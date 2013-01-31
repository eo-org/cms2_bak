<?php
//phpinfo();

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;

$m = new MongoClient('mongodb://craftgavin:whothirstforCENTERdb@10.150.183.38');
$db = $m->selectDb('server_cen');
for($i = 0; $i < 100; $i++) {
$siteArr = $db->site->findOne(array('domains.domainName' => 'www.chinesenu.com'));
}
//print_r($siteArr);
echo "<br />";
$mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $endtime = $mtime;
   $totaltime = ($endtime - $starttime);
   echo "This page was created in ".$totaltime." seconds"; 

echo "<br />===================================================<br />";




$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;

$m = new MongoClient('mongodb://craftgavin:whothirstforCENTERdb@10.150.183.38');
$db = $m->selectDb('server_cen');
for($i = 0; $i < 100; $i++) {
$siteArr = $db->site->findOne(array('domains.domainName' => 'www.chinesenu.com'));
}
//print_r($siteArr);
echo "<br />";
$mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $endtime = $mtime;
   $totaltime = ($endtime - $starttime);
   echo "This page was created in ".$totaltime." seconds";

echo "<br />===================================================<br />";







$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;


$m = new MongoClient('127.0.0.1');
$db = $m->selectDb('server_cen');
for($i = 0; $i < 100; $i++) {
$siteArr = $db->site->findOne(array('domains.domainName' => "www.chinesenu.com"));
}
//print_r($siteArr);
echo "<br />";
$mtime = microtime();
   $mtime = explode(" ",$mtime);
   $mtime = $mtime[1] + $mtime[0];
   $endtime = $mtime;
   $totaltime = ($endtime - $starttime);
   echo "This page was created in ".$totaltime." seconds";

?>
