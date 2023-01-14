<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Spatie\Permission\Contracts\Role;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        info($user=User::whereDate('to_date','<=',Carbon::now())->update(['active_status'=>'0','member_type'=>'Free','membertype_level'=>null,'to_date'=>null,'from_date'=>null]),
            $user->roles()->detach());
    }
}
