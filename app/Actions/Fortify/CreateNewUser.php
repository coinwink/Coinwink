<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        // RATE LIMITER
        if(isset($_SERVER["HTTP_CF_CONNECTING_IP"])){ 
            $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        else {
            $ip = 'local';
        }
        $min = date("Y-m-d H:i:s");
        $time = strtotime($min);
        $time = $time - (1 * 60);
        $date = date("Y-m-d H:i:s", $time);

        $req_count = DB::table('cw_rate_limiter')->where('ip', $ip)->where('date', '>', $date)->count();

        if ($req_count > 5) {
            echo("Limit error");
            exit();
        }

        DB::table('cw_rate_limiter')->insert(
            ['ip' => $ip, 'date' => $min, 'action' => 'CreateNewUser']
        );
        
        // @todo: maybe remove name field from db?
        $input['name'] = $input['email'];

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();
        
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        
        // Insert new user into cw_setttings table
        //
        function getProtectedValue($obj, $name) {
            $array = (array)$obj;
            $prefix = chr(0).'*'.chr(0);
            return $array[$prefix.$name];
        }

        $attributes = getProtectedValue($user, 'attributes');
        $user_id = $attributes["id"];

		// $unique_id = DB::table('cw_alerts_email_cur')->select('unique_id')->where('email', '=', $input['email'])->get();
        $unique_id = DB::table('cw_alerts_email_cur')->where('email', $input['email'])->value('unique_id');
		if(!$unique_id) {
            $unique_id = uniqid();
		}
        
        DB::table('cw_settings')->insert(array(
            'user_ID' => $user_id,
            'unique_id' => $unique_id,
            'email' => $input['email'],
            'theme' => '',
            't_s' => 0,
            't_i' => '',
            'phone_nr' => '',
            'tg_user' => '',
            'tg_id' => '',
            'cw_tab' => '',
            'conv_exp' => 0,
            'cur_main' => '',
            'cur_p' => '',
            'cur_w' => '',
            'conf_w' => '',
            'portfolio' => '',
            'watchlist' => ''
        ));

        return $user;
    }
}
