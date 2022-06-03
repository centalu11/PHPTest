<?php

namespace Cent\PhpTest\Queries;

use Exception;

class Video
{
    private $connection;
    private $tableName;

    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->tableName = 'videos';
    }

    public function save($channelID, $video)
    {
        try {
            $videoTags = implode(', ', $video['tags']);
            $statement = $this->connection->prepare("INSERT INTO videos
                (`videoId`, `channelId`, `title`, `description`, `publicationDate`, `viewCount`, `likeCount`, `favoriteCount`, `commentCount`, `tags`) VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $statement->bind_param(
                'sssssiiiis',
                $video['id'],
                $channelID,
                $video['title'],
                $video['description'],
                $video['publicationDate'],
                $video['viewCount'],
                $video['likeCount'],
                $video['favoriteCount'],
                $video['commentCount'],
                $videoTags
            );
            $statement->execute();
            if ($this->connection->error) {
                throw new Exception($this->connection->error);
            }
        } catch (\Exception $err) {
            if (strpos($err->getMessage(), 'Duplicate entry') === false )
                throw $err;
        }
    }
}
