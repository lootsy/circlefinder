<?php

use Illuminate\Database\Seeder;
use PragmaRX\Countries\Package\Countries;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = new Countries();

        $all = $countries->all();

        foreach ($all as $countrySource) {
            print('Loading ' . $countrySource->name->common . " ...\n");

            try {
                $first_time_zone = $countrySource->hydrate('timezones')->timezones->first()->zone_name;
            } catch (Exception $ex) {
                $first_time_zone = '';
            }

            $country = \App\Country::create([
                'iso' => $countrySource['cca2'],
                'name' => $countrySource->name->official,
                'name_common' => $countrySource->name->common,
                'timezone' => $first_time_zone,
            ]);

            $citiesSource = $countrySource->hydrateCities()->cities;

            print(count($citiesSource) . " cities\n");

            $cities = $country->cities();

            foreach ($citiesSource as $city) {
                if (!$city['timezone']) {
                    $city['timezone'] = $first_time_zone;
                }

                if (!$city['timezone']) {
                    throw Exception('Timezone missing for ' . $city['name']);
                }

                $cities->create([
                    'name' => $city['name'],
                    'timezone' => $city['timezone'],
                    'latitude' => $city['latitude'],
                    'longitude' => $city['longitude'],
                ]);
            }
        }
    }
}
