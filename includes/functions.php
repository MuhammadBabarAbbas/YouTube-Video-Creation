<?php


function createVideoFile($alphabet, $wordText)
{
    //'images/SlideImages/Slide' . $alphabet . '.png', 'audios/' . $alphabet .
    //$wordText . '.mp3');
    $imagePath = 'images/SlideImages/Slide' . $alphabet . '.png';
    $audioPath = 'audios/' . $alphabet . '.mp3';
    $mix = "ffmpeg -y -r 30 -s 1920x1080 -loop 1 -i " . $imagePath . " -i " . $audioPath .
        " -vcodec libx264 -crf 25  -pix_fmt yuv420p -shortest videos/" . $alphabet .
        ".mp4 2>&1";
    shell_exec($mix);
    $data = "file '" . $alphabet . ".mp4'" . PHP_EOL;
    $fp = fopen('videos/fileList.txt', 'a');
    fwrite($fp, $data);
}

function getRGBColor()
{
    $rgbColor = array();

    //Create a loop.
    foreach (array(
        'r',
        'g',
        'b') as $color) {
        //Generate a random number between 0 and 255.
        $rgbColor[$color] = mt_rand(0, 255);
    }
    return $rgbColor;
}

function createTextImage($text, $font, $fontSize, $rgbColor, $shadowColorSelection)
{
    $qFactor = 0;
    if ($text == 'Q' || strpos($text, 'Q') > -1) {
        $qFactor = 10;
    }
    $im = imagecreatetruecolor($fontSize + 100, $fontSize + 100 + $qFactor);
    $directory = 'alphabets';
    if (strlen($text) > 1) {
        $im = imagecreatetruecolor(($fontSize * strlen($text) * 0.7) + 150, $fontSize +
            200 + $qFactor);
        $directory = 'words';
    }
    $imageX = imagesx($im);
    $imageY = imagesy($im);
    imagealphablending($im, false);
    // Creating Transparent Background
    $transparentBackground = imagecolorallocatealpha($im, 255, 255, 255, 127);
    $transparency = imagecolorallocatealpha($im, 0, 0, 0, 127);
    //$transparentBackground = imagecolorallocate($im, 255, 255, 255);
    $shadowColorsPallette = array(
        128,
        255,
        0);
    $shadowColor = imagecolorallocate($im, $shadowColorsPallette[$shadowColorSelection],
        $shadowColorsPallette[$shadowColorSelection], $shadowColorsPallette[$shadowColorSelection]);
    $textColor = imagecolorallocate($im, $rgbColor['r'], $rgbColor['g'], $rgbColor['b']);

    imagefilledrectangle($im, 0, 0, $imageX, $imageY, $transparentBackground);

    //imagefill($im, 0, 0, $transparency);
    imagesavealpha($im, true);

    $textDim = imagettfbbox($fontSize, 0, $font, $text);
    $textX = $textDim[2] - $textDim[0];
    $textY = $textDim[7] - $textDim[1];

    $text_posX = ($imageX / 2) - ($textX / 2);
    $text_posY = ($imageY / 2) - ($textY / 2);

    // Add some shadow to the alphabet
    imagettftext($im, $fontSize, 0, $text_posX + 10, $text_posY + 10, $shadowColor,
        $font, $text);

    // Add the alphabet
    imagettftext($im, $fontSize, 0, $text_posX, $text_posY, $textColor, $font, $text);

    // Using imagepng() results in clearer text compared with imagejpeg()
    imagepng($im, 'images/' . $directory . '/' . $text . '.png');
    imagedestroy($im);
}

function createAudioFile($alphabet, $text)
{
    // Yes French is a beautiful language.
    $lang = "en-IN";

    // MP3 filename generated using MD5 hash
    // Added things to prevent bug if you want same sentence in two different languages
    $file = md5($lang . "?" . urlencode($text));

    // Save MP3 file in folder with .mp3 extension
    $file = "audios/" . $alphabet . ".mp3";


    // Check folder exists, if not create it, else verify CHMOD
    if (!is_dir("audios/"))
        mkdir("audios/");
    else
        if (substr(sprintf('%o', fileperms('audios/')), -4) != "0777")
            chmod("audios/", 0777);

    $text = $alphabet . " for " . $text;
    // If MP3 file exists do not create new request
    //if (!file_exists($file)) {
    // Download content
    //$mp3 = file_get_contents('http://translate.google.com/translate_tts?ie=UTF-8&q=' .
    //  urlencode($text) . '&tl=' . $lang . '&total=1&idx=0&textlen=5&prev=input');
    $mp3 = file_get_contents('https://translate.google.com/translate_tts?ie=UTF-8&client=gtx&q=' .
        urlencode($text) . '&tl=' . $lang . '&total=1&idx=0&textlen=5&prev=input');

    //
    file_put_contents($file, $mp3);
    //}
    $audiosGapFiles = array('silence', 'laughter');
    $data = "file '" . $alphabet . ".mp3'" . PHP_EOL;
    $data .= "file 'silence.mp3'" . PHP_EOL;
    //$data .= "file '" . $audiosGapFiles[array_rand($audiosGapFiles)] . ".mp3'" . PHP_EOL;
    $fp = fopen('audios/fileList.txt', 'a');
    fwrite($fp, $data);
}
?>