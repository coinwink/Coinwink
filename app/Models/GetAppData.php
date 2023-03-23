<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

class GetAppData
{
    public static function get($id_user) {
        // $cmc = DB::table('cw_data_cmc')->where('ID', '=', 1)->pluck('json');
        $rates = DB::table('cw_data_cur_rates')->get();
        if ($id_user) {
            $settings = DB::table('cw_settings')->where('user_ID', '=', $id_user)->get();
            $subs = DB::table('cw_subs')->where('user_ID', $id_user)->select('date_end', 'status', 'plan', 'date_renewed', 'months')->get();
            // return [ $cmc[0], $rates, $settings[0], $subs ];
            return [ $rates, $settings[0], $subs ];
        }
        else {
            // return [ $cmc[0], $rates, null, null ];
            return [ $rates, null, null ];
        }
    }
}
