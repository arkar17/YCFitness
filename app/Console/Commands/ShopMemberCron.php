<?php

namespace App\Console\Commands;

use Log;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ShopMemberCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shopmember:cron';

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
        // info(
        //     DB::table('users')
        //         ->join('shop_members','users.shopmember_type_id','shopmembers.id')
        //         ->where('')
        // );
        // info($user=User::whereDate('to_date','<=',Carbon::now())->update(['active_status'=>'0','member_type'=>'Free','membertype_level'=>null,'to_date'=>null,'from_date'=>null]),
        //     $user->roles()->detach());
        info($user=User::where('shopto_date','<=',Carbon::now())
                    ->update(['shop_request'=>'0','shopto_date'=>null,'shopfrom_date'=>null])
        );
    }
}
