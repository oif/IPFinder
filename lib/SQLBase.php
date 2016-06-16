<?php

/**
*
*/


define('DB_HOST', '106.187.93.29');
define('DB_USER', '4399_user');
define('DB_PASS', '12j3hias6');
define('DB_NAME', '4399');

class SQLBase {

    protected $db_handler;

    function __construct() {
    }

    function connect() {
        $this->db_handler = mysql_connect(DB_HOST, DB_USER, DB_PASS);
        if (!$this->db_handler) {
          die('Could not connect to databse: ' . mysql_error());
        }
        mysql_select_db(DB_NAME, $this->db_handler);
        mysql_query("set names 'utf8'");
    }

    function disconnect() {
        mysql_close($this->db_handler);
    }

    function insertIP($table, $row) {
        $sql = "INSERT INTO ip_".$table." (startAt, endAT, geo) VALUES (INET_ATON('".$row[0]."'), INET_ATON('".$row[1]."'), '".$row[2]."')";    // INET_ATON 将 IP 格式化，便于查询判断
        return mysql_query($sql);
    }

    function queryIP($table, $ip) {
        //$sql = "SELECT geo FROM ip_".$table." where '$ip' between startAt and endAt LIMIT 1";
        $sql = "SELECT geo FROM ip_".$table." where '$ip' <= endAt LIMIT 1";
        $result = mysql_query($sql);
        return mysql_fetch_array($result)['geo'];
    }


}