<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Helpers\ActivityHelper;

protected $listen = [
    Login::class => [
        function ($event) {
            ActivityHelper::log(
                'Login',
                'auth',
                'User login: ' . $event->user->email
            );
        },
    ],
    Logout::class => [
        function ($event) {
            ActivityHelper::log(
                'Logout',
                'auth',
                'User logout: ' . $event->user->email
            );
        },
    ],
];
