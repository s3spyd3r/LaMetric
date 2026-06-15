<?php

declare(strict_types=1);

const URL_BASE = 'https://myanimelist.net/profile/';

$raw = $_GET['profile'] ?? null;
$user = is_string($raw) ? trim($raw) : '';

if ($user === '') {
    echo json_encode([
        'frames' => [
            ['icon' => 'i13457', 'text' => 'No profile provided'],
        ],
    ], JSON_THROW_ON_ERROR);
    exit;
}

$html = @file_get_contents(URL_BASE . urlencode($user));

if ($html === false) {
    echo json_encode([
        'frames' => [
            ['icon' => 'i13457', 'text' => 'Failed to fetch profile'],
        ],
    ], JSON_THROW_ON_ERROR);
    exit;
}

$total = preg_match('/Episodes<\/span>\s*<span[^>]*>([^<]+)<\/span>/i', $html, $m) === 1
    ? str_replace(',', '', trim($m[1]))
    : 'N/A';

echo json_encode([
    'frames' => [
        ['icon' => 'i13457', 'text' => $total],
    ],
], JSON_THROW_ON_ERROR);
