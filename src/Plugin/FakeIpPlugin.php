<?php

/*
 * This file comes from https://github.com/geocoder-php/BazingaGeocoderBundle/blob/master/Plugin/FakeIpPlugin.php
 * This file has been copied for test reason
 */

namespace App\Plugin;

use Faker\Factory;
use Geocoder\Plugin\Plugin;
use Geocoder\Query\GeocodeQuery;
use Geocoder\Query\Query;

class FakeIpPlugin implements Plugin
{
    public function handleQuery(Query $query, callable $next, callable $first)
    {
        if (!$query instanceof GeocodeQuery) {
            return $next($query);
        }

        $faker = Factory::create();

        $text = str_replace('127.0.0.1', $faker->ipv4, $query->getText(), $count);

        if ($count > 0) {
            $query = $query->withText($text);
        }

        return $next($query);
    }
}
