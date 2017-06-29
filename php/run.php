<?php
// Here we want to perform our steps to extract the riot api info into a tiny json file
// we create on the server. If we already have one, we want to override it.

// 1. initialize curl
$curl = curl_init();

// key (hidden): RGAPI-19a18026-9713-4e4b-968d-159abeed82e1

// 2. initialize file
$fp = fopen('data.json', 'w');

// 3. define data for requests (summoner-ids) in the most beautiful way - by hand!
$members = array();
$member = new \StdClass;
$member->l4uid = 0;
$member->l4urank = 0;
$member->summonerid = 27730651;
$member->accountid = 31647183;
$member->task = "Papstsachen";
$member->l4uname = "Der Papst";
$members[$member->l4uid] = $member;
$member = new \StdClass;
$member->l4uid = 1;
$member->l4urank = 1;
$member->summonerid = 32737299;
$member->accountid = 36284446;
$member->task = "Militär";
$member->l4uname = "ELGDEM";
$members[$member->l4uid] = $member;
$member = new \StdClass;
$member->l4uid = 2;
$member->l4urank = 2;
$member->summonerid = 22725027;
$member->accountid = 26683508;
$member->task = "Nennen wir es Unterhaltungsbranche";
$member->l4uname = "Der König";
$members[$member->l4uid] = $member;
$member = new \StdClass;
$member->l4uid = 3;
$member->l4urank = 6;
$member->summonerid = 35712937;
$member->accountid = 39004654;
$member->task = "planlos";
$member->l4uname = "Der Planloser";
$members[$member->l4uid] = $member;
$member = new \StdClass;
$member->l4uid = 4;
$member->l4urank = 3;
$member->summonerid = 221626;
$member->accountid = 227355;
$member->task = "Minister für Zettelwirtschaft";
$member->l4uname = "Mr. Zylinder";
$members[$member->l4uid] = $member;
$member = new \StdClass;
$member->l4uid = 5;
$member->l4urank = 5;
$member->summonerid = 37948471;
$member->accountid = 40545318;
$member->task = "Geheimdienst";
$member->l4uname = "Der Fremde";
$members[$member->l4uid] = $member;
$member = new \StdClass;
$member->l4uid = 6;
$member->l4urank = 4;
$member->summonerid = 82608651;
$member->accountid = 225883942;
$member->task = "arbeitslos";
$member->l4uname = "Der Verwandte";
$members[$member->l4uid] = $member;

// 4. extract data

$ids = "";
for ($j = 0; $j < sizeof($members); $j++) {
    if ($j != 0) {
        $ids = $ids."%2C".$members[$j]->summonerid;
    } else {
        $ids = $members[$j]->summonerid;
    }
}

curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://euw.api.riotgames.com/api/lol/EUW/v1.4/summoner/'.$ids.'?api_key=RGAPI-19a18026-9713-4e4b-968d-159abeed82e1'
    // CURLOPT_URL example: https://euw.api.pvp.net/api/lol/euw/v1.4/summoner/by-name/RiotSchmick?api_key=<<<mykey>>>
));
$result = curl_exec($curl);
$object1 = json_decode($result);

// first: request all basic data
for ($i = 0; $i < sizeof($members); $i++) {

    $prop = $members[$i]->summonerid;
    $members[$i]->summonerobject = new \StdClass;
    $members[$i]->summonerobject->name = $object1->$prop->name;
    $members[$i]->summonerobject->profileIconId = $object1->$prop->profileIconId;
    $members[$i]->summonerobject->revisionDate = $object1->$prop->revisionDate;
    $members[$i]->summonerobject->summonerLevel = $object1->$prop->summonerLevel;

    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://euw1.api.riotgames.com/lol/league/v3/positions/by-summoner/'.$members[$i]->summonerid.'?api_key=RGAPI-19a18026-9713-4e4b-968d-159abeed82e1'
    ));
    $result = curl_exec($curl);
    $object = json_decode($result);

    $members[$i]->rankstats = new \StdClass;
    $members[$i]->rankstats = $object;

}

// second: make a short break^^
set_time_limit(21);
sleep(11);

// third: search additional information (champion etc.)
for ($i = 0; $i < sizeof($members); $i++) {

    $prop = $members[$i]->summonerid;
    $gamezero = "0";

    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://euw.api.riotgames.com/api/lol/EUW/v1.3/game/by-summoner/'.$members[$i]->summonerid.'/recent?api_key=RGAPI-19a18026-9713-4e4b-968d-159abeed82e1'
    ));
    $result = curl_exec($curl);
    $object2 = json_decode($result);

    // print_r($object2);

    $members[$i]->matchobject = new \StdClass;
    $members[$i]->matchobject->gameID = $object2->games[0]->gameId;
    $members[$i]->matchobject->gameMode = $object2->games[0]->gameMode;
    $members[$i]->matchobject->championId = $object2->games[0]->championId;
    $members[$i]->matchobject->createDate = $object2->games[0]->createDate;
    $members[$i]->matchobject->stats = new \StdClass;
    $members[$i]->matchobject->stats = $object2->games[0]->stats;

}

// fourth: another short break^^
set_time_limit(21);
sleep(11);

// fifth: getting champion names
for ($i = 0; $i < sizeof($members); $i++) {

    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'https://global.api.riotgames.com/api/lol/static-data/EUW/v1.2/champion/'.$members[$i]->matchobject->championId.'?api_key=RGAPI-19a18026-9713-4e4b-968d-159abeed82e1'
    ));
    $result = curl_exec($curl);
    $object3 = json_decode($result);

    $members[$i]->matchobject->championName = $object3->name;
    $members[$i]->matchobject->championKey = $object3->key;
    $members[$i]->matchobject->championTitle = $object3->title;

    // DEBUG
    print_r($members[$i]);

}


// 5. write and close file, done
fwrite($fp, json_encode($members));
fclose($fp);
print_r("done");