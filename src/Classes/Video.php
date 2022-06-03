<?php

namespace Cent\PhpTest\Classes;

class Video
{
    private $service;
    private $id;
    private $title;
    private $description;
    private $publicationDate;
    private $viewCount;
    private $likeCount;
    private $favoriteCount;
    private $commentCount;
    private $tags;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function getVideoByID($id)
    {
        try {
            $videoData = $this->service->videos->listVideos('snippet, statistics', [
                'id' => $id
            ]);

            if (empty($videoData->items[0])) {
                throw new \Exception("Video with ID {$id} not found");
            }

            $videoItem = $videoData->items[0];
            $this->id = $videoItem->id;
            $this->title = $videoItem->snippet->title ?? '';
            $this->description = $videoItem->snippet->description ?? '';
            $this->publicationDate = $videoItem->snippet->publishedAt ?? '';
            $this->viewCount = $videoItem->statistics->viewCount ?? 0;
            $this->likeCount = $videoItem->statistics->likeCount ?? 0;
            $this->favoriteCount = $videoItem->statistics->favoriteCount ?? 0;
            $this->commentCount = $videoItem->statistics->commentCount ?? 0;
            $this->tags = $videoItem->snippet->tags ?? [];

            return $this->getData();
        } catch (\Exception $err) {
            throw $err;
        }
    }

    public function getData()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'publicationDate' => $this->publicationDate,
            'viewCount' => $this->viewCount,
            'likeCount' => $this->likeCount,
            'favoriteCount' => $this->favoriteCount,
            'commentCount' => $this->commentCount,
            'tags' => $this->tags
        ];
    }
}
