<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    private function getMessage($message_uuid)
    {
        $circle = \App\Message::withUuid($circle_uuid)->firstOrFail();

        $user = auth()->user();

        $item = \App\Membership::where([
            'circle_id' => $circle->id,
            'user_id' => $user->id
        ])->firstOrFail();

        return [$user, $item, $circle];
    }

    public function store($circle_uuid, Request $request)
    {
        return sprintf('%s %s', $circle_uuid, $uuid);
    }

    public function update($circle_uuid, $uuid, Request $request)
    {
        return sprintf('%s %s', $circle_uuid, $uuid);
    }
}
