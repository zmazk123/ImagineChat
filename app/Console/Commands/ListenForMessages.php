<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;

class ListenForMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen for new chat messages from Postgres';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Listening for new messages...');

        /** @var \PDO $pdo */
        $pdo = DB::connection()->getPdo();
        $pdo->exec('LISTEN new_message');


        // is while(true) really the best solution here????? perhapsh this deserves a cleanup
        while (true) {
            $notification = $pdo->pgsqlGetNotify(PDO::FETCH_ASSOC, 30000); // 30 second timeout

            if ($notification) {
                $this->info('Received notification: ' . $notification['payload']);
                error_log('New message notification: ' . $notification['payload']);
            }
        }
    }
}