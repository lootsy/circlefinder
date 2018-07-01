<?php

namespace App\Console\Commands;

use App;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup CirleFinder';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function createModeratorRole()
    {
        \App\Role::firstOrCreate([
            'name' => 'moderator',
            'title' => 'Moderator',
        ]);

        $this->info('New Role "moderator" created');

        return true;
    }

    public function createLanguages()
    {
        $list = \App\Language::getListOfLanguages();

        foreach ($list as $code => $title) {
            if (\App\Language::where('code', $code)->count() == 0) {
                \App\Language::create([
                    'code' => $code,
                    'title' => $title,
                ]);
            }
        }

        $this->info('List of languages created');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Running migrations...');
        Artisan::call('migrate');

        $this->info('Refreshing version...');
        Artisan::call('version:refresh');

        if ($this->createModeratorRole() == false) {
            return;
        }

        if ($this->createLanguages() == false) {
            return;
        }
    }
}
