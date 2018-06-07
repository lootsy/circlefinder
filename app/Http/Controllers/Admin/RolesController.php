<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\Traits\ResourceCrud;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class RolesController extends Controller
{
    use ResourceCrud;

    public function __construct()
    {
        $this->model = \App\Role::class;
        $this->indexRoute = 'admin.roles.index';
        $this->trashRoute = 'admin.roles.trash';
        $this->viewFolder = 'admin.roles';

        $this->validationRules = [
            'name' => 'required|alpha_dash|unique:roles,name',
            'title' => 'required',
        ];

        $this->validationRuleIdSuffix = ['name'];

        $this->setupCrud();
    }
}
