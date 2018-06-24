<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function edit($circle_uuid, Request $request)
    {
        $circle = \App\Circle::withUuid($circle_uuid)->firstOrFail();

        $user = auth()->user();

        $item = \App\Membership::where([
            'circle_id' => $circle->id,
            'user_id' => $user->id
        ])->firstOrFail();

        $this->authorize('update', $item);

        return $circle_uuid;
    }

    public function update($circle_uuid, Request $request)
    {
        return $circle_uuid;
    }
}
