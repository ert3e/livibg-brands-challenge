<?php

namespace App\Console\Commands;

use App\Services\Mqtt\ApiMqttListener;
use Illuminate\Console\Command;
use PhpMqtt\Client\Exceptions\DataTransferException;
use PhpMqtt\Client\Exceptions\InvalidMessageException;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\Exceptions\ProtocolViolationException;
use PhpMqtt\Client\Exceptions\RepositoryException;

class MqttListen extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Listen for MQTT messages and dispatch jobs';

    public ApiMqttListener $listener;
    public function __construct(ApiMqttListener $listener)
    {
        $this->listener = $listener;
        parent::__construct();
    }

    /**
     * @throws ProtocolViolationException
     * @throws InvalidMessageException
     * @throws MqttClientException
     * @throws RepositoryException
     * @throws DataTransferException
     */
    public function handle()
    {
        $this->listener->listen();
    }
}
