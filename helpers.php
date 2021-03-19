<?php

if (! function_exists('unique_field_generator')) {
    function unique_field_generator(): string
    {
        $ip = \Request::ip();
        $time_zone = geoip($ip)->timezone;

        $date = \Illuminate\Support\Carbon::today($time_zone)->format('ymd');
        $rand = mt_rand(1,4);
        $last_val = Illuminate\Support\Facades\Cache::tags([$time_zone])->get('last_code');
        $start = \Illuminate\Support\Carbon::parse(\Illuminate\Support\Carbon::now($time_zone));
        $end = \Illuminate\Support\Carbon::parse('23:59:59',geoip($ip)->timezone);
        $diff_sec = $end->diffInSeconds($start);

        if ($last_val === null){
            Illuminate\Support\Facades\Cache::tags([$time_zone])->put('last_code', $rand, $diff_sec);
            return $rand.'-'.$date;
        }else{
            $new_code = $last_val + 1;
            Illuminate\Support\Facades\Cache::tags([$time_zone])->put('last_code', $new_code, $diff_sec);
            return $new_code.'-'.$date;
        }

    }
}
