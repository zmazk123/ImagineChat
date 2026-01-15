<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use PDO;

class ChatController extends Controller
{
    private function getPayload($pdo)
    {
        return $pdo->pgsqlGetNotify(PDO::FETCH_ASSOC);
    }
    
    public function stream()
    {
        if (! auth()->check()) {
            abort(401);
        }
        
        $pdo = DB::connection()->getPdo();
        $pdo->exec('LISTEN new_message');
        $ticks = 0;
        ob_implicit_flush();
        $response = new StreamedResponse(function () use ($pdo, $ticks) {

            while (true) {
                if (connection_aborted()) break;

                $notification = $this->getPayload($pdo);
                // Loop through notification until queue is empty
                while ($notification) {
                    error_log('New message notification: ' . $notification['payload']);
                    $payload = json_decode($notification['payload']);
                    $payload->type = "message";
                    if (is_object($payload) && isset($payload->user_id)) {
                        $payload->name = DB::table('users')->where('id', $payload->user_id)->value('name');
                    }
                    $payload = json_encode($payload);
                    
                    echo "data: $payload\n\n";

                    $notification = $this->getPayload($pdo);
                }

                // We should send heartbeat less frequently but ok for now.
                $heartbeat = json_encode(['type' => 'heartbeat']);

                echo "data: $heartbeat\n\n";
                
                // Delay for 1 second
                sleep(1);
                
                $ticks++;
                error_log("Tick: " . $ticks);

                // kill after 6 hours
                if ($ticks > 21600) {
                    exit();
                }
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }
}
