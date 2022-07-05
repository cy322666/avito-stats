<?php

namespace App\Console\Commands\Sipout;

use App\Models\Account;
use App\Services\Sipout\ApiClient;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Calls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sipout:calls';

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

        $dateFrom = Cache::get('sipout_date_from') ?? Carbon::create('2022', '01', '01')->format('d.m.Y');
        $dateTo   = Cache::get('sipout_date_to') ?? Carbon::create('2022', '01', '07')->format('d.m.Y');

        Log::info(__METHOD__.' > date_from : '.$dateFrom.' date_to : '.$dateTo);

        $calls = (new ApiClient($token))->calls([
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
        ]);

        foreach ($calls as $call) {

            try {
                DB::connection('sipout')
                    ->table('sipout_calls')
                    ->insert([
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        "date"   => $call->date,
                        "cnam"   => $call->cnam,
                        "caller" => $call->caller,
                        "called" => $call->called,
                        "duration"  => $call->duration,
                        "direction" => $call->direction,
                        "type"      => $call->type,
                        "answer"    => $call->answer,
                        "note_cnt"  => $call->note_cnt,
                        "callid" => $call->callid,
                        "ts"     => $call->ts,
                    ]);
            } catch (\Exception $exception) {

                Log::alert(__METHOD__.' : '.$exception->getMessage());

                continue;
            }
        }

        $latestDate = DB::connection('sipout')
            ->table('sipout_calls')
            ->latest('id')
            ->first();

        if ($latestDate) {
            $latestDate = explode(' ', $latestDate->date)[0];
        } else {
            $latestDate = Cache::get('sipout_date_to') ?? $dateTo;
        }

        Cache::put('sipout_date_from', $latestDate);
        Cache::put('sipout_date_to', Carbon::parse($latestDate)->addDays(6)->format('d.m.Y'));

        Log::info(__METHOD__.' next > date_from : '.Cache::get('sipout_date_from').' date_to : '.Cache::get('sipout_date_to'));
        Log::info(__METHOD__.' > end');

        return 0;
    }
}
