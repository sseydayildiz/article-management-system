<?php

use App\Models\UserActionLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

if (!function_exists('log_user_action')) {
    function log_user_action(string $action, array $data = [])
    {
        // Gelen veriyi JSON formatına çeviriyoruz
        $postData = !empty($data) 
            ? json_encode($data, JSON_UNESCAPED_UNICODE) 
            : (!empty(request()->except(['_token', 'password', '_method'])) 
                ? json_encode(request()->except(['_token', 'password', '_method']), JSON_UNESCAPED_UNICODE) 
                : null);

        // Veritabanına user_agent ve post_data ile birlikte yazıyoruz
        \DB::table('user_action_logs')->insert([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'ip'         => request()->ip(),
            'user_agent' => request()->userAgent(),
            'post_data'  => $postData,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}