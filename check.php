<?php

require "func/RandUA.php";
require "func/func.php";
ob_implicit_flush();
date_default_timezone_set("Asia/Jakarta");
define("OS", strtolower(PHP_OS));
$_dircookies = "cookies";
if(!is_dir($_dircookies)) mkdir($_dircookies);
$cookie = dirname(__FILE__)."/".$_dircookies."/".md5(time()).".cook";
if(file_exists($cookie)) unlink($cookie);
echo banner();
enterlist:
$listname = readline(" Enter list: ");
if(empty($listname) || !file_exists($listname)) {
  echo" [?] list not found".PHP_EOL;
  goto enterlist;
}
$lists = array_unique(explode("\n", str_replace("\r", "", file_get_contents($listname))));
$savetodir = readline(" Save to dir (default: valid)? ");
$savetodir = empty($savetodir) ? "valid" : $savetodir;
if(!is_dir($savetodir)) mkdir($savetodir);
chdir($savetodir);
$delpercheck = readline(" Delete list per check (y/n)? ");
$delpercheck = strtolower($delpercheck) == "y" ? true : false;

$RandUA = $userAgent = (new userAgent) ->generate();
$randStr = genStr(10);
$no = 0; $total = count($lists); $registered = 0; $die = 0; $unkwn = 0; $locked = 0;
$lists = array_chunk($lists, 20);
echo PHP_EOL;

  $mic_time = microtime();
   $mic_time = explode(" ",$mic_time);
   $mic_time = $mic_time[1] + $mic_time[0];
   $start_time = $mic_time;
foreach($lists as $clist) {
  $array = $ch = array();
  $mh = curl_multi_init();
  foreach($clist as $i => $list) {
    $no++;
    $email = $list;
    if(empty($email)) { continue; }
    $array[$i]["no"] = $no;
    $array[$i]["list"] = $list;
    $array[$i]["email"] = $email;
    $header = array(
	"Sec-Fetch-Mode: cors",
	"Accept: application/json, text/plain, */*",
	"Origin: https://www.loqate.com",
	"Content-Type: application/x-www-form-urlencoded; charset=UTF-8",
	"Referer: https://www.loqate.com/",
	"User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.90 Safari/537.36");

    $ch[$i] = curl_init();
    curl_setopt($ch[$i], CURLOPT_URL, "https://api.emails-checker.net/check?access_key=[insertapikey]&email=$email"); /* CHANGE YOUR API HERE */
    curl_setopt($ch[$i], CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch[$i], CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch[$i], CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch[$i], CURLOPT_ENCODING, "gzip");
    curl_setopt($ch[$i], CURLOPT_POST, 0);
    curl_setopt($ch[$i], CURLOPT_HEADER, 1);
    curl_setopt($ch[$i], CURLOPT_COOKIEJAR, dirname(__FILE__)."cookies/AppleVal-".$randStr.".cook");
    curl_setopt($ch[$i], CURLOPT_COOKIEFILE, dirname(__FILE__)."cookies/AppleVal-".$randStr.".cook");
    curl_setopt($ch[$i], CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch[$i], CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch[$i], CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_multi_add_handle($mh, $ch[$i]);
  }
  $active = null;
  do {
    curl_multi_exec($mh, $active);
  } while($active > 0);
  foreach($ch as $i => $c) {
    $no =  $array[$i]["no"];
    $list =  $array[$i]["list"];
    $email =  $array[$i]["email"];
    $x = curl_multi_getcontent($c);
    if (preg_match("#Valid Email Address#", $x)) {
    $registered++;
        file_put_contents("live.txt", $email.PHP_EOL, FILE_APPEND);
        echo "[".date("H:i:s")." ".$no."/".$total."] ".color()["LG"]."LIVE ".color()["WH"]." => ".$email.color()["WH"]; flush();
    }
    elseif (preg_match("#Invalid Email Address#", $x)) {
      $die++;
      file_put_contents("die.txt", $email.PHP_EOL, FILE_APPEND);
      echo "[".date("H:i:s")." ".$no."/".$total."] ".color()["LR"]."DEAD ".color()["WH"]." => ".$email.color()["WH"]; flush();
    }
    else{
      $unkwn++;
      file_put_contents("unkwn.html", $x);
      file_put_contents("unknwn.txt", $email.PHP_EOL, FILE_APPEND);
      echo "[".date("H:i:s")." ".$no."/".$total."] ".color()["LW"]."unkwn ".color()["WH"]." => ".$email.color()["WH"]; flush();
    }
      echo "0 Day Email Validator - Recoded";
    if($delpercheck) {
        $awal = str_replace("\r", "", file_get_contents("../".$listname));
          $akhir = str_replace($list."\n", "", $awal);
          if($no == $total) $akhir = str_replace($list, "", $awal);
          file_put_contents("../".$listname, $akhir);
      }
    echo PHP_EOL;
    curl_multi_remove_handle($mh, $c);
  }
  curl_multi_close($mh);
}
if(empty(file_get_contents("../".$listname))) unlink("../".$listname);
$mic_time = microtime();
   $mic_time = explode(" ",$mic_time);
   $mic_time = $mic_time[1] + $mic_time[0];
   $endtime = $mic_time;
   $total_execution_time = formatPeriod($endtime, $start_time);
   echo " -- This checking is taking: [ ".$total_execution_time." ]";
echo PHP_EOL." -- Total: ".$total." - Live: ".$registered." - Locked: ".$locked." - Die: ".$die." - unkwn: ".$unkwn.PHP_EOL."Saved to dir \"".$savetodir."\"".PHP_EOL;

