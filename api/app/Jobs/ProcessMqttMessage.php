<?php

namespace App\Jobs;


namespace App\Jobs;

use App\Services\ApiService;
use App\Services\Mqtt\ApiMqttPublisher;
use App\Services\TvMazeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Exceptions\DataTransferException;

class ProcessMqttMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @param ApiService $apiService
     * @return void
     * @throws DataTransferException
     */
    public function handle(ApiService $apiService): void
    {
        $apiService->execute(json_decode($this->message, true));
    }
}
