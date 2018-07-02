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
    function list_languages($languages, $limit = 0)
    {
        $count = $languages->count();
        $list = $languages->sortBy('code');

        if ($limit > 0 && $limit < $count) {
            $list = $list->slice(0, $limit);
        }

        return $list->implode('title', ', ') . ($limit && ($limit < $count) ? ', ...' : '');
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

if (!function_exists('good_title')) {
    function good_title($item)
    {
        if ($item->title) {
            return $item->title . ' (' . $item . ')';
        } else {
            return (string) $item;
        }
    }
}
