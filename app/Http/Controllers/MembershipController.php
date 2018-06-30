<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MembershipController extends Controller
{
    private function getUserAndMembership($circle_uuid)
    {
        $circle = \App\Circle::withUuid($circle_uuid)->firstOrFail();

        $user = auth()->user();

        $item = \App\Membership::where([
            'circle_id' => $circle->id,
            'user_id' => $user->id
        ])->firstOrFail();

        return [$user, $item, $circle];
    }

    public function edit($circle_uuid, Request $request)
    {
        list($user, $item, $circle) = $this->getUserAndMembership($circle_uuid);

        $this->authorize('update', $item);

        $timeTable = \App\TimeTable::updateOrCreateForMemebership($item, $request->all());

        return view('membership.edit')->with([
            'item' => $item,
            'timeTable' => $timeTable
        ]);
    }

    public function update($circle_uuid, Request $request)
    {
        list($user, $item, $circle) = $this->getUserAndMembership($circle_uuid);

        $this->authorize('update', $item);

        $this->validate($request, \App\Membership::validationRules());

        $timeTable = \App\TimeTable::updateOrCreateForMemebership($item, $request->all());

        $item->updateAndModify($request);

        return redirect()->route('circles.show', $circle->uuid)->with([
            'success' => sprintf('%s was updated!', (string) $item)
        ]);
    }
}
