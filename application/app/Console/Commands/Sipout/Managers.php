<?php

namespace App\Console\Commands\Sipout;

use App\Models\Account;
use App\Services\Sipout\ApiClient;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Managers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sipout:managers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $token = Account::query()
            ->where('name', 'sipout')
            ->first()
            ->token;

        $managers = (new ApiClient($token))->managers();

        foreach ($managers as $manager) {

            try {
                DB::connection('sipout')
                    ->table('sipout_managers')
                    ->insert([
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        "number" => $manager->number,
                        "descr"  => $manager->descr,
                        "sip_login"    => $manager->sip_login,
                        "sip_password" => $manager->sip_password,
                        "aon"   => $manager->aon,
                        "email" => $manager->email,
                        "pickup_groups" => $manager->pickup_groups,
                        "type" => $manager->type,
                    ]);
            } catch (\Exception $exception) {

                Log::alert(__METHOD__.' : '.$exception->getMessage());

                continue;
            }
        }

        return 0;
    }
}
