<?php
namespace App\Listeners;

use MikeMcLin\WpPassword\Facades\WpPassword;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Support\Facades\Hash;

class WordPressPasswordUpdate
{
    public function handle(Attempting $event)
    {
        $this->check($event->credentials['password'], \App\Models\User::where('email', $event->credentials['email'])->first()->password ?? 'not found');
    }

    public function check($value, $hashedValue, array $options = [])
    {
        if (Hash::needsRehash($hashedValue)) 
        {
            if (WpPassword::check($value, $hashedValue)) 
            {
                $newHashedValue = (new \Illuminate\Hashing\BcryptHasher)->make($value, $options);
                \Illuminate\Support\Facades\DB::update('UPDATE users SET `password` = "' . $newHashedValue . '" WHERE `password` = "' . $hashedValue . '"');
                $hashedValue = $newHashedValue;
            }
        }
    }
}