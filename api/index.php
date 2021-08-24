<?php
header('content-type:application/json');
header("access-control-allow-headers: Accept,Authorization,Cache-Control,Content-Type,DNT,If-Modified-Since,Keep-Alive,Origin,User-Agent,X-Mx-ReqToken,X-Requested-With");
header("access-control-allow-methods: GET, POST, PUT, DELETE, HEAD, OPTIONS");
header("access-control-allow-credentials: true");
header("access-control-allow-origin: *");


$dir =  dirname(__FILE__).'/../icon';
$file = scandir($dir);

$svgs = array_filter($file,function ($svg){
    return strpos($svg,'.svg') !== false;
});


$data = [];
foreach ($svgs as $svg){
    array_push($data,str_replace('.svg','',$svg));
}

echo json_encode(['code'=>200,'msg'=>'success','data'=>$data]);