<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        return auth()->user()->notifications;
    }

    public function markAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }
}
