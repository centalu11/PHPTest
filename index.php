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
    $page = '';
    if ($interface === 'cli') {
        if (!isset($argv[1]) ||  $argv[1] . trim('') === '') {
            throw new Exception('url is required.');
        }

        $page = $argv[2] ?? '';
        $url = $argv[1];
    } else {
        if (!isset($_GET['url']) || $_GET['url'] . trim('') === '') {
            throw new Exception('url is required.');
        }

        $page = $_GET['page'] ?? '';
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
    $channelData = $channel->getChannelFromUsername($username); // Get Channel By Username
    // If channel cannot be found by username, get channel ID from the page source instead
    if (empty($channelData)) {
        $pageSource = getPageSourceFromUrl($url);
        $channelID = getChannelIDFromPageSource($pageSource);

        $channelData = $channel->getChannelFromID($channelID);
    }

    // Get Playlist
    $uploadPlaylistID = $channelData->contentDetails->relatedPlaylists->uploads;
    $playlist = new Cent\PhpTest\Classes\Playlist($service);
    $playlistData = $playlist->getPlaylistByID($uploadPlaylistID, $page);

    // Get Videos
    $videos = [];

    foreach ($playlistData['items'] as $playlistItem) {
        $videoID = $playlistItem->contentDetails->videoId;
        $video = new Cent\PhpTest\Classes\Video($service);
        $videoData = $video->getVideoByID($videoID);

        // Save Video to Database
        $videoDb = new Cent\PhpTest\Queries\Video($dbConnection);
        $videoDb->save($channelData->id, $videoData);
        array_push($videos, $videoData);
    }

    response([
        'prevPage' => $playlistData['prevPage'],
        'nextPage' => $playlistData['nextPage'],
        'videos' => $videos
    ]);
} catch (Exception $err) {
    echo $err->getMessage();
}

function getUsernameFromUrl($url)
{
    $urlArray = explode('/', $url);
    $count = count($urlArray);

    return $urlArray[$count - 1];
}

function getPageSourceFromUrl($url)
{
    try {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $pageSource = curl_exec($curl);

        if (curl_error($curl))
            throw curl_error($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($status == 404) {
            throw new Exception("Channel with '{$url}' was not found");
        } else if ($status != 200) {
            throw new Exception("Something went wrong when trying to get the channel's page source.");
        }
        curl_close($curl);

        return $pageSource;
    } catch (\Exception $err) {
        throw $err;
    }
}

function getChannelIDFromPageSource($pageSource)
{
    // For newer channels, externalId is used
    $searchString = '"externalId":"';
    $stringStart = strpos($pageSource, $searchString);

    // For older channels, channel-external-id is used
    if ($stringStart === false) {
        $searchString = '"channel-external-id":"';
        $stringStart = strpos($pageSource, $searchString);
    }

    $stringEnd = strpos($pageSource, '","', $stringStart);
    $searchStringCount = strlen($searchString);

    $string = substr($pageSource, $stringStart + $searchStringCount, ($stringEnd - $stringStart - $searchStringCount));

    return $string;
}

function response($value)
{
    print('<pre>' . print_r($value, true) . '</pre>');
}
