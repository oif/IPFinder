<?php
include('../lib/SQLBase.php');
define('TABLECOUNT', 6);
define('IPLIST', 'ip.txt');

function runtime($mode = 0) {   // 运行时间监测
    static $t;
    if(!$mode) {
        $t = microtime();
        return;
    }
    $t1 = microtime();
    list($m0,$s0) = split(" ",$t);
    list($m1,$s1) = split(" ",$t1);
    return sprintf("%.3f ms",($s1+$m1-$s0-$m0)*1000);
}

/**
*
*/
class IPImporter extends SQLBase {

    protected $IPTactics;
    protected $IPList;

    function __construct() {
        $this->IPTactics = $this->deliverIPList();
    }

    static function IPFormator($row) { // IP 格式化返回
        $ip = explode(' ', $row);
        $result = array();
        $keeper = '';
        $co = 0;
        foreach ($ip as $info) {
            if (!empty($info)) {
                if ($co < 2) {  // 0 为 起始 IP，2 为 结束 IP
                    array_push($result, $info);
                    $co++;
                } else {
                    $keeper .= $info;   // 将所有物理地址信息去除空格
                }
            }
        }
        array_push($result, $keeper);
        return $result;
    }

    function getStorageTable($ip) { // 分表储存
        $segment = explode('.', $ip);
        $tables = $this->IPTactics;
        for ($i=0; $i < count($tables); $i++) {
            if ($segment[0] <= $tables[$i]) {
                return $i;
            }
        }
    }

    static function ASegment($ip) { // 分表储存
        $segment = explode('.', $ip);
        return $segment[0];
    }

    function deliverIPList() {
        $ipList = fopen(IPLIST, "r") or die("Unable to open file!");
        $IPDist = array_fill(0, 256, 0);
        $rowCount = 0;
        while(!feof($ipList)) {
            $ip = fgets($ipList);
            if (empty($ip)) {
                break;
            }
            $row = IPImporter::IPFormator($ip);
            $IPDist[IPImporter::ASegment($row[0])]++;
            $rowCount++;
        }
        fclose($ipList);
        $avg = $rowCount/TABLECOUNT;
        $storageTactics = array();
        foreach ($IPDist as $seg => $co) {
            if ($avg <= 0) {
                array_push($storageTactics, $seg);
                $avg = $rowCount/TABLECOUNT;
            } else {
                $avg -= $co;
            }
        }
        array_push($storageTactics, 255);
        return $storageTactics;
    }

    function run() {
        $ipList = fopen(IPLIST, "r") or die("Unable to open file!");
        $this->connect();
        while(!feof($ipList)) {
            $ip = fgets($ipList);
            if (empty($ip)) {
                break;
            }
            $row = IPImporter::IPFormator($ip);
            $this->insertIP($this->getStorageTable($ip), $row);
        }
        $this->disconnect();
        fclose($ipList);
        var_dump($this->IPTactics);
    }

}

runtime();
$importer = new IPImporter();
$importer->run();
echo runtime(1);    // 总计运行时间