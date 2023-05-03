<?php
ini_set('max_execution_time', 0);
include ('includes/vocabulary.php');

$font_files = glob('fonts\*.ttf');

$backgrounds = glob('images/backgrounds/*.png');

foreach (range('F', 'Z') as $alphabet) {

    $usedWords = array();
    $title = "Words starting with " . $alphabet .
        ", Alphabet Flashcards, Learning ABC, Phonics and Sounds";
    $randomIndex = null;
    $range = 1;
    if (sizeof($vocabulary[$alphabet]) > 26) {
        $range = ceil(sizeof($vocabulary[$alphabet]) / 26);
    }
    foreach (range(1, $range) as $counter) {
        if (!file_exists('audios/' . $alphabet)) {
            mkdir('audios/' . $alphabet, 0777, true);
        }

        if (!file_exists('videos/' . $alphabet)) {
            mkdir('videos/' . $alphabet, 0777, true);
        }

        if (file_exists("videos/" . $alphabet . "/fileList.txt")) {
            file_put_contents("videos/" . $alphabet . "/fileList.txt", "");
        }
        if (file_exists("audios/fileList.txt")) {
            unlink("audios/fileList.txt");
        }

        $startAudioText = "Let's learn the words, that start with " . $alphabet;
        createAudioFile($alphabet, $startAudioText, 0);

        copy('videos/LearningforKids.mp4', "videos/" . $alphabet .
            "/LearningforKids.mp4");
        copy('videos/ThanksforLanding.mp4', "videos/" . $alphabet .
            "/ThanksforLanding.mp4");
        copy('videos/Subscribetoourchannel.mp4', "videos/" . $alphabet .
            "/Subscribetoourchannel.mp4");

        $data = "file 'LearningforKids.mp4'" . PHP_EOL;
        $fp = fopen('videos/' . $alphabet . '/fileList.txt', 'a');
        fwrite($fp, $data);

        $landingText = "Thanks for Landing here, Subscribe to our channel to learn with fun.";
        createAudioFile($alphabet, $landingText, 1);
        $data = "file 'ThanksforLanding.mp4'" . PHP_EOL;
        $fp = fopen('videos/' . $alphabet . '/fileList.txt', 'a');
        fwrite($fp, $data);

        foreach (range(2, 27) as $i) {
            $font = __dir__ . "\\" . $font_files[array_rand($font_files)];
            if (!empty($vocabulary[$alphabet]) && isset($vocabulary[$alphabet])) {
                $randomIndex = array_rand($vocabulary[$alphabet]);
                $actualword = $vocabulary[$alphabet][$randomIndex];
                if (!isset($usedWords[$alphabet])) {
                    $usedWords[$alphabet] = array($actualword);
                } else {
                    array_push($usedWords[$alphabet], $actualword);
                }
                unset($vocabulary[$alphabet][$randomIndex]);
            } else {
                break;
                if (isset($usedWords[$alphabet])) {
                    $randomIndex = array_rand($usedWords[$alphabet]);
                    $actualword = $usedWords[$alphabet][$randomIndex];
                }
            }
            $word = str_replace("-", " ", $actualword);
            $word = str_replace("_", "", $word);
            $word = ucwords($word);
            $wordText = $word;
            //Selecting Letter Shadow
            $shadowColorsPallette = array(
                128,
                255,
                0);
            $shadowColorSelection = array_rand($shadowColorsPallette);
            //Selected Random color for letters
            $rgbColor = getRGBColor();
            if (!file_exists('images/alphabets/' . $alphabet)) {
                mkdir('images/alphabets/' . $alphabet, 0777, true);
            }
            createTextImage($alphabet, $font, 400, $rgbColor, $shadowColorSelection, $i);
            $shadowColorSelection = array_rand($shadowColorsPallette);
            //Selected Random color for letters
            $rgbColor = getRGBColor();
            createTextImage($word, $font, 200, $rgbColor, $shadowColorSelection, $i);


            // Load the stamp and the photo to apply the watermark to
            $background = imagecreatefrompng($backgrounds[array_rand($backgrounds)]);
            //$background = imagecreatefrompng('images/1.png');


            $letter = imagecreatefrompng('images/alphabets/' . $alphabet . '/' . $i . '.png');
            $shapeWord = str_replace(" ", "-", $word);
            $shapeWord = strtolower($shapeWord);
            $word = imagecreatefrompng('images/words/' . $i . '.png');
            $shape = imagecreatefrompng('images/snaps/' . $actualword . '.png');

            $background_width = imagesx($background);
            $background_height = imagesy($background);

            // Set the margins for the stamp and get the height/width of the stamp image
            $marge_right = $background_width / 2;
            $marge_bottom = $background_height / 3;
            $sx = imagesx($letter) * 1.5;
            $sy = imagesy($letter);

            // Merge the stamp onto our photo with an opacity of 50%
            imagecopy($background, $letter, imagesx($background) - $sx - $marge_right,
                imagesy($background) - $sy - $marge_bottom, 0, 0, imagesx($letter), imagesy($letter));

            // Set the margins for the stamp and get the height/width of the stamp image
            $marge_right = 10;
            $marge_bottom = $background_height / 3;
            $sx = imagesx($shape) * 1.5;
            $sy = imagesy($shape);

            // Merge the stamp onto our photo with an opacity of 50%
            imagecopy($background, $shape, imagesx($background) - $sx - $marge_right,
                imagesy($background) - $sy - $marge_bottom, 0, 0, imagesx($shape), imagesy($shape));


            // Set the margins for the stamp and get the height/width of the stamp image
            $marge_bottom = 50;
            $sx = imagesx($word);
            $sy = imagesy($word);
            $marge_right = (imagesx($background) - $sx) / 2;

            // Merge the stamp onto our photo with an opacity of 50%
            imagecopy($background, $word, imagesx($background) - $sx - $marge_right, imagesy
                ($background) - $sy - $marge_bottom, 0, 0, imagesx($word), imagesy($word));

            if (!file_exists('images/SlideImages/' . $alphabet)) {
                mkdir('images/SlideImages/' . $alphabet, 0777, true);
            }

            // Save the image to file and free memory
            imagepng($background, 'images/SlideImages/' . $alphabet . '/Slide' . $i . '.png');
            imagedestroy($background);

            createAudioFile($alphabet, $wordText, $i);
            createVideoFile($alphabet, $wordText, $i);

        }
        
        if ($counter > 1) {
            if (sizeof($usedWords[$alphabet]) > 26) {
                //$startup = (($counter - 2) * 26);
                $startup = 0;
                for ($c = $startup; $c < ($startup + 26); $c++) {
                    unset($usedWords[$alphabet][$c]);
                }
                array_shift($usedWords[$alphabet]);
            }
        }

        if (sizeof($usedWords[$alphabet]) < 12) {
            for ($i = 0; $i < 12; $i++) {
                $usedWords[$alphabet][sizeof($usedWords[$alphabet])] = $usedWords[$alphabet][$i];
                if (sizeof($usedWords[$alphabet]) >= 12) {
                    break;
                }
            }
        }        
        
        $firstWord = ucwords(str_replace("-", " ", $usedWords[$alphabet][0]));
        $firstWord = ucwords(str_replace("_", "", $firstWord));
        
        $secondWord = ucwords(str_replace("-", " ", $usedWords[$alphabet][1]));
        $secondWord = ucwords(str_replace("_", "", $secondWord));
        
        $thirdWord = ucwords(str_replace("-", " ", $usedWords[$alphabet][2]));
        $thirdWord = ucwords(str_replace("_", "", $thirdWord));
        $title = "Words starting with " . $alphabet . ", " . $firstWord . ", " . $secondWord .
            ", " . $thirdWord . ", Alphabet Flashcards, Learning ABC, Phonics and Sounds";
            
            
        $subscribeText = "Thanks for Watching! Subscribe to our channel to learn with fun.";
        createAudioFile($alphabet, $subscribeText, 28);
        $data = "file 'Subscribetoourchannel.mp4'" . PHP_EOL;
        $fp = fopen('videos/' . $alphabet . '/fileList.txt', 'a');
        fwrite($fp, $data);

        $time = microtime(true);
        $musics = glob('music/*.mp3');
        $videoTitle = str_replace(" ", "-", $title);

        unlink("createdVideos/audio.mp3");
        unlink("createdVideos/video.ts");

        shell_exec("ffmpeg -f concat -i audios/fileList.txt -c copy createdVideos/audio.mp3");

        shell_exec("ffmpeg -f concat -i videos/" . $alphabet .
            "/fileList.txt -map 0:0 -map 0:1 -an -c copy createdVideos/video.ts");
        shell_exec("ffmpeg -i createdVideos/video.ts -i createdVideos/audio.mp3 -c copy -map 0:0 -map 1:0 -map_metadata -1 -movflags +faststart -strict -3 -f mp4  createdVideos/" .
            $videoTitle . $time . "_temp.mp4");

        shell_exec("ffmpeg -r 30 -i createdVideos/" . $videoTitle . $time .
            "_temp.mp4 -i " . $musics[array_rand($musics)] . " -filter_complex \"[0:a]apad[main];  [1:a]volume=-10dB,apad[A]; [main][A]amerge[out]\" -c:v libx264 -c:a aac -map 0:v -map \"[out]\" -preset ultrafast -threads 0 -profile:v baseline -ac 2 -vcodec libx264 -crf 25 -pix_fmt yuv420p -shortest -y createdVideos/" .
            $videoTitle . $time . ".mp4");
        unlink("createdVideos/" . $videoTitle . $time . "_temp.mp4");
        rename("createdVideos/" . $videoTitle . $time . ".mp4", "createdVideos/" . $title .
            $time . ".mp4");
        /*$shadowColorsPallette = array(
        128,
        255,
        0);
        //$alphabet = 'C';
        $shadowColorSelection = array_rand($shadowColorsPallette);*/
        $thumbnail = imagecreatefrompng("images/thumbnail_white.png");
        $thumbnail = imagescale($thumbnail, 1280, 720);

        // Set the margins for the stamp and get the height/width of the stamp image
        $thumbnailText = 'Words Starting';
        $basicColors = array(0, 255);
        $rgbColor = array(
            'r' => $basicColors[array_rand($basicColors)],
            'g' => $basicColors[array_rand($basicColors)],
            'b' => $basicColors[array_rand($basicColors)]);
        $font = __dir__ . "\\fonts\\arialbd.ttf";

        createTextImage($thumbnailText, $font, 90, $rgbColor, $shadowColorSelection, 0);
        $thumbnailTextImage = imagecreatefrompng('images/words/0.png');

        $marge_bottom = 300;
        $sx = imagesx($thumbnailTextImage);
        $sy = imagesy($thumbnailTextImage);
        $marge_right = (imagesx($thumbnail) - $sx) / 2;

        // Merge the stamp onto our photo with an opacity of 50%
        imagecopy($thumbnail, $thumbnailTextImage, imagesx($thumbnail) - $sx - $marge_right,
            imagesy($thumbnail) - $sy - $marge_bottom, 0, 0, imagesx($thumbnailTextImage),
            imagesy($thumbnailTextImage));

        $thumbnailText = 'With ' . $alphabet;

        createTextImage($thumbnailText, $font, 90, $rgbColor, $shadowColorSelection, 0);
        $thumbnailTextImage = imagecreatefrompng('images/words/0.png');

        $marge_bottom = 150;
        $sx = imagesx($thumbnailTextImage);
        $sy = imagesy($thumbnailTextImage);
        $marge_right = (imagesx($thumbnail) - $sx) / 2;

        // Merge the stamp onto our photo with an opacity of 50%
        imagecopy($thumbnail, $thumbnailTextImage, imagesx($thumbnail) - $sx - $marge_right,
            imagesy($thumbnail) - $sy - $marge_bottom, 0, 0, imagesx($thumbnailTextImage),
            imagesy($thumbnailTextImage));

        $background_width = imagesx($thumbnail);
        $background_height = imagesy($thumbnail);

        
        $i = 1;
        foreach ($usedWords[$alphabet] as $usedWord) {
            $shape = imagecreatefrompng('images/snaps_small/' . $usedWord . '.png');
            // Set the margins for the stamp and get the height/width of the stamp image
            $sx = imagesx($shape) * 1.5;
            $sy = imagesy($shape);
            switch ($i) {
                case 1:
                    $marge_right = ($background_width / 4) / 2.5;
                    $marge_bottom = $background_height / 1.5;
                    break;
                case 2:
                    $marge_right = ($background_width / 4) / 2.5;
                    $marge_bottom = 5;
                    break;
                case 3:
                    $marge_right = ($background_width / 4) * 1.15;
                    $marge_bottom = $background_height / 1.5;
                    break;
                case 4:
                    $marge_right = ($background_width / 4) * 1.15;
                    $marge_bottom = 5;
                    break;
                case 5:
                    $marge_right = ($background_width / 4) * 2;
                    $marge_bottom = $background_height / 1.5;
                    break;
                case 6:
                    $marge_right = ($background_width / 4) * 2;
                    $marge_bottom = 5;
                    break;
                case 7:
                    $marge_right = 900;
                    $marge_bottom = $background_height / 1.5;
                    break;
                case 8:
                    $marge_right = 900;
                    $marge_bottom = $background_height / 3;
                    break;
                case 9:
                    $marge_right = 900;
                    $marge_bottom = 5;
                    break;
                case 10:
                    $marge_right = 0;
                    $marge_bottom = $background_height / 1.5;
                    $sx = imagesx($shape);
                    break;
                case 11:
                    $marge_right = 0;
                    $marge_bottom = $background_height / 3;
                    $sx = imagesx($shape);
                    break;
                case 12:
                    $marge_right = 0;
                    $marge_bottom = 5;
                    $sx = imagesx($shape);
                    break;
                case 13:
                    $marge_right = 1800;
                    $marge_bottom = 1000;
                    break;
            }
            // Merge the stamp onto our photo with an opacity of 50%
            imagecopy($thumbnail, $shape, imagesx($thumbnail) - $sx - $marge_right, imagesy
                ($thumbnail) - $sy - $marge_bottom, 0, 0, imagesx($shape), imagesy($shape));
            $i++;
        }
        imagejpeg($thumbnail, 'videoThumbnails/' . $title . $time . '.jpg');
    }
}


