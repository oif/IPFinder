<?php
require_once('./libs/IPFinder.php');

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

echo json_encode($apiResponse); // 输出

