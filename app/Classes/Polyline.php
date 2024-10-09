<?php

namespace App\Classes;

use InvalidArgumentException;

class Polyline
{
    /**
     * Default precision level of 1e-5.
     *
     * Overwrite this property in extended class to adjust precision of numbers.
     * !!!CAUTION!!!
     * 1) Adjusting this value will not guarantee that third party
     *    libraries will understand the change.
     * 2) Float point arithmetic IS NOT real number arithmetic. PHP's internal
     *    float precision may contribute to undesired rounding.
     *
     */
    protected static int $precision = 5;

    /**
     * Apply Google Polyline algorithm to list of points.
     * @param array $points List of points to encode. Each point must be [latitude, longitude].
     * @return string encoded string
     */
    final public static function encode(array $points): string
    {
        $points = self::flatten($points);
        if (count($points) % 2 !== 0) {
            throw new InvalidArgumentException('Each point must have both latitude and longitude.');
        }

        $encodedString = '';
        $prevLat = 0;
        $prevLng = 0;

        foreach (array_chunk($points, 2) as [$lat, $lng]) {
            $lat = (int) round($lat * (10 ** static::$precision));
            $lng = (int) round($lng * (10 ** static::$precision));

            $dLat = $lat - $prevLat;
            $dLng = $lng - $prevLng;

            $encodedString .= self::encodeNumber($dLat) . self::encodeNumber($dLng);

            $prevLat = $lat;
            $prevLng = $lng;
        }

        return $encodedString;
    }

    /**
     * Encode a number according to Google Polyline algorithm.
     *
     * @param int $number Number to encode.
     * @return string Encoded number.
     */
    private static function encodeNumber(int $number): string
    {
        $number = ($number < 0) ? ~($number << 1) : ($number << 1);
        $chunk = '';

        while ($number >= 0x20) {
            $chunk .= chr((0x20 | ($number & 0x1f)) + 63);
            $number >>= 5;
        }

        $chunk .= chr($number + 63);
        return $chunk;
    }

    /**
     * Decode polyline string into list of points.
     * @param string $string Encoded polyline string.
     * @return array Decoded list of points.
     */
    final public static function decode(string $string): array
    {
        $points = [];
        $index = 0;
        $lat = 0;
        $lng = 0;

        while ($index < strlen($string)) {
            $lat += self::decodeNumber($string, $index);
            $lng += self::decodeNumber($string, $index);

            $points[] = [$lat / (10 ** static::$precision), $lng / (10 ** static::$precision)];
        }

        return $points;
    }

    /**
     * Decode number from the encoded polyline string.
     * @param string $string The encoded string.
     * @param int &$index Reference to the current position in the string.
     * @return int Decoded number.
     */
    private static function decodeNumber(string $string, int &$index): int
    {
        $shift = $result = 0;
        do {
            $bit = ord($string[$index++]) - 63;
            $result |= ($bit & 0x1f) << $shift;
            $shift += 5;
        } while ($bit >= 0x20);

        return ($result & 1) ? ~($result >> 1) : ($result >> 1);
    }

    /**
     * Flatten a multi-dimensional array into a one-dimensional array.
     * @param array $array Subject array to flatten.
     * @return array Flattened array.
     */
    final public static function flatten(array $array): array
    {
        $flatten = [];
        array_walk_recursive($array, static function ($value) use (&$flatten) {
            $flatten[] = $value;
        });

        return $flatten;
    }

    /**
     * Concat list into pairs of points
     * @param array $list One-dimensional array to segment into list of tuples.
     * @return array pairs
     */
    final public static function pair(array $list): array
    {
        return array_chunk($list, 2);
    }

    /**
     * Find extreme coordinates (SW and NE).
     * @param array $coordinates List of [latitude, longitude] pairs.
     * @return array Array with SW (southwest) and NE (northeast) corners.
     */
    final public static function findExtremeCoordinates(array $coordinates): array
    {
        if (empty($coordinates)) {
            throw new InvalidArgumentException('Coordinates array cannot be empty.');
        }

        $minLat = $maxLat = $coordinates[0][0];
        $minLng = $maxLng = $coordinates[0][1];

        foreach ($coordinates as [$lat, $lng]) {
            $minLat = min($minLat, $lat);
            $maxLat = max($maxLat, $lat);
            $minLng = min($minLng, $lng);
            $maxLng = max($maxLng, $lng);
        }

        return ['SW' => [$minLat, $minLng], 'NE' => [$maxLat, $maxLng]];
    }

    public static function convertFitLocationToPolyline(array $locationData = []): string
    {
        $points = [];

        foreach ($locationData['position_lat'] as $key => $value) {
            if ($locationData['position_long'][$key]) {
                $points[] = [$value, $locationData['position_long'][$key]];
            }
        }

        return self::encode($points);
    }
}
