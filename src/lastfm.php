<?php

// Configuration
const LASTFM_SERVER = "http://ws.audioscrobbler.com/2.0/";
const LASTFM_API_KEY = "YOUR_LASTFM_API_KEY";
const METHOD = "user.getinfo";
const FORMAT = "json";

// Set response type
header('Content-Type: application/json; charset=utf-8');

try {
    // Sanitize and validate input
    $user = filter_input(INPUT_GET, 'profile', FILTER_SANITIZE_STRING);

    if (!$user) {
        throw new Exception("Missing or invalid 'profile' parameter.");
    }

    // Build query safely
    $query = http_build_query([
        'method'  => METHOD,
        'user'    => $user,
        'api_key' => LASTFM_API_KEY,
        'format'  => FORMAT
    ]);

    $url = LASTFM_SERVER . '?' . $query;

    // Use cURL for better error handling
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
    ]);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception('Request error: ' . curl_error($ch));
    }

    curl_close($ch);

    // Decode JSON
    $content = json_decode($result, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON response from Last.fm.');
    }

    // Check response structure
    if (!isset($content['user']['playcount'])) {
        throw new Exception('Unexpected API response structure.');
    }

    // Success response
    echo json_encode([
        "frames" => [
            [
                "icon" => "i11667",
                "text" => (string)$content['user']['playcount']
            ]
        ]
    ]);

} catch (Exception $ex) {
    // Return error as a frame for consistency
    echo json_encode([
        "frames" => [
            [
                "icon" => "i11667",
                "text" => $ex->getMessage()
            ]
        ]
    ]);
}