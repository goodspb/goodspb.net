<?php

$dir = "./source/_posts";

$dh = opendir($dir);
$i = 0;
while ($file = readdir($dh)){
    if( $file =="." || $file ==".." || substr($file, -3) != '.md'){
        continue;
    }
    $arr = file($dir . '/' . $file);
    $title = $arr[1];
    $title = strtr(trim(str_replace('title:', '', $title)), [
        " " => "-",
        "/" => "",
        "," => "",
        "'" => "",
        "，" => "",
        "--" => "-",
    ]);
    $new =  $title . '.md';
    echo $new;
    echo "\n";
    rename($dir . '/' . $file, $dir . '/' . $new);
    $i ++;
}
echo $i;
