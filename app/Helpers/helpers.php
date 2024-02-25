<?php
namespace App\Helpers;

use \Illuminate\Support\Facades\DB;

if (!function_exists('transaction')) {
    function transaction(\Closure $callback, $attempts = 1)
    {
        if (DB::transactionLevel()){
            return $callback();
        }
        return DB::transaction($callback,$attempts);
    }
}
