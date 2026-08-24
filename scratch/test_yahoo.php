<?php
$query = urlencode("Samsung Galaxy S24 Ultra product photo");
$url = "https://images.search.yahoo.com/search/images?p={$query}";

$context = stream_context_create([
    'http' => [
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n",
    ]
]);

$html = file_get_contents($url, false, $context);
if (preg_match('/<img[^>]+src="([^"]+)"/', $html, $matches)) {
    echo "Found first img src: " . $matches[1] . "\n";
}
if (preg_match('/data-src="([^"]+)"/', $html, $matches)) {
    echo "Found first data-src: " . $matches[1] . "\n";
}
// Yahoo images often use src for placeholders and actual images might be in JSON or data-src. Let's dump all img tags.
preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $imgMatches);
print_r(array_slice($imgMatches[1], 0, 5));
