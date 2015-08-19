<?php
/*
 * Description: extracts titles from a list of urls
 * It follows redirects and works with Japanese (Shift-JIS or UTF-8 encoded)
 * CC: Hector Garcia http://www.ageekinjapan.com
 */

mb_language('Japanese');

$urls = explode("\n", file_get_contents('wikia.txt'));

function file_get_contents_curl($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

foreach($urls as $url) {
    $html = file_get_contents_curl(trim($url));

    preg_match('/<title>(.+)<\/title>/',$html,$matches);
    $title = $matches[1];

    // Cthulhu parsing, #likeaboss
    preg_match('/<div class="tally"><em>(.*)<\/em>/',$html,$matches);
    $number_of_pages = $matches[1];

    echo $url . ';' . $title . ';' . $number_of_pages . "\n";
}