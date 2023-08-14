<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Country\Helper;

use BeastBytes\Country\CountryDataInterface;

class Country
{
    private const UC = '^';

    public static function formatAddress(array $address, string $country, CountryDataInterface $countryData): string
    {
        $result = strtr($countryData->getAddressFormat($country), self::prepare($address));

        $offset = strpos($result, self::UC);
        $length = strrpos($result, self::UC) - $offset;
        $result = substr_replace(
            $result,
            strtoupper(substr($result, $offset + 1, $length - 1)),
            $offset,
            $length + 1
        );

        return trim(preg_replace(['/\{.+?}/', '/ +/', '/ ?\n+ ?/'], ['', ' ', "\n"], $result));
    }

    public static function formatName(array $name, string $country, CountryDataInterface $countryData): string
    {
        $result = strtr($countryData->getNameFormat($country), self::prepare($name));
        return preg_replace('/\s\{.+?}/', '', $result);
    }

    private static function prepare(array $data): array
    {
        $new = [];

        foreach ($data as $key => $value) {
            $new['{' . $key . '}'] = $value;
        }

        return $new;
    }
}
