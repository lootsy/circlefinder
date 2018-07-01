<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        $model = \App\Circle::orderBy('id', 'desc');
        $model = $model->with(['memberships']);
        $items = $model->paginate(10);

        return view('guest.index')->with([
            'items' => $items,
        ]);
    }
}
