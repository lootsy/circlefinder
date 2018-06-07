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
    protected $signature = 'admin:create {name} {email} {--pass=}';

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

    private function validate_password($password)
    {
        if (strlen($password) < $this->min_pass_len)
        {
            $this->error(sprintf('The password shall be at least %s charachters long!', $this->min_pass_len));
            return false;
        }

        return true;
    }

    public function passwordPrompt($min_pass_len = 6)
    {
        $password_ok = false;

        $password = "";

        while($password_ok == false)
        {
            $password = $this->secret('Please enter the admin password');

            if ($this->validate_password($password) == false)
            {
                continue;
            }

            $password_repeat = $this->secret('Please repeat the admin password');

            if($password === $password_repeat)
            {
                $password_ok = true;
            }
            else
            {
                $this->error('Passwords don\'t match!');
            }
        }

        return $password;
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

        if($existing_admin)
        {
            return $this->error('Admin with this email is already in the database!');
        }

        $pwd_from_cli = $this->option('pass');

        if(strlen($pwd_from_cli) > 0)
        {
            if($this->validate_password($pwd_from_cli) == false)
            {
                return -1;
            }
            else
            {
                $password = $pwd_from_cli;
            }
        }
        else
        {
            $password = $this->passwordPrompt($min_pass_len);
        }


        $admin = new \App\Admin();
        $admin->password = Hash::make($password);
        $admin->email = $this->argument('email');
        $admin->name = $this->argument('name');
        $admin->save();

        return $this->info('New admin created!');
    }
}
