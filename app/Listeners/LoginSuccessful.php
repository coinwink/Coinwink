<?php

namespace App\Listeners;

use IlluminateAuthEventsLogin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoginSuccessful
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \IlluminateAuthEventsLogin  $event
     * @return void
     */

    // public function handle(IlluminateAuthEventsLogin $event)
    public function handle()
    {
        // Update user last login time
        $id_user = Auth::user()->id;
        $timestamp = date("Y-m-d H:i:s");
        DB::table('cw_settings')->where('user_ID', $id_user)->update(array(
            'last_login' => $timestamp
        ));
    }
}
