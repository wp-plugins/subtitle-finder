<?php
function subt_download(){
$url = base64_decode($_GET['url']);
if(isset($_GET['subtdl']) && !empty($url)){
$s = curl_init(); 
curl_setopt($s,CURLOPT_URL,"http://subscene.com/".$url); 
curl_setopt($s,CURLOPT_RETURNTRANSFER,true); 
$codes = curl_exec($s); 
if(curl_error($s)){
echo '<meta charset="utf-8" />';
_e("Cannot Connect to Subscene.com","subt");
die();
}
curl_close($s); 
preg_match_all('/<div class=\"download\">(.*?)<\/div>/s',$codes,$code);
$code = preg_replace("#<thead>(.*)</thead>#s","",$code[0]);
$code = $code[0];

$dom = new DOMDocument;
@$dom->loadHTML('<?xml encoding="utf-8" ?>'.$code);
$link = $dom->getElementsByTagName('a')->item(0);
@$url = $link->getAttribute('href');

if(get_option("subt_direct") == 1){
$s = curl_init(); 
curl_setopt($s,CURLOPT_URL,"http://subscene.com/".$url); 
curl_setopt($s,CURLOPT_RETURNTRANSFER,true); 
$bytes = curl_exec($s); 
if(curl_error($s)){
echo '<meta charset="utf-8" />';
_e("Cannot Connect to Subscene.com","subt");
die();
}
curl_close($s);
$filename = $_GET['filename'];
if(empty($filename))
$filename = "subtitle.zip";
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.$filename.'"'); //<<< Note the " " surrounding the file name
header('Content-Transfer-Encoding: binary');
header('Connection: Keep-Alive');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
echo $bytes;


} else {
echo '<script> window.location = "http://subscene.com/'.$url.'"; </script>';
}
}
}
?>