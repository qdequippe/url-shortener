<?php

namespace App\Twig;

use App\Entity\Link;
use App\Entity\Visit;
use App\Repository\VisitRepository;
use Twig\Extension\RuntimeExtensionInterface;

class StatsRuntime implements RuntimeExtensionInterface
{
    /**
     * @var VisitRepository
     */
    private $visitRepository;

    public function __construct(VisitRepository $visitRepository)
    {
        $this->visitRepository = $visitRepository;
    }

    public function visitsPerPeriodData(Link $link, string $period)
    {
        return $this->visitRepository->countPerPeriod($link);
    }

    public function referrersData(Link $link, string $period)
    {
        $visits = $this->visitRepository->findBy(['link' => $link]);

        $data = [];

        foreach ($visits as $visit) {
            foreach ($visit->getReferrers() as $key => $value) {
                if (isset($data[$key])) {
                    $data[$key] += $value;

                    continue;
                }

                $data[$key] = $value;
            }
        }

        return [
            'keys' => json_encode(array_keys($data)),
            'values' => json_encode(array_values($data))
        ];
    }

    public function browsersData(Link $link, string $period)
    {
        $result = $this->visitRepository->countPerBrowser($link);

        $browsers = array_merge(['Other'], Visit::BROWSERS_LIST);

        return [
            'keys' => json_encode($browsers),
            'values' => json_encode(array_values($result))
        ];
    }

    public function countriesData(Link $link, string $period)
    {
        $visits = $this->visitRepository->findBy(['link' => $link]);

        $data = [];

        foreach ($visits as $visit) {
            foreach ($visit->getCountries() as $key => $value) {
                if (isset($data[$key])) {
                    $data[$key] += $value;

                    continue;
                }

                $data[$key] = $value;
            }
        }

        return [
            'keys' => json_encode(array_keys($data)),
            'values' => json_encode(array_values($data))
        ];
    }

    public function osData(Link $link, string $period)
    {
        $result = $this->visitRepository->countPerOs($link);

        $browsers = array_merge(['Other'], Visit::OS_LIST);

        return [
            'keys' => json_encode($browsers),
            'values' => json_encode(array_values($result))
        ];
    }
}