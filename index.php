<?php

ini_set('max_execution_time', 0);
error_reporting(E_ALL);
ini_set('display_errors', 1);
$content = file_get_contents('orders_customers_products.csv');
$content = str_replace("\n", "<br/>", $content);
foreach(range(1,100) as $num){
    $content = str_replace('"'.$num.',', '"'.$num.'","', $content);
}
echo $content;
exit;
$snap_folders = array_filter(glob('images/animated_snaps_categorized/*'), 'is_dir');
$finalString = "array(";
foreach ($snap_folders as $folder) {
$folderName = str_replace("images/animated_snaps_categorized/", "", $folder);
$snaps = glob(''.$folder.'/*.gif');
$letterArray = array();
foreach ($snaps as $snap) {
$snap = str_replace("images/animated_snaps_categorized/".$folderName."/", "", $snap);
$snap = str_replace(".gif", "", $snap);
if (isset($letterArray[$folderName])) {
array_push($letterArray[$folderName], $snap);
} else {
$letterArray[$folderName] = array($snap);
}
}

$lastKey = '';

foreach ($letterArray as $key => $value) {
//if ($lastKey == '' || $lastKey != $key) {
$finalString .= "'" . $key . "' => array(";
foreach ($value as $val) {
$finalString .= "'" . $val . "',";
}
$finalString .= "),<br/>";
}

}
$finalString .= ")";
$finalString = str_replace(',)', ')', $finalString);
echo $finalString;
exit;
/*$dirs = array_filter(glob('images/animated_snaps_categorized/relation/*'), 'is_dir');
foreach ($dirs as $_filename) {
    echo '<img src="'. $_filename . '/frame1.png"/><br/>';
}
exit;*/
$snaps = glob('images/animated_snaps_categorized/relation/*.gif');
foreach ($snaps as $_filename) {    
    /*$_actual = basename($_filename);
    $new_actual = str_replace("_", "-", $_actual);
    $new_filename = str_replace($_actual, $new_actual, $_filename);
    if($_filename != $new_filename){
        rename($_filename, $new_filename);
    }
    continue;*/
    //$_filename = 'images/animated_snaps_categorized/toy-story/mr-potato.gif';
    $directory = str_replace(".gif", "", $_filename);
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }else{
        continue;
    }
    $animatedGifExtractedFrames = glob($directory . '/*.png');
    if (sizeof($animatedGifExtractedFrames) == 0) {
        shell_exec("ffmpeg -i " . $_filename . " -vsync 0 " . $directory .
            "/frame%d.png");
        $animatedGifExtractedFrames = glob($directory . '/*.png');
        foreach ($animatedGifExtractedFrames as $animatedGifExtractedFrame) {
            $_backgroundColour = '0,0,0';
            $_img = imagecreatefrompng($animatedGifExtractedFrame);
            $_backgroundColours = explode(',', $_backgroundColour);
            $_removeColour = imagecolorallocate($_img, (int)$_backgroundColours[0], (int)$_backgroundColours[1],
                (int)$_backgroundColours[2]);
            imagecolortransparent($_img, $_removeColour);
            imagesavealpha($_img, true);
            $_transColor = imagecolorallocatealpha($_img, 0, 0, 0, 127);
            imagefill($_img, 0, 0, $_transColor);
            imagepng($_img, $animatedGifExtractedFrame);
            $newimage = resize_image($animatedGifExtractedFrame, 750, 645, false);
            //$newimage = resize_image($animatedGifExtractedFrame, 1000, 860, false);
            //$newimage = resize_image($animatedGifExtractedFrame, 1920, 1080, false);
            imagepng($newimage, $animatedGifExtractedFrame);
        }
    }
}
function resize_image($file, $w, $h, $crop = false)
{
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width - ($width * abs($r - $w / $h)));
        } else {
            $height = ceil($height - ($height * abs($r - $w / $h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w / $h > $r) {
            $newwidth = $h * $r;
            $newheight = $h;
        } else {
            $newheight = $w / $r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefrompng($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);

    $transparentBackground = imagecolorallocatealpha($dst, 255, 255, 255, 127);

    imagealphablending($dst, false);
    imagesavealpha($dst, true);

    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}
exit;






$snaps = glob('images/backgrounds/animated/kung-fu-panda/*.jpg');
$letterArray = array();
foreach ($snaps as $snap) {
    $im  = imagecreatefromjpeg($snap);
    $snap = str_replace(".jpg", ".png", $snap);
    imagepng($im, $snap);
    continue;
    }
    exit;
    $images = 'images/animated_snaps_categorized/flower';
$images = glob($images . '/*.gif');
foreach ($images as $_filename) {
    $directory = str_replace(".gif", "", $_filename);
    if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
    }
    //$animatedGifExtractedFrames = glob($directory . '/*.png');
    //if (sizeof($animatedGifExtractedFrames) == 0) {
    shell_exec("ffmpeg -i " . $_filename . " -vsync 0 " . $directory .
        "/frame%d.png");
    $animatedGifExtractedFrames = glob($directory . '/*.png');
    foreach ($animatedGifExtractedFrames as $animatedGifExtractedFrame) {
        $_backgroundColour = '0,0,0';
        $_img = imagecreatefrompng($animatedGifExtractedFrame);
        $_backgroundColours = explode(',', $_backgroundColour);
        $_removeColour = imagecolorallocate($_img, (int)$_backgroundColours[0], (int)$_backgroundColours[1],
            (int)$_backgroundColours[2]);
        imagecolortransparent($_img, $_removeColour);
        imagesavealpha($_img, true);
        $_transColor = imagecolorallocatealpha($_img, 0, 0, 0, 127);
        imagefill($_img, 0, 0, $_transColor);
        imagepng($_img, $animatedGifExtractedFrame);
        $newimage = resize_image($animatedGifExtractedFrame, 750, 645, false);
        imagepng($newimage, $animatedGifExtractedFrame);
    }
    //}
}
$snap_folders = array_filter(glob('images/animated_snaps_categorized/*'), 'is_dir');
$finalString = "array(";
foreach ($snap_folders as $folder) {
$folderName = str_replace("images/animated_snaps_categorized/", "", $folder);
$snaps = glob(''.$folder.'/*.gif');
$letterArray = array();
foreach ($snaps as $snap) {
$snap = str_replace("images/animated_snaps_categorized/".$folderName."/", "", $snap);
$snap = str_replace(".gif", "", $snap);
if (isset($letterArray[$folderName])) {
array_push($letterArray[$folderName], $snap);
} else {
$letterArray[$folderName] = array($snap);
}
}

$lastKey = '';

foreach ($letterArray as $key => $value) {
//if ($lastKey == '' || $lastKey != $key) {
$finalString .= "'" . $key . "' => array(";
foreach ($value as $val) {
$finalString .= "'" . $val . "',";
}
$finalString .= "),<br/>";
}

}
$finalString .= ")";
$finalString = str_replace(',)', ')', $finalString);
echo $finalString;
exit;
$snap_folders = array_filter(glob('images/animated_snaps_categorized/*'), 'is_dir');
$finalString = "array(";
foreach ($snap_folders as $folder) {
$folderName = str_replace("images/animated_snaps_categorized/", "", $folder);
$snaps = glob(''.$folder.'/*.gif');
$letterArray = array();
foreach ($snaps as $snap) {
$snap = str_replace("images/animated_snaps_categorized/".$folderName."/", "", $snap);
$snap = str_replace(".gif", "", $snap);
if (isset($letterArray[$folderName])) {
array_push($letterArray[$folderName], $snap);
} else {
$letterArray[$folderName] = array($snap);
}
}

$lastKey = '';

foreach ($letterArray as $key => $value) {
//if ($lastKey == '' || $lastKey != $key) {
$finalString .= "'" . $key . "' => array(";
foreach ($value as $val) {
$finalString .= "'" . $val . "',";
}
$finalString .= "),<br/>";
}

}
$finalString .= ")";
$finalString = str_replace(',)', ')', $finalString);
echo $finalString;
exit;
?>