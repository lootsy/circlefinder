<?php

if(!function_exists('is_trash'))
{
    function is_trash_route()
    {
        $current_route = request()->route()->getName();

        $last_part = last(explode('.', $current_route));

        if($last_part === 'trash')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}