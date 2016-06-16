<?php

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

function IPFormator($row) { // IP 格式化返回

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
    $tables = array(0, 70, 150, 210, 218, 220, 255);
    for ($i=0; $i < count($tables); $i++) {
        if ($segment[0] >= $tables[$i] && $segment[0] <= $tables[$i+1]) {
            return $i;
        }
    }
}

$db = mysql_connect("106.187.93.29","4399_user","12j3hias6");
if (!$db) {
  die('Could not connect to db: ' . mysql_error());
}
mysql_select_db("4399", $db);
mysql_query("set names 'utf8'");
$ipList = fopen("ip.txt", "r") or die("Unable to open file!");
runtime();
while(!feof($ipList)) {
    $ip = fgets($ipList);
    if (empty($ip)) {
        break;
    }
    $row = IPFormator($ip);
    // INET_ATON 将 IP 格式化，便于查询判断
    $sql = "INSERT INTO ip_".getStorageTable($row[0])." (startAt, endAT, geo) VALUES (INET_ATON('".$row[0]."'), INET_ATON('".$row[1]."'), '".$row[2]."')";
    mysql_query($sql);
}
echo runtime(1);    // 总计运行时间
fclose($ipList);
mysql_close($db);