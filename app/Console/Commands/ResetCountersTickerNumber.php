<?php

namespace App\Console\Commands;

use App\Models\Counter;
use Illuminate\Console\Command;

class ResetCountersTickerNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-counters-ticker-number';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Counter::query()->update(['next_ticket_number' => 1]);
    }
}
