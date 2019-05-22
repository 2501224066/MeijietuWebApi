<?php


namespace App\Listeners;


use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;

class SqlListener
{
    public function __construct()
    {
    }

    public function handle(QueryExecuted $queryExecuted)
    {
        // 将SQL记录写入日志
        $sql = str_replace('?', '"'.'%s'.'"', $queryExecuted->sql);
        $spendTime = '【SQL】 execution time: '.$queryExecuted->time.'ms; ';
        $log = vsprintf($sql, $queryExecuted->bindings);
        Log::info($spendTime . $log);
    }
}