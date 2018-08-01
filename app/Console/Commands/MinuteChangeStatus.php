<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class MinuteChangeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Minute:changeStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status trip every minute';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('way_points')->where('order_num', 0)->whereRaw('leave_time < now()')->join('trips', 'way_points.trip_id', '=', 'trips.id')->update(['trips.status' => 'in process']);
        DB::table('way_points')->where('order_num', 0)->whereRaw('arrival_time < now()')->join('trips', 'way_points.trip_id', '=', 'trips.id')->update(['trips.status' => 'closed']);
        $this->info('Status Update has been successfully');
    }
}
