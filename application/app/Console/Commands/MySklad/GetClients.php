<?php

namespace App\Console\Commands\MySklad;

use App\Models\Account;
use App\Models\MySklad\Skus;
use App\Services\MySklad\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mс:clients';

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
        Log::info(__METHOD__. ' start ');

        $apiClient = new Client(Account::whereName('mc')->first()->token);

        $clients = $apiClient->service
            ->contragents()
            ->all(
                500,
                Cache::get('mc_clients_offset') ?? 0,
            );
        if (count($clients['array']) > 0) {

            foreach ($clients['array'] as $client) {

                try {
                    DB::connection('mc')
                        ->table((new \App\Models\MySklad\Client())->getTable())
                        ->insert([
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'uuid'  => $client['id'],
                            'group' => isset($client['tags']) ? json_encode($client['tags']) : null,
                            'name'  => $client['name'],
                            'full_name' => $client['legalTitle'] ?? null,
                            'address'   => $client['legalAddress'] ?? null,
                            'inn'   => $client['inn'] ?? null,
                            'kpp'   => $client['kpp'] ?? null,
                            'phone' => $client['phone'] ?? null,
                            'email' => $client['email'] ?? null,
                            'archived' => $client['archived'],
                        ]);

                    $model = DB::connection('mc')
                        ->table((new \App\Models\MySklad\Client())->getTable())
                        ->latest('id');

                    $type = match ($client['companyType']) {
                        'legal'        => 'Юридическое лицо',
                        'entrepreneur' => 'Индивидуальный предприниматель',
                        'individual'   => 'Физическое лицо',
                    };

                    $state = !empty($client['state']) ? $apiClient->service
                        ->contragents()
                        ->state($client['state']['meta']['href'])['name'] : null;

                    $model->update([
                        'type'   => $type,
                        'status' => $state,
                    ]);

                } catch (\Throwable $exception) {

                    dd($exception->getMessage().' '.$exception->getLine());
                }
            }
        }
        Cache::put('mc_clients_offset', $clients['offset']);

        Log::info(__METHOD__. ' stop ');

        return 0;
    }
}
