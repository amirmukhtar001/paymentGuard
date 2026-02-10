<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $user = Auth::user();
        $data = [
            'user' => $user,
            'apps' => [],
        ];

        return view('layouts.app_screen_frest', $data);
    }

    public function search()
    {
        return view('index', ['title' => 'Search']);
    }
}
