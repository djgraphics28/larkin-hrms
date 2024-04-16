<?php

namespace App\Helpers;

use Carbon\Carbon;

class Helpers
{
    /**
     * Formats a date to a desired format.
     *
     * @param string $date The date to format.
     * @param string $format The desired output format (e.g., "Y-m-d", "d/m/Y").
     * @return string The formatted date.
     */
    public static function formatDate($date, $format = 'Y-m-d')
    {
        return Carbon::parse($date)->format($format);
    }

    /**
     * Generates a random string of a specified length.
     *
     * @param int $length The desired length of the random string.
     * @return string The generated random string.
     */
    public static function generateRandomString($length = 16)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    // Add more helper methods as needed...
}
