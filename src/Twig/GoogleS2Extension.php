<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GoogleS2Extension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('google_s2_favicon', [$this, 'googleS2Favicon']),
        ];
    }

    public function googleS2Favicon(string $url)
    {
        if (null !== ($host = $this->extractDomain($url))) {
            return sprintf('https://www.google.com/s2/favicons?domain=%s', $host);
        }

        return null;
    }

    private function extractDomain(string $url)
    {
        $parsedUrl = parse_url($url);

        if (isset($parsedUrl['host'])) {
            return $parsedUrl['host'];
        }

        return null;
    }
}