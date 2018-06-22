<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class CirclesController extends Controller
{
    public function __construct()
    {
        $this->items_per_page = Config::get('circles.listing.items_per_page');
    }

    public function index()
    {
        $model = \App\Circle::orderBy('id', 'desc');
        $model = $model->with(['memberships', 'users', 'user']);
        $items = $model->paginate($this->items_per_page);
        
        return view('circles.index')->with([
            'items' => $items
        ]);
    }

    public function show($uuid, Request $request)
    {
        $item = \App\Circle::withUuid($uuid)->firstOrFail();
        
        $this->authorize('view', $item);

        $user = auth()->user();
        
        return view('circles.show')->with([
            'item' => $item,
            'user' => $user
        ]);
    }

    public function edit($uuid, Request $request)
    {
        $item = \App\Circle::withUuid($uuid)->firstOrFail();
        
        $this->authorize('update', $item);

        return view('circles.edit')->with([
            'item' => $item
        ]);
    }

    public function update($uuid, Request $request)
    {
        $item = \App\Circle::withUuid($uuid)->firstOrFail();
        
        $this->authorize('update', $item);

        $this->validate($request, \App\Circle::validationRules());

        $item->update($request->all());

        return redirect()->route('circles.show', $item->uuid)->with([
            'success' => sprintf('%s was updated!', (string) $item)
        ]);
    }

    
}
