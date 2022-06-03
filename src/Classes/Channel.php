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

            return !empty($channelData->items[0]) ? $channelData->items[0] : [];
        } catch (\Exception $err) {
            throw $err;
        }
    }

    public function getChannelFromID($id)
    {
        try {
            $channelData = $this->service->channels->listChannels('contentDetails', [
                'id' => $id
            ]);

            return !empty($channelData->items[0]) ? $channelData->items[0] : [];
        } catch (\Exception $err) {
            throw $err;
        }
    }
}
