<?php

/**
 * Get piazza daily menu, using CURL 
 * Call function piazzaCall to get array of today's and tomorrow's menu
 * 
 */

/** main function, returns array of today's and tomorrow's meals */
function piazzaCall()
{
    $response = curlCall();
    $menu = [
        'today' => [],
        'tomorrow' => []
    ];

    // today's menu
    $start = '<h3>Današnja dnevna ponudba</h3>';
    $end = '<div id="front-daily-lunch-wrapper-right">';
    $menu['today'] = parseMenu($response, $start, $end);

    // tomorrow's menu
    $start = '<h3>Jutrišnja dnevna ponudba</h3>';
    $end = '<aside class="grid-3 region region-sidebar-second" id="region-sidebar-second">';
    $menu['tomorrow'] = parseMenu($response, $start, $end);

    return $menu;

}

// get html with curl call
function curlCall() : string
{
    $url = 'http://www.piazza.si/sl';

    if (!function_exists('curl_init')) {
        dd('cURL is not installed on server.');
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}


/** return array of strings between <strong> tags and  all numbers delimited by comma */
function parseMenu($response, $start, $end) : array
{
    // shorten the data
    $start_point = strpos($response, $start);
    $end_point = strpos($response, $end, $start_point);
    $length = $end_point - $start_point;
    $html = substr($response, $start_point, $length);

    // get menu
    preg_match_all('!<strong>(.*?)<\/strong>!', $html, $match);
    $names = $match[1];

    // get prices
    preg_match_all('!(\d+,\d+)!', $html, $match);
    $prices = $match[1];
    $menu = [];

    foreach ($names as $key => $name) {
        $food = [];
        $food['name'] = $name;
        if (array_key_exists($key, $prices)) {
            $food['price'] = $prices[$key];
        } else {
            $food['price'] = null;
        }
        $menu[] = $food;
    }
    return $menu;
}
