<?php

namespace Cent\PhpTest\Classes;

class Playlist
{
    private $service;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function getPlaylistByID($id, $page)
    {
        try {
            $playlistData = $this->service->playlistItems->listPlaylistItems('contentDetails', [
                'playlistId' => $id,
                'maxResults' => 50,
                'pageToken' => $page
            ]);

            if (empty($playlistData->items)) {
                throw new \Exception("Playlist with ID {$id} not found");
            }
            return [
                'prevPage' => $playlistData->prevPageToken,
                'nextPage' => $playlistData->nextPageToken,
                'items' => $playlistData->items
            ];
        } catch (\Exception $err) {
            throw $err;
        }
    }
}