function createVideoFile($alphabet, $wordText, $index)
{


    $imagePath = 'images/SlideImages/' . $alphabet . '/Slide' . $index . '.png';
    $audioPath = 'audios/' . $alphabet . '/' . $index . '.mp3';
    $mix = "ffmpeg -y -r 30 -s 1920x1080 -loop 1 -i " . $imagePath . " -i " . $audioPath .
        " -vcodec libx264 -crf 25  -pix_fmt yuv420p -shortest videos/" . $alphabet . "/" .
        $index . ".mp4 2>&1";
    shell_exec($mix);
    $data = "file '" . $index . ".mp4'" . PHP_EOL;
    $fp = fopen('videos/' . $alphabet . '/fileList.txt', 'a');
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

function createTextImage($text, $font, $fontSize, $rgbColor, $shadowColorSelection,
    $index)
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
    if ($directory == 'alphabets') {
        imagepng($im, 'images/' . $directory . '/' . $text . '/' . $index . '.png');
    } else {
        imagepng($im, 'images/' . $directory . '/' . $index . '.png');
    }
    imagedestroy($im);
}

function createAudioFile($alphabet, $text, $index)
{
    // Yes French is a beautiful language.
    $lang = "en-IN";

    // MP3 filename generated using MD5 hash
    // Added things to prevent bug if you want same sentence in two different languages
    $file = md5($lang . "?" . urlencode($text));

    // Save MP3 file in folder with .mp3 extension
    $file = "audios/" . $alphabet . "/" . $index . ".mp3";


    // Check folder exists, if not create it, else verify CHMOD
    if (!is_dir("audios/"))
        mkdir("audios/");
    else
        if (substr(sprintf('%o', fileperms('audios/')), -4) != "0777")
            chmod("audios/", 0777);

    //if ($index == 0 || $index == 27) {
    $marketingTextArray = array(
        0,
        1,
        28);
    if (in_array($index, $marketingTextArray)) {
        $text = $text;
    } else {
        $text = $alphabet . " for " . $text;
    }
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
    $data = "file '" . $alphabet . "/" . $index . ".mp3'" . PHP_EOL;
    //$data .= "file 'silence.mp3'" . PHP_EOL;
    if (!(strpos($text, "Thanks for Landing here") > -1)) {
        $data .= "file 'silence.mp3'" . PHP_EOL;
    }
    //$data .= "file '" . $audiosGapFiles[array_rand($audiosGapFiles)] . ".mp3'" . PHP_EOL;
    $fp = fopen('audios/fileList.txt', 'a');
    fwrite($fp, $data);
}
?>