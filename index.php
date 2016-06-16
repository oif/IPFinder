<?php

include('lib/SQLBase.php');

class IPFinder extends SQLBase {

    protected $ip;
    function __construct($ip)
    {
        $this->ip = $ip;
    }

    function validator() {  // 验证合法 IP
        if (preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',$this->ip)) {
            return true;
        }
        return false;
    }

    function getStorageTable() {    // 获取对应的数据储存表
        $segment = explode('.', $this->ip);
        $tables = array(62, 147, 212, 219, 223, 255);
        for ($i=0; $i < count($tables); $i++) {
            if ($segment[0] <= $tables[$i]) {
                return $i;
            }
        }
    }

    function getGeo() { // 获取物理地址

        return array('ip' => $this->ip, 'geo' => $this->queryIP($this->getStorageTable(), ip2long($this->ip)));
    }
}

function runtime($mode = 0) {   // 运行时间监测
    static $t;
    if(!$mode) {
        $t = microtime();
        return;
    }
    $t1 = microtime();
    list($m0,$s0) = split(" ",$t);
    list($m1,$s1) = split(" ",$t1);
    return round(($s1+$m1-$s0-$m0)*1000, 7);
}

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");    // API

$apiResponse = array('err' => 'Invalid input', 'errCode' => '666');


if (!empty($_SERVER['REQUEST_URI'])){
    $url = $_SERVER['REQUEST_URI'];
    $urlArray = explode("/",$url);
    if (!empty($urlArray[1]) ) {
        $geo = new IPFinder($urlArray[1]);
        if ($geo->validator()) {    // 验证所输入的 IP
            runtime();
            $geo->connect();
            $apiResponse = $geo->getGeo();  // 获取物理地址
            $apiResponse['responseTime'] = runtime(1).'ms';
            $geo->disconnect();
        } else {
            $apiResponse = array('err' => 'bad IP', 'errCode' => '2333');  // 非法 IP
        }
    }
}


echo json_encode($apiResponse);