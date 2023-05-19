<?php

function banner() {
  $out = PHP_EOL.PHP_EOL.color()["LW"]."-- 0 Bounce Email Validator --".color()["WH"]."
".color()["WH"].color()["LW"]."-- Recoded from Umbrella  --".color()["WH"]."
".color()["WH"].PHP_EOL.PHP_EOL;
  return $out;
}

function formatPeriod($endtime, $starttime) {
  $duration = $endtime - $starttime;
  $hours = (int) ($duration / 60 / 60);
  $minutes = (int) ($duration / 60) - $hours * 60;
  $seconds = (int) $duration - $hours * 60 * 60 - $minutes * 60;
  return ($hours == 0 ? "00":$hours) . " Hours " . ($minutes == 0 ? "00":($minutes < 10? "0".$minutes:$minutes)) . " Minutes " . ($seconds == 0 ? "00":($seconds < 10? "0".$seconds:$seconds))." Seconds";
}
function color() {
  return array(
    "LW" => ("\e[1;37m"),
    "WH" => ("\e[0m"),
    "YL" => ("\e[1;33m"),
    "LR" => ("\e[1;31m"),
    "MG" => ("\e[0;35m"),
    "LM" => ("\e[1;35m"),
    "CY" => ("\e[1;36m"),
    "LG" => ("\e[1;32m")
  );
}
function genStr($length = 5) {
    $characters = 'abcdefghijklmopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
    echo $randomString;
}
function genNum($length = 10) {
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
    echo $randomString;
}
function getStr($source, $start, $end) {
    $a = explode($start, $source);
    $b = explode($end, $a[1]);
    return $b[0];
}
function curl($url, $data = 0, $header = 0, $cookie = 0, $custom = 0) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    if($header) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    }
    if($data) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    if($cookie) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    }
    if($custom) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $custom);
    }
    
    $x = curl_exec($ch);
    curl_close($ch);
    return $x;
}
