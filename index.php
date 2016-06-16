<?php

include('lib/SQLBase.php');

class IPFinder extends SQLBase {

    public $ip;

    function __construct($ip)
    {
        $this->ip = ip2long($ip);
    }

    function getStorageTable() {    // 获取对应的数据储存表
        $tables = array(1019208517, 1035514387, 1857440693, 2101951965, 3268745471, 3544337407, 3658925698, 3662981000, 3684312051, 3722450404, 3739018005, 4294967295);
        for ($i=0; $i < count($tables); $i++) {
            if ($this->ip <= $tables[$i]) {
                return $i;
            }
        }
    }

    function getGeo() { // 获取物理地址
        $this->connect();    // 连接数据库
        runtime();
        $geoInfo = $this->queryIP($this->getStorageTable(), $this->ip);
        $totalTime = runtime(1);
        $this->disconnect();    // 释放连接
        return array('ip' => long2ip($this->ip), 'geo' => $geoInfo, 'responseTime' => $totalTime.'ms');
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
    $geo = new IPFinder($urlArray[1]);  // 初始化
    if (!empty($urlArray[1])) {
        if ($geo->ip) {    // 验证所输入的 IP
            $apiResponse = $geo->getGeo();  // 获取物理地址
        } else {
            $apiResponse = array('err' => 'bad IP', 'errCode' => '2333');  // 非法 IP
        }
    }
}

echo json_encode($apiResponse);