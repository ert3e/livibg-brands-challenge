<?php

namespace App\Jobs;


namespace App\Jobs;

use App\Services\MqttPublisher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
     */
    public function handle()
    {
        $this->mqttPublisher = new MqttPublisher();
        $search = json_decode($this->message, true);

        $searchQuery = $search['query'];
        $correlationId = json_decode($this->message, true)['correlation_id'];

        // Make the API request to search for the TV show
        try {
            $response = Http::get("https://api.tvmaze.com/search/shows?q=" . urlencode($searchQuery));
        } catch (\Exception $e) {
            Log::info('Failed to get search results for: ' . $searchQuery);
            Log::info('Failed to get search results for: ' . $e->getMessage());
        }
        $results = $response->json();
        // Filter results
        $results = collect($results)
            ->pluck('show')
            ->filter(function ($show) use ($searchQuery) {
                return strcasecmp($show['name'], $searchQuery) === 0;
            })
            ->values();

        // Publish the response back to the MQTT topic
        $this->mqttPublisher->publish('response/tvshow',
            json_encode([
                'correlation_id' => $correlationId,
                'results' => $results
            ])
        );
        Log::info('Processed and published search results for: ' . $searchQuery);
    }
}
