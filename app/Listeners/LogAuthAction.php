<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Spatie\Activitylog\Models\Activity;

class LogAuthAction
{
    public function handleLogin(Login $event)
    {
        $user = $event->user;
        activity('Auth')
            ->performedOn($user)
            ->causedBy($user)
            ->log("User {$user->username} has logged in.");
    }

    public function handleLogout(Logout $event)
    {
        if ($event->user) {
            $user = $event->user;
            activity('Auth')
                ->performedOn($user)
                ->causedBy($user)
                ->log("User {$user->username} has logged out.");
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            Login::class,
            [LogAuthAction::class, 'handleLogin']
        );

        $events->listen(
            Logout::class,
            [LogAuthAction::class, 'handleLogout']
        );
    }
}
