<?php

namespace App\Controller;

use App\Entity\Link;
use App\Entity\Visit;
use App\Form\LinkType;
use App\Repository\LinkRepository;
use App\Repository\VisitRepository;
use DeviceDetector\DeviceDetector;
use Hidehalo\Nanoid\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LinkController extends AbstractController
{
    /**
     * @Route("/links", name="link_index", methods={"GET"})
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
     * @param Request $request
     * @param Link    $link
     *
     * @return Response
     */
    public function goTo(Request $request, Link $link): Response
    {
        $dd = new DeviceDetector($request->headers->get('user-agent'));
        $dd->parse();



        if (!$dd->isBot()) {
            $visit = new Visit();
            $visit->setLink($link);
            $visit->setBrowser($dd->getClient()['name']);
            $visit->setOs($dd->getOs()['name']);
            $visit->setReferrers([$request->headers->get('referer', 'Direct')]);
            $visit->setCountries(['Unknown']);

            $link->incVisitCount();

            $em = $this->getDoctrine()->getManager();
            $em->persist($visit);
            $em->flush();;
        }

        return new RedirectResponse($link->getTarget());
    }

    /**
     * @Route("/links/{id}/stats", name="link_stats", methods={"GET"})
     * @param Link            $link
     *
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
