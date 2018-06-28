<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {name} {email} {pass}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new admin';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->min_pass_len = 6;
        parent::__construct();
    }

    private function validatePassword($password)
    {
        if (strlen($password) < $this->min_pass_len) {
            $this->error(sprintf('The password shall be at least %s charachters long!', $this->min_pass_len));
            return false;
        }

        return true;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $password = "";
        $min_pass_len = 6;
        $existing_admin = \App\Admin::where('email', $this->argument('email'))->first();

        if ($existing_admin) {
            return $this->error('Admin with this email is already in the database!');
        }

        $pwd_from_cli = $this->argument('pass');

        if ($this->validatePassword($pwd_from_cli) == false) {
            return -1;
        } else {
            $password = $pwd_from_cli;
        }

        $admin = new \App\Admin();
        $admin->password = Hash::make($password);
        $admin->email = $this->argument('email');
        $admin->name = $this->argument('name');
        $admin->save();

        return $this->info('New admin created!');
    }
}
