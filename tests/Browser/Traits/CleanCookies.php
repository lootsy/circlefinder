<?php

namespace Tests\Browser\Traits;

trait CleanCookies
{
    public function setUp()
    {
        parent::setUp();
        
        foreach (static::$browsers as $browser) {
            $browser->driver->manage()->deleteAllCookies();
        }
    }
}