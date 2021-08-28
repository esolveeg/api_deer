<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class UserController extends Controller
{
    public function list($type)
    {
        // dd((int)$request->byWeight);
        $pipeline = app(Pipeline::class)->send(User::query()->where('type' , $type))->through([])->thenReturn();
        $users = $pipeline->paginate(8);

        return $users;
    }
}
