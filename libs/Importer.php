<?php
include 'SQLBase.php';

define('RPT', 40000);   // rows per table
define('IPLIST', '../tools/ip.txt');

class Importer extends SQLBase {

    protected $IPTactics;

    function __construct() {
        //$this->IPTactics = $this->deliverIPList();
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

    function run() {
        $ipList = fopen(IPLIST, "r") or die("Unable to open file!");
        $IPCounter = 0;
        $tableRecoder = 0;
        $this->createTable($tableRecoder);
        echo "Importing...\n";
        echo "----------------\n";
        echo "Partition:\n";
        while(!feof($ipList)) {
            $ip = fgets($ipList);
            if (empty($ip)) {
                break;
            }
            $row = Importer::IPFormator($ip);
            $this->insertIP($tableRecoder, $row);
            $IPCounter++;
            if ($IPCounter >= RPT) {    // 切换表
                echo ip2long($row[1]).', ';
                $IPCounter = 0;
                $tableRecoder++;
                $this->createTable($tableRecoder);
            }

        }
        echo "4294967295\n";
        echo "----------------\n";
        fclose($ipList);
        echo "Created $tableRecoder tables in total\n";
        echo "All the IP list import finish!\n";
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
    return sprintf("%.3f ms",($s1+$m1-$s0-$m0)*1000);
}
