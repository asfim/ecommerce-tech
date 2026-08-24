<?php
$query = urlencode("Samsung Galaxy S24 Ultra product photo");
$url = "https://www.bing.com/images/search?q={$query}";

$context = stream_context_create([
    'http' => [
        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36\r\n",
    ]
]);

$html = file_get_contents($url, false, $context);
if (preg_match('/murl&quot;:&quot;(.*?)&quot;/', $html, $matches)) {
    echo "Found Bing Image: " . $matches[1] . "\n";
} else {
    echo "No image found.\n";
}
