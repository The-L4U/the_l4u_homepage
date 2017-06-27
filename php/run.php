<?php
// Here we want to perform our steps to extract the riot api info into a tiny json file
// we create on the server. If we already have one, we want to override it.

// 1. initialize curl
$curl = curl_init();

// key (hidden): RGAPI-19a18026-9713-4e4b-968d-159abeed82e1

// 2. initialize file
$fp = fopen('datajson', 'w');

// 3. define data for requests (summoner-ids) in the most beautiful way - by hand!
$members = array();
$member = new \StdClass;
$member->l4uid = 0;
$member->l4urank = 0;
$member->summonerid = 27730651;
$member->task = "Papstsachen";
$member->l4uname = "Der Papst";
$members[$member->l4uid] = $member;
$member = new \StdClass;
$member->l4uid = 1;
$member->l4urank = 1;
$member->summonerid = 32737299;
$member->task = "Militär";
$member->l4uname = "ELGDEM";
$members[$member->l4uid] = $member;
$member = new \StdClass;
$member->l4uid = 2;
$member->l4urank = 2;
$member->summonerid = 22725027;
$member->task = "Nennen wir es Unterhaltungsbranche";
$member->l4uname = "Der König";
$members[$member->l4uid] = $member;
$member = new \StdClass;
$member->l4uid = 3;
$member->l4urank = 6;
$member->summonerid = 35712937;
$member->task = "planlos";
$member->l4uname = "Der Planloser";
$members[$member->l4uid] = $member;
$member = new \StdClass;
$member->l4uid = 4;
$member->l4urank = 3;
$member->summonerid = 221626;
$member->task = "Minister für Zettelwirtschaft";
$member->l4uname = "Mr. Zylinder";
$members[$member->l4uid] = $member;
$member = new \StdClass;
$member->l4uid = 5;
$member->l4urank = 5;
$member->summonerid = 37948471;
$member->task = "Geheimdienst";
$member->l4uname = "Der Fremde";
$members[$member->l4uid] = $member;
$member = new \StdClass;
$member->l4uid = 6;
$member->l4urank = 4;
$member->summonerid = 82608651;
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

    // DEBUG
    // print_r($members[$i]);
}

// 5. write and close file, done
fwrite($fp, json_encode($members));
fclose($fp);
print_r("done");