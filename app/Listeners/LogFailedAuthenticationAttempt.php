<?php

namespace App\Listeners;

use Auth;
use Hash;
use App\Models\User as User;
use Illuminate\Auth\Events\Failed;
use MikeMcLin\WpPassword\Facades\WpPassword;

class LogFailedAuthenticationAttempt
{
    /**
     * Handle the event.
     *
     * @param  Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
        $user = User::where('email', $event->credentials['email'])->first();
        if ($user) {
            if (WpPassword::check($event->credentials['password'], $user->password)) {
                Auth::login($user);
                $user->password = Hash::make($event->credentials['password']);
                $user->save();
            }
            // else {
            //     // return credentials error msg
            // }
        }
    }
}