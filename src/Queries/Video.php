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

    public function save($channelUsername, $video)
    {
        try {
            $videoTags = implode(', ', $video['tags']);
            $tableColumns = 'channelUsername, title, description, publicationDate, viewCount, likeCount, favoriteCount, commentCount, tags';
            $tableValues = '"' . $channelUsername . '", "' .
            $video['title'] . '" , "' .
            $video['description'] . '" , "' .
            $video['publicationDate'] . '" , ' .
            $video['viewCount'] . ', ' .
            $video['likeCount'] . ', ' .
            $video['favoriteCount'] . ', ' .
            $video['commentCount'] . ', "' .
            $videoTags . '"';

            $sql = "INSERT INTO {$this->tableName} ({$tableColumns}) VALUES ({$tableValues})";
            $this->connection->query($sql);
            if ($this->connection->error) {
                throw new Exception($this->connection->error);
            }
        } catch (\Exception $err) {
            throw $err;
        }
    }
}
