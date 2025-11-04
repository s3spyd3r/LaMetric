<?php

const URL_BASE = 'https://myanimelist.net/profile/';

$user = $_GET['profile'] ?? null;
$user = filter_var($user, FILTER_SANITIZE_STRING);

if (empty($user)) {
    echo json_encode([
        'frames' => [
            ['icon' => 'i13457', 'text' => 'No profile provided']
        ]
    ]);
    exit;
}

$url = URL_BASE . urlencode($user);

$html = @file_get_contents($url);
if ($html === false) {
    echo json_encode([
        'frames' => [
            ['icon' => 'i13457', 'text' => 'Failed to fetch profile']
        ]
    ]);
    exit;
}

if (preg_match('/Episodes<\/span>\s*<span[^>]*>([^<]+)<\/span>/i', $html, $matches)) {
    $episodes = trim($matches[1]);
    $total = str_replace(',', '', $episodes);
} else {
    $total = 'N/A';
}

echo json_encode([
    'frames' => [
        ['icon' => 'i13457', 'text' => $total]
    ]
]);