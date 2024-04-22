<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CounterController extends Controller
{
    public function index()
    {
        $counters = Counter::all();
        return view('counters.index', compact('counters'));
    }

    public function serving()
    {
        $sql = <<<SQL
        SELECT `a`.`updated_at`,
        `a`.`tickets`,
        `counters`.`id` AS `counter_id`
        FROM `counters`
        LEFT JOIN (
            SELECT MAX(`tickets`.`updated_at`) as `updated_at`,
            GROUP_CONCAT(CONCAT(`counters`.`name`, LPAD(`number`, 3, 0))) AS `tickets`,
            `counter_id`
            FROM `tickets`
            LEFT JOIN `counters` ON `counters`.`id` = `tickets`.`counter_id`
            WHERE `status` = 'processing'
            GROUP BY `counter_id`
        ) `a` ON `a`.`counter_id` = `counters`.`id`;
        SQL;

        return response()->json(
            collect(DB::select($sql))
                ->map(function ($counter) {
                    return [
                        'updated_at' => $counter->updated_at ? Carbon::createFromTimeString($counter->updated_at)->timestamp : null,
                        'tickets' => $counter->tickets,
                        'counter_id' => $counter->counter_id,
                    ];
                })
                ->groupBy('counter_id')
                ->map(function ($counter) {
                    return $counter->first();
                })
        );
    }
}
