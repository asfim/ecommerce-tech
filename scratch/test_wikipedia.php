<?php
$title = urlencode("Samsung Galaxy S24");
$url = "https://en.wikipedia.org/w/api.php?action=query&prop=pageimages&format=json&piprop=original&titles={$title}";

$context = stream_context_create([
    'http' => [
        'header' => "User-Agent: ECommerceScraperBot/1.0\r\n",
    ]
]);

$json = file_get_contents($url, false, $context);
$data = json_decode($json, true);

$pages = $data['query']['pages'];
$firstPage = reset($pages);

if (isset($firstPage['original']['source'])) {
    echo "Found Wikipedia Image: " . $firstPage['original']['source'] . "\n";
} else {
    echo "No image found.\n";
}
