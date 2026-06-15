<?php

declare(strict_types=1);

const LASTFM_SERVER = "http://ws.audioscrobbler.com/2.0/";
const LASTFM_API_KEY = "YOUR_LASTFM_API_KEY";
const METHOD = "user.getinfo";
const FORMAT = "json";

header('Content-Type: application/json; charset=utf-8');

try {
    $raw = filter_input(INPUT_GET, 'profile');
    $user = is_string($raw) ? trim($raw) : '';

    if ($user === '') {
        throw new RuntimeException("Missing or invalid 'profile' parameter.");
    }

    $query = http_build_query([
        'method'  => METHOD,
        'user'    => $user,
        'api_key' => LASTFM_API_KEY,
        'format'  => FORMAT,
    ]);

    $ch = curl_init(LASTFM_SERVER . '?' . $query);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
    ]);

    $result = curl_exec($ch);

    if (curl_errno($ch) !== 0) {
        throw new RuntimeException('Request error: ' . curl_error($ch));
    }

    curl_close($ch);

    $content = json_decode((string) $result, false, 512, JSON_THROW_ON_ERROR);
    $playcount = is_object($content) ? ($content->user->playcount ?? null) : null;

    if (!is_string($playcount) && !is_int($playcount)) {
        throw new RuntimeException('Unexpected API response structure.');
    }

    echo json_encode([
        'frames' => [
            [
                'icon' => 'i11667',
                'text' => (string) $playcount,
            ],
        ],
    ], JSON_THROW_ON_ERROR);
} catch (Throwable $e) {
    echo json_encode([
        'frames' => [
            [
                'icon' => 'i11667',
                'text' => $e->getMessage(),
            ],
        ],
    ], JSON_THROW_ON_ERROR);
}
