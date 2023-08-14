<?php
/**
 * @copyright Copyright © 2023 BeastBytes - All rights reserved
 * @license BSD 3-Clause
 */

declare(strict_types=1);

namespace BeastBytes\Country\Helper\Tests;

use BeastBytes\Country\CountryDataInterface;
use BeastBytes\Country\PHP\CountryData;
use BeastBytes\Country\Helper\Country;
use Generator;
use PHPUnit\Framework\Attributes\BeforeClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CountryHelperTest extends TestCase
{
    private static CountryDataInterface $countryData;

    #[BeforeClass]
    public static function init(): void
    {
        self::$countryData = new CountryData();
    }

    #[DataProvider('addressProvider')]
    public function test_address_format(array $address, string $country, string $expected): void
    {
        $this->assertSame($expected, Country::formatAddress($address, $country, self::$countryData));
    }

    #[DataProvider('nameProvider')]
    public function test_name_format(array $name, string $country, string $expected): void
    {
        $this->assertSame($expected, Country::formatName($name, $country, self::$countryData));
    }

    public static function addressProvider(): Generator
    {
        foreach ([
            'GB' => [
                'address' => [
                    'street_address' => '10 Downing Street',
                    'locality' => 'City of Westminster',
                    'region' => 'London',
                    'postal_code' => 'SW1'
                ],
                'country' => 'GB',
                'expected' => "10 Downing Street\nCity of Westminster\nLondon SW1",
            ],
            'GB with person' => [
                'address' => [
                    'recipient' => 'Mr. W. Churchill',
                    'job_title' => 'Prime Minister',
                    'organization_unit' => 'UK Government',
                    'street_address' => '10 Downing Street',
                    'locality' => 'City of Westminster',
                    'region' => 'London',
                    'postal_code' => 'SW1'
                ],
                'country' => 'GB',
                'expected' => "Mr. W. Churchill\nPrime Minister\nUK Government\n10 Downing Street\nCity of Westminster\nLondon SW1",
            ],
            'DE' => [
                'address' => [
                    'street_address' => "Reichstag\nPlatz der Republik 1",
                    'locality' => 'Berlin',
                    'postal_code' => '11011',
                    'country' => 'Deutchland'
                ],
                'country' => 'DE',
                'expected' => "Reichstag\nPlatz der Republik 1\n11011 Berlin\nDEUTCHLAND",
            ],
        ] as $name => $value) {
            yield $name => $value;
        }
    }

    public static function nameProvider(): Generator
    {
        foreach ([
            'GB' => [
                'name' => [
                    'honorific_prefix' => 'Mr',
                    'given_name' => 'John',
                    'family_name' => 'Smith',
                 ],
                'country' => 'GB',
                'expected' => 'Mr John Smith',
            ],
            'JP' => [
                'name' => [
                    'honorific_prefix' => 'Mr',
                    'given_name' => 'Tarō',
                    'family_name' => 'Yamada',
                ],
                'country' => 'JP',
                'expected' => 'Mr Yamada Tarō',
            ]
        ] as $name => $value) {
            yield $name => $value;
        }
    }
}
