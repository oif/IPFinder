<?php
include '../libs/Importer.php';

$importer = new Importer();
$importer->connect();
echo "Start import IP list...\n";
runtime();
$importer->run();   // 导入数据
echo "Time usage: ".runtime(1)."\n";    // 总计运行时间
$importer->disconnect();
