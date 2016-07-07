<?php
ini_set('default_charset', 'utf-8');
header('Content-type: text/html; charset=utf-8');
ini_set('display_errors', 'On');
error_reporting(E_ALL);

$API_KEY = "b755a4e6-3156-4af9-8ca3-288485dbe4b7";
$version = getVersion();

//player class
class player
{
    var $name;
    var $ID;
    var $champID;
    var $champ;
    var $team;

    //images
    var $icon;
    var $loading;

    //array of length 4 containing arrays of base cooldown values
    var $cooldowns;

    //array of spell image links
    var $spells;

    function __construct($n, $i, $c, $t){
        $this->name = $n;
        $this->ID = $i;
        $this->champID = $c;
        $this->team = $t;
        $this->cooldowns = $this->getCooldown($c);
    }
    //gets the cooldown value array for a given champ ID and returns it
    function getCooldown($champ){
        $cd = array();
        $query = "https://na.api.pvp.net/api/lol/static-data/na/v1.2/champion/{$champ}?api_key={$GLOBALS["API_KEY"]}&champData=spells,image";
        $data = file_get_contents($query);
        $json = json_decode($data, true);
        $spells = $json['spells'];

        //sets the champ name of this player object
        $this->champ = $json['name'];

        $this->getIcons($json);

        for($i=0;$i<4;$i++){
            $cd[] = $spells[$i]['cooldown'];
        }
        return $cd;
    }

    //gets the link for the icons for the champ and abilities
    function getIcons($json){
        $iconName = $json["image"]["full"];
        $this->icon = "http://ddragon.leagueoflegends.com/cdn/{$GLOBALS["version"]}/img/champion/{$iconName}";

        $loadingName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $iconName);
        $this->loading = "http://ddragon.leagueoflegends.com/cdn/img/champion/loading/{$loadingName}_0.jpg";


        //abilities:
        for($i = 0; $i<count($json['spells']); $i++){
            //gets the links from the json
            $this->spells[$i] = "http://ddragon.leagueoflegends.com/cdn/{$GLOBALS["version"]}/img/spell/" . $json['spells'][$i]['image']['full'];
        }   
    }
}

//gets the latest version for this region
function getVersion(){
    $query = "https://global.api.pvp.net/api/lol/static-data/na/v1.2/versions?api_key=".$GLOBALS["API_KEY"];
    $data = file_get_contents($query);
    $json = json_decode($data, true);
    //print($json[0]);
    return $json[0];
}


//returns player ID from name
function getID($name){
    $query = "https://na.api.pvp.net/api/lol/na/v1.4/summoner/by-name/{$name}?api_key=" . $GLOBALS["API_KEY"];
    $url = str_replace(' ', '%20', $query);
    $data = file_get_contents($url);
    $json = json_decode($data, true);
    //name of player object (part of json object)
    $n = strtolower(str_replace(' ', '', $name));
    return $json[$n]['id'];
}

//returns game data from player ID, packaged into an array of player objects
function getGame($id){
    $query = "https://na.api.pvp.net/observer-mode/rest/consumer/getSpectatorGameInfo/NA1/{$id}?api_key=" . $GLOBALS["API_KEY"];
    $response = get_http_response_code($query);

    if($response != "200"){
        //on error, e.g. 404 or 403
        return $response;
    }else{
        $data = file_get_contents($query);
        $json = json_decode($data, true);

        $players = array();

        foreach($json['participants'] as $player){
            $p = new player($player['summonerName'],$player['summonerId'],$player['championId'],$player['teamId']);
            $players[] = $p;
        }
        return $players;
    }
}

//returns the response code
function get_http_response_code($url) {
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
}

//returns another collapsable player div
function add($p, $i){

    return '<td class="champ-column">
                                <div class="abilities">
                                    <div class="ability_row">
                                        <img src="'.$p->spells[0].'" class="ability_pic"> 
                                        <div class="cd-box"><span class="cooldowns">'.cd_join($p->cooldowns[0]).'</span></div>
                                    </div>
                                    <div class="ability_row">
                                        <img src="'.$p->spells[1].'" class="ability_pic"> 
                                        <div class="cd-box"><span class="cooldowns">'.cd_join($p->cooldowns[1]).'</span></div>
                                    </div>
                                    <div class="ability_row">
                                        <img src="'.$p->spells[2].'" class="ability_pic"> 
                                        <div class="cd-box"><span class="cooldowns">'.cd_join($p->cooldowns[2]).'</span></div>
                                    </div>
                                    <div class="ability_row">
                                        <img src="'.$p->spells[3].'" class="ability_pic"> 
                                        <div class="cd-box"><span class="cooldowns">'.cd_join($p->cooldowns[3]).'</span></div>
                                    </div>
                                </div>
                                <img src="'.$p->loading.'" class="champ"></td>';
}

//takes in an array of cd lengths at each level, then formats it into a string
function format($cd){
    $str = "";
    for($i=0;$i<count($cd);$i++){
        $str .= $cd[$i];
        if($i<count($cd)-1){
            $str.= "/";
        }
    }
    return $str;
}

//takes an array of cooldowns and turns it into a formatted string (e.g. {1,2,3} -> 1/2/3)
function cd_join($arr){
    $str = "";
    for($i=0;$i<count($arr)-1;$i++){
        $str .= $arr[$i] . "/";
    }
    $str .= $arr[count($arr)-1];
    return $str;
}
?>