<?php

namespace App\Console\Commands\Sipout;

use App\Models\Account;
use App\Services\Sipout\ApiClient;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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

        $calls = (new ApiClient($token))->calls([
            'date_from' => '01.03.2022',
            'date_to'   => '02.03.2022',
        ]);

        foreach ($calls as $call) {
//dd($call);
//            try {
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
//            } catch (\Exception $exception) {
//
//                dd($exception->getMessage());
//            }
        }

        return 0;
    }
}
