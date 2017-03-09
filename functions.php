<?php
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function makeTime($timestamp) {
$ts = $timestamp;
$cts = time();
$str = "";
$result = $cts-$ts;
if ($result < 31556952) {
if ($result < 2629746) {
if ($result < 86400) {
if ($result < 3600) {
if ($result < 60) {
$n = $result/1;
if ($n == 1){
$str = " second";
}else{
$str = " seconds";
}
$final = $n.$str;
}else{
 $n = floor($result/60);
if ($n == 1){
$str = " minute";
  }else{
  $str = " minutes";
}
$final = $n.$str;
 }
            }else{
            $n = floor($result/3600);
            if ($n == 1){
                    $str = " hour";
                    }else{
                    $str = " hours";
                    }
                    $final = $n.$str;
            }
        }else{
        $n = floor($result/86400);
        if ($n == 1){
                    $str = " day";
                    }else{
                    $str = " days";
                    }
                    $final = $n.$str;
        }
    }else{
    $n = floor($result/2629746);
    if ($n == 1){
                    $str = " month";
                    }else{
                    $str = " months";
                    }
                    $final = $n.$str;
    }
}else{
$n = floor($result/31556952);
if ($n == 1){
                    $str = " year";
                    }else{
                    $str = " years";
                    }
                    $final = $n.$str;
}
return $final;
}
?>