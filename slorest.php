<?php


/**
 * 
 * Get daily menu from restavracija123.si
 * 
 * 
 */

$currentDate = date("Y-m-d");
$url = 'http://www.restavracija123.si/api/getDailyMenu/4375/'.$currentDate;
$json = file_get_contents($url);
$menu = json_decode($json);

$ponudbaC = count($menu->dnevna);
