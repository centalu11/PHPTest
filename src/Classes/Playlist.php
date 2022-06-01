<?php

namespace Cent\PhpTest\Classes;

class Playlist
{
    private $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function getPlaylistByID($id)
    {
        try {
            $playlistData = $this->service->playlistItems->listPlaylistItems('contentDetails', [
                'playlistId' => $id
            ]);

            if (empty($playlistData->items)) {
                throw new \Exception("Playlist with ID {$id} not found");
            }

            return $playlistData->items;
        } catch (\Exception $err) {
            throw $err;
        }
    }
}
