<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->unreadNotifications->count()) {
            $user->unreadNotifications->markAsRead();
        }
    }
}
