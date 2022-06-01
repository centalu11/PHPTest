<?php

if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    throw new Exception(sprintf('Please run "composer install" in "%s"', __DIR__));
}
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $interface = php_sapi_name();
    $url = '';
    if ($interface === 'cli') {
        if (!isset($argv[1]) ||  $argv[1] . trim('') === '') {
            throw new Exception('url is required.');
        }
        $url = $argv[1];
    } else {
        if (!isset($_GET['url']) || $_GET['url'] . trim('') === '') {
            throw new Exception('url is required.');
        }
        $url = $_GET['url'];
    }

    $username = getUsernameFromUrl($url);

    // MYSQL Connection
    $dbConnection = new Cent\PhpTest\DatabaseConnection();
    $dbConnection = $dbConnection->connect()->getConnection();

    // Instantiate youtube service
    $googleService =  new Cent\PhpTest\Classes\GoogleService();
    $service = $googleService->getYoutubeService();

    // Get Channel
    $channel = new Cent\PhpTest\Classes\Channel($service);
    $channelData = $channel->getChannelFromUsername($username);

    // Get Playlist
    $uploadPlaylistID = $channelData->contentDetails->relatedPlaylists->uploads;
    $playlist = new Cent\PhpTest\Classes\Playlist($service);
    $playlistData = $playlist->getPlaylistByID($uploadPlaylistID);

    // Get Videos
    $videos = [];
    foreach ($playlistData as $playlistItem) {
        $videoID = $playlistItem->contentDetails->videoId;
        $video = new Cent\PhpTest\Classes\Video($service);
        $videoData = $video->getVideoByID($videoID);

        // Save Video to Database
        $videoDb = new Cent\PhpTest\Queries\Video($dbConnection);
        $videoDb->save($username, $videoData);
        array_push($videos, $videoData);
    }

    print('<pre>' . print_r($videos, true) . '</pre>');
} catch (Exception $err) {
    echo $err->getMessage();
}

function getUsernameFromUrl($url)
{
    $urlArray = explode('/', $url);
    $count = count($urlArray);

    return $urlArray[$count - 1];
}

// function getArguments()
// {
//     var_dump($argc); //number of arguments passed 
//     echo $argv[1];
// }