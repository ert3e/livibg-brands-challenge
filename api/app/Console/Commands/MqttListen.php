<?php

namespace App\Console\Commands;

use App\Services\Mqtt\ApiMqttListener;
use App\Services\Mqtt\ApiMqttListenerInterface;
use Illuminate\Console\Command;

class MqttListen extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Listen for MQTT messages and dispatch jobs';
    private $mqttListener;

    public function __construct(ApiMqttListenerInterface $mqttListener)
    {
        parent::__construct();
        $this->mqttListener = $mqttListener;
    }

    /**
     */
    public function handle(ApiMqttListener $listener)
    {
        $this->mqttListener->listen();
    }
}
