<?php

namespace Cent\PhpTest\Classes;

class Channel
{
    private $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function getChannelFromUsername($username)
    {
        try {
            $channelData = $this->service->channels->listChannels('contentDetails', [
                'forUsername' => $username
            ]);

            if (empty($channelData->items[0])) {
                throw new \Exception("Channel with username {$username} not found");
            }

            return $channelData->items[0];
        } catch (\Exception $err) {
            throw $err;
        }
    }
}
