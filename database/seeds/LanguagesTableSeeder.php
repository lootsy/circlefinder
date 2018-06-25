<?php

use Illuminate\Database\Seeder;

class LanguagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = \App\Language::getListOfLanguages();

        foreach($languages as $code => $name)
        {
            \App\Language::firstOrCreate([
                'code' => $code,
                'title' => $name
            ]);
        }
    }
}
