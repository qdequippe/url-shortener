<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StatsExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('visits_per_period_data', [StatsRuntime::class, 'visitsPerPeriodData']),
            new TwigFunction('referrers_data', [StatsRuntime::class, 'referrersData']),
            new TwigFunction('browsers_data', [StatsRuntime::class, 'browsersData']),
            new TwigFunction('countries_data', [StatsRuntime::class, 'countriesData']),
            new TwigFunction('os_data', [StatsRuntime::class, 'osData']),
        ];
    }
}