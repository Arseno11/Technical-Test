<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index()
    {

        $users = User::latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }
}