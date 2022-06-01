<?php

namespace Cent\PhpTest\Classes;

class GoogleService
{
    private $client;

    public function __construct()
    {
        $this->client =  new \Google\Client();
        $this->client->setApplicationName("PHP-Test");
        $this->client->setDeveloperKey($_ENV['API_KEY'] ?? 'AIzaSyA-7ybmkjqNJFeJGGjKigFO7wYKrskMcBU');
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getYoutubeService() 
    {
        return new \Google\Service\YouTube($this->client);
    }
}
