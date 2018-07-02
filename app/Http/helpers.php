<?php

if (!function_exists('is_trash')) {
    function is_trash_route()
    {
        $current_route = request()->route()->getName();

        $last_part = last(explode('.', $current_route));

        if ($last_part === 'trash') {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('translate_type')) {
    function translate_type($type)
    {
        $translations = ['virtual' => _('Virtual'), 'f2f' => _('Face to face'), 'any' => _('Any')];

        return $translations[$type];
    }
}

if (!function_exists('list_languages')) {
    function list_languages($languages)
    {
        return $languages->sortBy('code')->implode('title', ', ');
    }
}

if (!function_exists('format_date')) {
    function format_date($date)
    {
        return $date->formatLocalized('%x');
    }
}

if (!function_exists('list_of_types')) {
    function list_of_types()
    {
        $fullList = [];

        foreach (config('circle.defaults.types') as $type) {
            $fullList[$type] = translate_type($type);
        }

        return $fullList;
    }
}

if (!function_exists('circle_state')) {
    function circle_state($circle)
    {
        if ($circle->completed) {
            return _('Completed');
        }

        if ($circle->full()) {
            return _('Full');
        }

        return sprintf('Open (%d / %d)', $circle->memberships()->count(), $circle->limit);
    }
}
