<?
header('content-type:application/json');
$res = ['code' => 0, 'msg' => '开始上传'];
$temp = explode(".", $_FILES["file"]["name"]);

$dir =  dirname(__FILE__) . '/icon/';
$extension = end($temp);     // 获取文件后缀名
if ((($_FILES["file"]["type"] == "image/svg+xml")) && ($_FILES["file"]["size"] < 2048)) {
    if ($_FILES["file"]["error"] > 0) {
        echo "错误：: " . $_FILES["file"]["error"] . "<br>";
    } else {
        // 判断当前目录下的 upload 目录是否存在该文件
        // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
        if (file_exists($dir . $_FILES["file"]["name"])) {
            $res = ['code' => 0, 'msg' => $_FILES["file"]["name"] . " 文件已经存在。 "];
        } else {
            // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
            move_uploaded_file($_FILES["file"]["tmp_name"], $dir . $_FILES["file"]["name"]);

            $res = ['code' => 200, 'msg' => "上传成功"];
        }
    }
} else {

    $res = ['code' => 0, 'msg' => "格式或者大小不满足条件,只能上传小于2kb的svg图片", 'data' => $_FILES];
}

echo json_encode($res);
