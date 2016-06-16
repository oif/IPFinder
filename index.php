<?php

class IPFinder {

    protected $ip;
    protected $db_host;
    protected $db_user;
    protected $db_pass;
    protected $db_name;

    function __construct($ip)
    {
        $this->ip = $ip;
        $this->db_host = '106.187.93.29';
        $this->db_user = '4399_user';
        $this->db_pass = '12j3hias6';
        $this->db_name = '4399';
    }

    function validator() {  // 验证合法 IP
        if (preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',$this->ip)) {
            return true;
        }
        return false;
    }

    function getStorageTable() {    // 获取对应的数据储存表
        $segment = explode('.', $this->ip);
        $tables = array(0, 70, 150, 210, 218, 220, 255);
        for ($i=0; $i < count($tables); $i++) {
            if ($segment[0] >= $tables[$i] && $segment[0] <= $tables[$i+1]) {
                return $i;
            }
        }
    }

    function getGeo() { // 获取物理地址
        $table = $this->getStorageTable();
        $db = mysql_connect($this->db_host, $this->db_user, $this->db_pass);
        if (!$db) {
          die('Could not connect to db: ' . mysql_error());
        }
        mysql_select_db($this->db_name, $db);
        mysql_query("set names 'utf8'");
        $sql = "SELECT geo FROM ip_".$table." where INET_ATON('$this->ip') between startAt and endAt LIMIT 1";
        $result = mysql_query($sql);
        $api = array('ip' => $this->ip, 'geo' => mysql_fetch_array($result)['geo']);
        return $api;
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

runtime();
if (!empty($_SERVER['REQUEST_URI'])){
    $url = $_SERVER['REQUEST_URI'];
    $urlArray = explode("/",$url);
    if (!empty($urlArray[1]) ) {

        $geo = new IPFinder($urlArray[1]);
        if ($geo->validator()) {    // 验证所输入的 IP
            $apiResponse = $geo->getGeo();  // 获取物理地址
        } else {
            $apiResponse = array('err' => 'bad IP', 'errCode' => '2333');  // 非法 IP
        }
    }
}
$apiResponse['responseTime'] = runtime(1).'ms';

echo json_encode($apiResponse);