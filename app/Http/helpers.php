<?php

use Intervention\Image\Facades\Image;

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

        return _('Open (' . count($circle->memberships) . '/' . $circle->limit . ')');
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

if (!function_exists('user_picture')) {
    function user_avatar($user, $size = null, $only_url = false, $with_link = false)
    {
        $placeholder = 'no_avatar.jpeg';
        $image_url = '';
        $retina_image_url = '';

        if ($size == null) {
            $size = config('userprofile.avatar.size');
        }

        if ($user->avatar) {
            $image_url = route('profile.avatar.download.resized', ['uuid' => $user->uuid, 'w' => $size, 'h' => $size]);
            $retina_image_url = route(
                'profile.avatar.download.resized',
                ['uuid' => $user->uuid, 'w' => $size * 2, 'h' => $size * 2]
            );
        } else {
            foreach ([$size, $size * 2] as $s) {
                $new_file_path = sprintf('images/%d_%d_%s', $s, $s, $placeholder);

                if (Storage::disk('public')->exists($new_file_path) == false) {
                    $image = Image::make(resource_path('assets/images/' . $placeholder));
                    $image->resize($s, $s);
                    Storage::disk('public')->put($new_file_path, (string) $image->encode('jpg'));
                }
            }

            $image_url = url(sprintf('images/%d_%d_%s', $size, $size, $placeholder));
            $retina_image_url = url(sprintf('images/%d_%d_%s', $size * 2, $size * 2, $placeholder));
        }

        if ($only_url) {
            return $image_url;
        }

        $pre = '';
        $post = '';

        if ($with_link) {
            $pre = sprintf('<a href="%s">', route('profile.show', ['uuid' => $user->uuid]));
            $post = '</a>';
        }

        return sprintf(
            '%s<img src="%s" srcset="%s 2x" alt="%s" />%s',
            $pre,
            $image_url,
            $retina_image_url,
            htmlspecialchars($user->name),
            $post
        );
    }
}
