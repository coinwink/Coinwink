<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateUsersTableForWp extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
        });
        
        // Migrate from WP Users > Laravel Users
        DB::table('wp_users')
            ->orderBy('id')
            ->chunk(100, function ($wp_users) {
                foreach ($wp_users as $wp_user) {

                    $id_db = DB::table('users')->orderBy('id', 'desc')->first();
                    if (isset($id_db)) {
                        $id_db = $id_db->id;
    
                        $id_diff = $wp_user->ID - $id_db;
    
                        if ($id_diff != 0) {
                            while ($id_diff > 1) {
                                DB::table('users')->insert(array(
                                    'name' => 'dummy74766125',
                                    'email' => bin2hex(openssl_random_pseudo_bytes(14)),
                                    'email_verified_at' => null,
                                    'password' => null,
                                    'two_factor_secret' => null,
                                    'two_factor_recovery_codes' => null,
                                    // 'two_factor_confirmed_at' => null,
                                    'remember_token' => null,
                                    'created_at' => null,
                                    'updated_at' => null
                                ));
                                $id_diff--;
                            }
                        }
                    }

                    // add new user (if doesn't exist)
                    User::firstOrCreate(
                        [
                            'email' => $wp_user->user_email,
                        ],
                        [
                            'name' => $wp_user->user_email,
                            'password' => $wp_user->user_pass
                        ]
                    );

                    if ($wp_user->ID > 42702 && $wp_user->ID < 42712) {
                        $theDate = new DateTime($wp_user->user_registered);
                        $theDate->modify('-2 days');
                        $stringDate = $theDate->format('Y-m-d H:i:s');
    

                        // Add 'user_registered' date to users table 'createad_at' and 'updated_at'
                        DB::table('users')->where('email', $wp_user->user_email)->update(array(
                            'created_at' => $stringDate,
                            'updated_at' => $stringDate
                        ));
    
                        // user's acc is verified if last_login is set
                        $last_login = DB::table('cw_settings')->where('user_ID', $wp_user->ID)->value('last_login');

                        // Add 'user_registered' date to users table 'email_verified_at'
                        if ($last_login) {
                            DB::table('users')->where('email', $wp_user->user_email)->update(array(
                                'email_verified_at' => $stringDate
                            ));
                        }

                    }

                    else {

                        // Add 'user_registered' date to users table 'createad_at' and 'updated_at'
                        DB::table('users')->where('email', $wp_user->user_email)->update(array(
                            'created_at' => $wp_user->user_registered,
                            'updated_at' => $wp_user->user_registered
                        ));
    
                        // user's acc is verified if last_login is set
                        $last_login = DB::table('cw_settings')->where('user_ID', $wp_user->ID)->value('last_login');

                        // Add 'user_registered' date to users table 'email_verified_at'
                        if ($last_login) {
                            DB::table('users')->where('email', $wp_user->user_email)->update(array(
                                'email_verified_at' => $wp_user->user_registered
                            ));
                        }

                    }
                }
            });
            
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}