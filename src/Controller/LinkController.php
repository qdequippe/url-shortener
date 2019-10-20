<?php

namespace App\Controller;

use App\Entity\Link;
use App\Entity\Visit;
use App\Form\LinkType;
use App\Plugin\FakeIpPlugin;
use App\Repository\LinkRepository;
use App\Repository\VisitRepository;
use DeviceDetector\DeviceDetector;
use Geocoder\Exception\Exception as GeocoderException;
use Geocoder\Location;
use Geocoder\Plugin\PluginProvider;
use Geocoder\Provider\Ipstack\Ipstack;
use Geocoder\Query\GeocodeQuery;
use Hidehalo\Nanoid\Client;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttplugClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use TheIconic\Tracking\GoogleAnalytics\Analytics;

class LinkController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var string
     */
    private $apiStackKey;

    /**
     * @var string
     */
    private $gaKey;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var VisitRepository
     */
    private $visitRepository;

    public function __construct(
        LoggerInterface $logger,
        string $apiStackKey,
        string $gaKey,
        SessionInterface $session,
        VisitRepository $visitRepository
    ) {
        $this->logger = $logger;
        $this->apiStackKey = $apiStackKey;
        $this->gaKey = $gaKey;
        $this->session = $session;
        $this->visitRepository = $visitRepository;
    }

    /**
     * @Route("/links", name="link_index", methods={"GET"})
     *
     * @param LinkRepository $linkRepository
     *
     * @return Response
     */
    public function index(LinkRepository $linkRepository): Response
    {
        return $this->render('link/index.html.twig', [
            'links' => $linkRepository->findAll(),
        ]);
    }

    /**
     * @Route("/links/new", name="link_new", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $link = new Link();
        $form = $this->createForm(LinkType::class, $link);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $client = new Client();
            $link->setAddress($client->generateId(6));

            $entityManager->persist($link);
            $entityManager->flush();

            return $this->redirectToRoute('link_index');
        }

        return $this->render('link/new.html.twig', [
            'link' => $link,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{address}", name="link_got_to", methods={"GET"})
     *
     * @param Request          $request
     * @param Link             $link
     *
     * @return Response
     */
    public function goTo(Request $request, Link $link): Response
    {
        $userAgent = $request->headers->get('user-agent');
        $dd = new DeviceDetector($userAgent);
        $dd->parse();

        $redirectResponse = new RedirectResponse($link->getTarget());

        if ($dd->isBot()) {
            return $redirectResponse;
        }

        $em = $this->getDoctrine()->getManager();
        $browserName = isset($dd->getClient()['name']) ? $dd->getClient()['name'] : 'Other';
        $osName = isset($dd->getOs()['name']) ? $dd->getOs()['name'] : 'Other';

        /** @var Visit $visit */
        $visit = $this->visitRepository->findVisitInLastHour($link);

        if (null === $visit) {
            $visit = new Visit();
            $visit->setLink($link);
            $em->persist($visit);
        }

        $visit->incTotal();
        $visit->incBrowser($browserName);
        $visit->incOs($osName);
        $visit->addReferrer($request->headers->get('referer', 'Email, SMS, Direct'));

        $clientIp = $request->getClientIp();

        $httpClient = new HttplugClient();
        $geocoder = new Ipstack($httpClient, $this->apiStackKey);
        $pluginProvider = new PluginProvider($geocoder, [new FakeIpPlugin()]);

        $countries = [];
        try {
            $results = $pluginProvider->geocodeQuery(GeocodeQuery::create($clientIp));

            /** @var Location $result */
            foreach ($results as $result) {
                $countries[] = $result->getCountry()->getName();
            }

            if (empty($countries)) {
                $countries = ['Unknown'];
            }
        } catch (GeocoderException $exception) {
            $this->logger->error($exception->getMessage());

            $countries = ['Unknown'];
        }

        foreach ($countries as $country) {
            $visit->addCountry($country);
        }

        $link->incVisitCount();

        $em->flush();

        $this->session->start();
        $this->session->save();

        if (!empty($this->gaKey)) {
            $analytics = (new Analytics())
                ->setProtocolVersion('1')
                ->setTrackingId($this->gaKey)
                ->setClientId($request->getSession()->getId())
                ->setDocumentPath(sprintf('/%s', $link->getAddress()))
                ->setAnonymizeIp(true)
                ->setIpOverride($clientIp)
                ->setUserAgentOverride($userAgent);

            $analytics->sendPageview();
        }

        return new RedirectResponse($link->getTarget());
    }

    /**
     * @Route("/links/{id}/stats", name="link_stats", methods={"GET"})
     *
     * @param Link            $link
     * @param VisitRepository $visitRepository
     *
     * @return Response
     */
    public function stats(Link $link, VisitRepository $visitRepository): Response
    {
        $visits = $visitRepository->findBy(['link' => $link]);

        return $this->render('link/stats.html.twig', [
            'link' => $link,
            'visits' => $visits,
        ]);
    }

    /**
     * @Route("/links/{id}", name="link_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Link    $link
     *
     * @return Response
     */
    public function delete(Request $request, Link $link): Response
    {
        if ($this->isCsrfTokenValid('delete'.$link->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($link);
            $entityManager->flush();
        }

        return $this->redirectToRoute('link_index');
    }
}
