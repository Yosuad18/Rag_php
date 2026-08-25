<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        return view('chat.index');
    }
}
