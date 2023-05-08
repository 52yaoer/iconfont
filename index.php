<?php

//if (!$_GET['name']) {
if (!isset($_GET["name"])) {
?>

    <title>a-icon 图标服务</title>
    <link rel="shortcut icon" href="/favicon.ico">
    <style>
        .page {
            display: flex;
            flex-flow: row wrap;
        }

        .item {
            width: 100px;
            height: 100px;
            margin: 10px
        }

        .svg-img {
            width: 68px;
            height: 68px;
            margin: 0 auto;
            display: block;
        }

        .icon-name {
            color: #666;
            font-size: 14px;
            text-align: center;
        }

        .upload-box {
            height: 200px;
            background: #eee;
            padding: 10px;
        }

        .upload-box-p .plupload-preview {
            flex-wrap: wrap;
            /*height:60%;*/
            list-style: none;
            display: none;
        }

        .upload-box-p {
            border: 1px dashed #ada2a2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .upload-box-desc {
            text-align: center;
            display: block;
            color: #888;
            font-size: 2em;
        }

        a:link,
        a:visited {
            text-decoration: none;
        }

        a:hover p,
        a:active p {
            color: red;
        }
    </style>
    <div class='upload-box'>
        <div class="upload-box-p" id="uploadBoxArea">
            <div class="upload-box-btn">
                <div><label class="upload-box-desc">没找到需要的图标？拖个2KB以内的svg到这个灰框里来试试看!☺。</label></div>
            </div>
        </div>
    </div>
    <div class="page" id="page">
    <?php
    $dir = dirname(__FILE__) . '/icon';
    $file = scandir($dir);

    $svgs = array_filter($file, function ($svg) {
        return strpos($svg, '.svg') !== false;
    });

    foreach ($svgs as $svg) {
        $name = str_replace('.svg', '', $svg);
        echo "<div class=item><a href=?name={$name}&size=64&scale=2&color=red target=_blank><img class=svg-img src=/icon/{$svg} /><p class=icon-name>{$name}</p></a></div>";
    }


    echo "</div><script>

            var dz = document.getElementById('uploadBoxArea');
            dz.ondragover = function (ev) {
            ev.preventDefault();
            this.style.borderColor = 'red';
            }
            dz.ondragleave = function () {
            this.style.borderColor = 'gray';
            }
            dz.ondrop = function (ev) {
            this.style.borderColor = 'gray';
            ev.preventDefault();
            var files = ev.dataTransfer.files;
            if (files.length > 0) {
                const formData = new FormData();
                formData.append('file', files[0]);
                fetch('/upload.php', {
                method: 'POST',
                body: formData
                }).then(res => {
                return res.json()
                }).then(res => {
                if (res.code === 0) {
                    alert(res.msg)
                } else {
                    location.reload();
                }
                })
            }
            }

</script>";
} else {

    if ($_GET['color']) {
        $color = str_replace('_', '#', $_GET['color']);
    } else {
        $color = '#ccc';
    }


    try {
        $svg = @file_get_contents("./icon/{$_GET['name']}.svg");


        if (strpos($_GET['name'], 'logo-i') !== 0) {
            if (strpos($svg, 'fill=') !== false) {
                $svg = preg_replace('/fill="(.*)"/', "fill='{$color}'", $svg);
            } else {
                $svg = str_replace('<path ', "<path fill='{$color}' ", $svg);
            }
        }
        if ($_GET['scale']) {
            $scale = intval($_GET['scale']);
            if ($scale >= 5) {
                $scale = 5;
            }
        } else {
            $scale = 2;
        }


        if ($_GET['size']) {
            $size = intval($_GET['size']);
            if ($size >= 512) {
                $size = 512;
            }
        } else {
            $size = 64;
        }

        $size *= $scale;


        $im = new Imagick();
        $im->setSize($size, $size);
        $im->setBackgroundColor(new ImagickPixel('transparent'));
        $im->readImageBlob($svg);
        $im->setImageFormat("png32");
        header('Content-type: image/png');
        header("Cache-Control: max-age=3600");
        echo $im;
        $im->clear();
        $im->destroy();
    } catch (Exception $e) {
        http_response_code(404);
    }
}
