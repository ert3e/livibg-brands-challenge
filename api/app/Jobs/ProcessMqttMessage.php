<?php

namespace App\Jobs;


namespace App\Jobs;

use App\Services\Mqtt\ApiMqttPublisher;
use App\Services\TvShowService;
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
    protected $mqttPublisher;

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
     * @return void
     * @throws DataTransferException
     */
    public function handle(TvShowService $tvShowService, ApiMqttPublisher $mqttPublisher): void
    {

        $search = json_decode($this->message, true);

        $searchQuery = $search['query'];
        $correlationId = json_decode($this->message, true)['correlation_id'];

        $results = $tvShowService->searchShows($searchQuery);


        // Publish the response back to the MQTT topic
        $mqttPublisher->publish('response/tvshow', json_encode([
                'correlation_id' => $correlationId,
                'results' => $results
            ])
        );
        Log::info('Processed and published search results for: ' . $searchQuery);
    }
}
