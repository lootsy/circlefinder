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
        $countries_path = database_path('data/location/countries.json');
        $states_path = database_path('data/location/states.json');
        $cities_path = database_path('data/location/cities.json');

        $countries_source = json_decode(file_get_contents($countries_path));
        $states_source = json_decode(file_get_contents($states_path));
        $cities_source = json_decode(file_get_contents($cities_path));

        

        \App\Country::truncate();
        \App\State::truncate();
        \App\City::truncate();

        print("Creating countries...\n");
        foreach ($countries_source->countries as $country_data) {
            $country = \App\Country::create([
                'code' => $country_data->sortname,
                'name' => $country_data->name
            ]);
        }

        print("Creating states...\n");
        $country_cache = array();
        foreach ($states_source->states as $state_data) {
            if(key_exists($state_data->country_id, $country_cache) == false) {
                $country_cache[$state_data->country_id] = \App\Country::find($state_data->country_id);
            }

            $country = $country_cache[$state_data->country_id];

            $state = $country->states()->create([
                'code' => $state_data->id,
                'name' => $state_data->name
            ]);
        }

        print("Creating cities...\n");
        $state_cache = array();
        foreach ($cities_source->cities as $city_data) {
            if(key_exists($city_data->state_id, $state_cache) == false) {
                $state_cache[$city_data->state_id] = \App\State::find($city_data->state_id);
            }

            $state = $state_cache[$city_data->state_id];

            $city = $state->cities()->create([
                'code' => $city_data->id,
                'name' => $city_data->name,
                'timezone' => 'xyz',
            ]);
        }
       
        return;
        

        $state = $country->states()->create([
            'name' => 'Niedersachsen'
        ]);

        $city = $state->cities()->create([
            'name' => 'Hannover',
            'timezone' => 'Europe/Berlin'
        ]);


        /*
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
        */


    }
}
