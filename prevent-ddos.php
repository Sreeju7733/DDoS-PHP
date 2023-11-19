<?php
// Path to the file where rate limit data will be stored
$rateLimitFile = 'rate_limit_data.txt';

$requestsLimit = 50; // Set a limit for requests
$timeFrame = 60; // Set the time frame in seconds (e.g., 60 seconds)

// Check if the rate limit file exists, and create it if it doesn't
if (!file_exists($rateLimitFile)) {
    // Create the file and initialize data
    $initialData = array('requests' => 0, 'lastRequestTime' => time());
    file_put_contents($rateLimitFile, json_encode($initialData));
}

// Load rate limit data from the file
$rateLimitData = json_decode(file_get_contents($rateLimitFile), true);

// Check and update rate limit data
$timePassed = time() - $rateLimitData['lastRequestTime'];
if ($timePassed >= $timeFrame) {
    // Reset request count and update last request time if time frame has elapsed
    $rateLimitData['requests'] = 0;
    $rateLimitData['lastRequestTime'] = time();
}

// Increment request count and check if limit is exceeded
$rateLimitData['requests']++;
if ($rateLimitData['requests'] > $requestsLimit) {
    http_response_code(429); // HTTP 429 - Too Many Requests
    exit("Too many requests. Please try again later.");
}

// Save updated rate limit data back to the file
file_put_contents($rateLimitFile, json_encode($rateLimitData));
?>
