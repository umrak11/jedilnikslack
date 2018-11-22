<?php
include 'piazza.php';
include 'slorest.php';


/**
 * Send a Message to a Slack Channel.
 * 
 * @param string $message The message to post into a channel.
 * @param string $channel The name of the channel prefixed with #, example #foobar
 * @return boolean
 */
function slack($message, $channel)
{
    $ch = curl_init("https://slack.com/api/chat.postMessage");
    $data = http_build_query([
        "token" => "xoxp-486223147778-486422870453-485487770496-c435081f9db488e9191f5b40d0a20311",
    	"channel" => $channel,
    	"text" => $message,
    	"username" => "Hrana bot",
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
}

/**
 * 
 * Generate TEXT for SLACK
 * 
 */

//Restavracija 123
$ponudba = ":knife_fork_plate: *Dnevni jedilnik Restavracija 123* \n";
for ($i = 0; $i < $ponudbaC; $i++)
{
    $ponudba .= "- ".$menu->dnevna[$i]->title." *".$menu->dnevna[$i]->price."€*\n";   
}
$ponudba .= "\n _Dodatna ponudba:_ \n\n"; 
$ponudba .= "- ".$menu->priporocamo[0]->title." *".$menu->priporocamo[0]->price."€*\n"; 
$ponudba .= "- ".$menu->priporocamo[1]->title." *".$menu->priporocamo[1]->price."€*\n\n"; 


//Piazza
$pC = piazzaCall()['today'];
$ponudbaPC = count(piazzaCall()['today']);
$ponudba .= "\n\n\n :shallow_pan_of_food:*Dnevni jedilnik Piazza.si* \n";
for ($i = 0; $i < $ponudbaPC; $i++)
{
    $ponudba .= "- ".$pC[$i]['name']." *".$pC[$i]['price']."€*\n";   
}

slack($ponudba, '#slack-testing');