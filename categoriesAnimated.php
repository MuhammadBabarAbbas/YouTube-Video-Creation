<?php
ini_set('max_execution_time', 0);
include ('includes/animated_vocabulary_categories.php');

$font_files = glob('fonts\*.ttf');


$categories = array_keys($vocabulary);
$usedWords = array();
$usedCategories = array('tool');

foreach ($categories as $category) {
    if (!in_array($category, $usedCategories)) {
        continue;
    }
    $backgrounds = glob('images/backgrounds/animated/' . $category . '/*.gif');
    /*foreach($vocabulary[$category] as $worddd){
    if (!isset($usedWords[$category])) {
    $usedWords[$category] = array($worddd);
    } else {
    array_push($usedWords[$category], $worddd);
    }
    }*/

    $usedWords = array();
    $title = ucwords($category) . " Names for Kids, " . ucwords($category) .
        " Flashcards, Learning ABC, Phonics and Sounds";
    if ($category == 'time') {
        $title = "Learn to Read " . ucwords($category) .
            " for Kids, Reading Time for Kids, " . ucwords($category) .
            " Flashcards, Learning ABC, Phonics and Sounds";
    }
    $randomIndex = null;
    $range = 1;
    if (sizeof($vocabulary[$category]) > 26) {
        $range = ceil(sizeof($vocabulary[$category]) / 26);
    }
    foreach (range(1, $range) as $counter) {

        if (!file_exists('audios/' . $category)) {
            mkdir('audios/' . $category, 0777, true);
        }

        if (!file_exists('videos/' . $category)) {
            mkdir('videos/' . $category, 0777, true);
        }
        
        if (file_exists("videos/" . $category . "/fileList.txt")) {
            file_put_contents("videos/" . $category . "/fileList.txt", "");
        }
        if (file_exists("audios/fileList.txt")) {
            unlink("audios/fileList.txt");
        }

        $startAudioText = "Let's learn the " . $category .
            " Names, with the help of Flashcards";

        if ($category == 'time') {
            $startAudioText = "Let's learn how to read " . $category .
                " Names, with the help of Flashcards";
        }

        copy('videos/LearningforKids.mp4', "videos/" . $category .
            "/LearningforKids.mp4");
        copy('videos/ThanksforLanding.mp4', "videos/" . $category .
            "/ThanksforLanding.mp4");
        copy('videos/Subscribetoourchannel.mp4', "videos/" . $category .
            "/Subscribetoourchannel.mp4");

        /*createAudioFile($category, $startAudioText, 0);
        $data = "file 'silence.mp3'" . PHP_EOL;
        $fp = fopen('audios/fileList.txt', 'a');
        fwrite($fp, $data);
        $data = "file 'LearningforKids.mp4'" . PHP_EOL;
        $fp = fopen('videos/' . $category . '/fileList.txt', 'a');
        fwrite($fp, $data);

        $landingText = "Thanks for Landing here, Subscribe to our channel to learn with fun.";
        createAudioFile($category, $landingText, 1);
        $data = "file 'silence.mp3'" . PHP_EOL;
        $fp = fopen('audios/fileList.txt', 'a');
        fwrite($fp, $data);
        $data = "file 'ThanksforLanding.mp4'" . PHP_EOL;
        $fp = fopen('videos/' . $category . '/fileList.txt', 'a');
        fwrite($fp, $data);*/
        
        foreach (range(1, 26) as $i) {

            array_map('unlink', array_filter((array )glob("images/imageProcessingDesk/backgroundFrames/" .
                $category . "/*.png")));
            //array_map( 'unlink', array_filter((array) glob("images/imageProcessingDesk/backgroundFrames/*.gif") ) );
            array_map('unlink', array_filter((array )glob("images/imageProcessingDesk/processedBackgroundFrames/" .
                $category . "/*.png")));

            $font = __dir__ . "\\" . $font_files[array_rand($font_files)];
            if (!empty($vocabulary[$category]) && isset($vocabulary[$category])) {
                $randomIndex = array_rand($vocabulary[$category]);
                if ($category == 'number' || $category == 'time') {
                    $randomIndex = $i - 1;
                }
                //$randomIndex = $i - 1;
                $actualword = $vocabulary[$category][$randomIndex];
                if (!isset($usedWords[$category])) {
                    $usedWords[$category] = array($actualword);
                } else {
                    array_push($usedWords[$category], $actualword);
                }
                unset($vocabulary[$category][$randomIndex]);
            } else {
                break;
                if (isset($usedWords[$category])) {
                    $randomIndex = array_rand($usedWords[$category]);
                    $actualword = $usedWords[$category][$randomIndex];
                }
            }
            if ($i == 1) {
                $firstWord = $actualword;
            } else
                if ($i == 2) {
                    $secondWord = $actualword;
                } else
                    if ($i == 3) {
                        $thirdWord = $actualword;
                    } else
                        if ($i == 4) {
                            $fourthWord = $actualword;
                        } else
                            if ($i == 5) {
                                $fifthWord = $actualword;
                            } else
                                if ($i == 6) {
                                    $sixthWord = $actualword;
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
            $firstLetter = strtoupper($word[0]);
            if (!file_exists('images/categories/' . $category)) {
                mkdir('images/categories/' . $category, 0777, true);
            }
            if (!file_exists('images/alphabets/' . $firstLetter)) {
                mkdir('images/alphabets/' . $firstLetter, 0777, true);
            }
            createTextImage($firstLetter, $font, 400, $rgbColor, $shadowColorSelection, $i);
            $shadowColorSelection = array_rand($shadowColorsPallette);
            //Selected Random color for letters
            $rgbColor = getRGBColor();
            createTextImage($word, $font, 200, $rgbColor, $shadowColorSelection, $i);

            // Load the stamp and the photo to apply the watermark to
            //$background = imagecreatefrompng($backgrounds[array_rand($backgrounds)]);
            //$background = imagecreatefrompng('images/1.png');


            $letter = imagecreatefrompng('images/alphabets/' . $firstLetter . '/' . $i .
                '.png');
            $shapeWord = str_replace(" ", "-", $word);
            $shapeWord = strtolower($shapeWord);
            $word = imagecreatefrompng('images/words/' . $i . '.png');


            $image_path = $backgrounds[array_rand($backgrounds)];
            $backgroundFramesName = basename($image_path);
            $backgroundFramesDirectroy = str_replace(".gif", "",
                "images/imageProcessingDesk/backgroundFrames/" . $category . "/" . $backgroundFramesName);

            if (!file_exists("images/imageProcessingDesk/backgroundFrames/" . $category)) {
                mkdir("images/imageProcessingDesk/backgroundFrames/" . $category, 0777, true);
            }

            if (!file_exists($backgroundFramesDirectroy)) {
                mkdir($backgroundFramesDirectroy, 0777, true);
            }
            $backgroundExtractedFrames = glob($backgroundFramesDirectroy . '/*.png');
            if (file_exists($image_path) && sizeof($backgroundExtractedFrames) == 0) {
                shell_exec("ffmpeg -i " . $image_path . " -vsync 0 " . $backgroundFramesDirectroy .
                    "/frame%d.png");
                $backgroundExtractedFrames = glob($backgroundFramesDirectroy . '/*.png');
                foreach ($backgroundExtractedFrames as $backgroundExtractedFrame) {
                    copyTransparent($backgroundExtractedFrame, $backgroundExtractedFrame);
                    $newimage = resize_image($backgroundExtractedFrame, 1920, 1080, false);
                    imagepng($newimage, $backgroundExtractedFrame);
                }
            }
            $backgroundExtractedFrames = glob($backgroundFramesDirectroy . '/*.png');
            for ($j = 1; $j <= sizeof($backgroundExtractedFrames); $j++) {
                $background = imagecreatefrompng($backgroundFramesDirectroy . '/frame' . ($j) .
                    '.png');

                $animatedGifPath = 'images/animated_snaps_categorized/' . $category . '/' . $actualword .
                    '.gif';
                $animatedGifName = basename($animatedGifPath);

                $animatedGifFramesDirectroy = str_replace(".gif", "",
                    "images/animated_snaps_categorized/" . $category . "/" . $animatedGifName);
                if (!file_exists($animatedGifFramesDirectroy)) {
                    mkdir($animatedGifFramesDirectroy, 0777, true);
                }
                $animatedGifExtractedFrames = glob($animatedGifFramesDirectroy . '/*.png');
                if (file_exists($animatedGifPath) && sizeof($animatedGifExtractedFrames) == 0) {
                    shell_exec("ffmpeg -i " . $animatedGifPath . " -vsync 0 " . $animatedGifFramesDirectroy .
                        "/frame%d.png");
                    $animatedGifExtractedFrames = glob($animatedGifFramesDirectroy . '/*.png');
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
                }
                $animatedGifExtractedFrames = glob($animatedGifFramesDirectroy . '/*.png');
                $animatedFramesCount = sizeof($animatedGifExtractedFrames);
                $animatedFrameIndex = 0;
                if ($animatedFramesCount > 0) {
                    $animatedFrameIndex = $j <= $animatedFramesCount ? $j : ($j % $animatedFramesCount);
                }
                $animatedFrameIndex = $animatedFrameIndex == 0 ? 1 : $animatedFrameIndex;
                $shape = imagecreatefrompng($animatedGifFramesDirectroy . '/frame' . ($animatedFrameIndex) .
                    '.png');


                //$shape = imagecreatefrompng('images/snaps_categorized/' . $category . '/' . $actualword . '.png');

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
                $sx = imagesx($shape);
                $sy = imagesy($shape);
                $dst_x = (imagesx($background) - $sx) - (((imagesx($background) / 2) - $sx) / 2);
                // Merge the stamp onto our photo with an opacity of 50%
                imagecopy($background, $shape, $dst_x, imagesy($background) - $sy - $marge_bottom,
                    0, 0, imagesx($shape), imagesy($shape));


                // Set the margins for the stamp and get the height/width of the stamp image
                $marge_bottom = 50;
                $sx = imagesx($word);
                $sy = imagesy($word);
                $marge_right = (imagesx($background) - $sx) / 2;

                // Merge the stamp onto our photo with an opacity of 50%
                imagecopy($background, $word, imagesx($background) - $sx - $marge_right, imagesy
                    ($background) - $sy - $marge_bottom, 0, 0, imagesx($word), imagesy($word));

                if (!file_exists('images/imageProcessingDesk/processedBackgroundFrames/' . $category)) {
                    mkdir('images/imageProcessingDesk/processedBackgroundFrames/' . $category, 0777, true);
                }

                // Save the image to file and free memory
                imagepng($background, 'images/imageProcessingDesk/processedBackgroundFrames/' .
                    $category . '/SlideFrame' . $j . '.png');
                imagedestroy($background);
            }

            if (!file_exists("images/AnimatedSlideImages/" . $category)) {
                mkdir("images/AnimatedSlideImages/" . $category, 0777, true);
            }

            shell_exec("ffmpeg -i images/imageProcessingDesk/processedBackgroundFrames/" . $category .
                "/SlideFrame%d.png -y -vf palettegen -r 15 images/imageProcessingDesk/processedBackgroundFrames/" .
                $category . "/palette.png");
            shell_exec("ffmpeg -thread_queue_size 1024 -i images/imageProcessingDesk/processedBackgroundFrames/" .
                $category . "/SlideFrame%d.png -y -i images/imageProcessingDesk/processedBackgroundFrames/" .
                $category . "/palette.png -r 15 -lavfi paletteuse images/AnimatedSlideImages/" .
                $category . "/Slide" . $i . ".gif");
            createAudioFile($category, $wordText, $i);
            createVideoFile($category, $wordText, $i);

        }
        $subscribeText = "Thanks for Watching! Subscribe to our channel to learn with fun.";
        createAudioFile($category, $subscribeText, -1);
        $data = "file 'Subscribetoourchannel.mp4'" . PHP_EOL;
        $fp = fopen('videos/' . $category . '/fileList.txt', 'a');
        fwrite($fp, $data);

        $time = microtime(true);
        $musics = glob('music/*.mp3');
        $videoTitle = str_replace(" ", "-", $title);
        
        unlink("createdVideos/audio.mp3");
        unlink("createdVideos/video.ts");

        shell_exec("ffmpeg -f concat -i audios/fileList.txt -c copy createdVideos/audio.mp3");

        //shell_exec("ffmpeg -f concat -i videos/" . $category . "/fileList.txt -map 0:0 -map 0:1 -an -c copy createdVideos/video.ts");
        shell_exec("ffmpeg -y -r 15 -safe 0 -f concat -i videos/" . $category .
            "/fileList.txt -map 0:0 -map 0:1 -an -c:v libx264 -profile:v high -pix_fmt yuv420p createdVideos/video.ts");

        shell_exec("ffmpeg -i createdVideos/video.ts -i createdVideos/audio.mp3 -c copy -map 0:0 -map 1:0 -map_metadata -1 -movflags +faststart -strict -3 -f mp4  createdVideos/" .
            $videoTitle . $time . "_temp.mp4");

        shell_exec("ffmpeg -i createdVideos/" . $videoTitle . $time . "_temp.mp4 -stream_loop -1 -i " .
            $musics[array_rand($musics)] . " -filter_complex \"[0:a]apad[main];  [1:a]volume=-10dB,apad[A]; [main][A]amerge[out]\" -c:v libx264 -c:a aac -map 0:v -map \"[out]\" -preset ultrafast -threads 0 -profile:v baseline -ac 2 -vcodec libx264 -crf 25 -pix_fmt yuv420p -shortest -y createdVideos/" .
            $videoTitle . $time . ".mp4");
        unlink("createdVideos/" . $videoTitle . $time . "_temp.mp4");

        $firstWordTitle = str_replace("_", "", $firstWord);
        $firstWordTitle = str_replace("-", " ", $firstWordTitle);
        $secondWordTitle = str_replace("_", "", $secondWord);
        $secondWordTitle = str_replace("-", " ", $secondWordTitle);
        $thirdWordTitle = str_replace("_", "", $thirdWord);
        $thirdWordTitle = str_replace("-", " ", $thirdWordTitle);

        $titleCategory = ucwords(str_replace("-", " ", $category));
        $title = ucwords($titleCategory) . " Names for Kids, " . ucwords($titleCategory) .
            " Flashcards, " . ucwords($firstWordTitle) . ", " . ucwords($secondWordTitle) .
            ", " . ucwords($thirdWordTitle) . ", Learning ABC, Phonics and Sounds";
        if ($category == 'time') {
            $title = "Learn to Read " . ucwords($category) .
                " for Kids, Reading Time for Kids, " . ucwords($category) .
                " Flashcards, Learning ABC, Phonics and Sounds";
        }
        rename("createdVideos/" . $videoTitle . $time . ".mp4", "createdVideos/" . $title .
            $time . ".mp4");


        $thumbnail = imagecreatefrompng("images/thumbnail.png");
        $thumbnail = imagescale($thumbnail, 1280, 720);

        // Set the margins for the stamp and get the height/width of the stamp image

        $background_width = imagesx($thumbnail);
        $background_height = imagesy($thumbnail);

        $newimage = resize_image('images/animated_snaps_categorized/' . $category . '/' .
            $firstWord . '/frame1.png', 250, 215, false);
        imagepng($newimage, 'images/thumbnail_images/1.png');
        $newimage = resize_image('images/animated_snaps_categorized/' . $category . '/' .
            $secondWord . '/frame1.png', 250, 215, false);
        imagepng($newimage, 'images/thumbnail_images/2.png');
        $newimage = resize_image('images/animated_snaps_categorized/' . $category . '/' .
            $thirdWord . '/frame1.png', 250, 215, false);
        imagepng($newimage, 'images/thumbnail_images/3.png');
        $newimage = resize_image('images/animated_snaps_categorized/' . $category . '/' .
            $fourthWord . '/frame1.png', 250, 215, false);
        imagepng($newimage, 'images/thumbnail_images/4.png');
        $newimage = resize_image('images/animated_snaps_categorized/' . $category . '/' .
            $fifthWord . '/frame1.png', 250, 215, false);
        imagepng($newimage, 'images/thumbnail_images/5.png');
        $newimage = resize_image('images/animated_snaps_categorized/' . $category . '/' .
            $sixthWord . '/frame1.png', 250, 215, false);
        imagepng($newimage, 'images/thumbnail_images/6.png');

        $shape = imagecreatefrompng('images/thumbnail_images/1.png');
        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = ($background_width / 4) / 2.5;
        $marge_bottom = $background_height / 2;

        $sx = imagesx($shape) * 1.5;
        $sy = imagesy($shape);

        // Merge the stamp onto our photo with an opacity of 50%
        imagecopy($thumbnail, $shape, imagesx($thumbnail) - $sx - $marge_right, imagesy
            ($thumbnail) - $sy - $marge_bottom, 0, 0, imagesx($shape), imagesy($shape));

        $shape = imagecreatefrompng('images/thumbnail_images/2.png');
        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = ($background_width / 4) / 2.5;
        $marge_bottom = $background_height / 5;

        $sx = imagesx($shape) * 1.5;
        $sy = imagesy($shape);

        // Merge the stamp onto our photo with an opacity of 50%
        imagecopy($thumbnail, $shape, imagesx($thumbnail) - $sx - $marge_right, imagesy
            ($thumbnail) - $sy - $marge_bottom, 0, 0, imagesx($shape), imagesy($shape));

        $shape = imagecreatefrompng('images/thumbnail_images/3.png');
        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = ($background_width / 4) * 1.15;
        $marge_bottom = $background_height / 2;

        $sx = imagesx($shape) * 1.5;
        $sy = imagesy($shape);

        // Merge the stamp onto our photo with an opacity of 50%
        imagecopy($thumbnail, $shape, imagesx($thumbnail) - $sx - $marge_right, imagesy
            ($thumbnail) - $sy - $marge_bottom, 0, 0, imagesx($shape), imagesy($shape));

        $shape = imagecreatefrompng('images/thumbnail_images/4.png');
        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = ($background_width / 4) * 1.15;
        $marge_bottom = $background_height / 5;

        $sx = imagesx($shape) * 1.5;
        $sy = imagesy($shape);

        // Merge the stamp onto our photo with an opacity of 50%
        imagecopy($thumbnail, $shape, imagesx($thumbnail) - $sx - $marge_right, imagesy
            ($thumbnail) - $sy - $marge_bottom, 0, 0, imagesx($shape), imagesy($shape));

        $shape = imagecreatefrompng('images/thumbnail_images/5.png');
        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = ($background_width / 4) * 2;
        $marge_bottom = $background_height / 2;

        $sx = imagesx($shape) * 1.5;
        $sy = imagesy($shape);

        // Merge the stamp onto our photo with an opacity of 50%
        imagecopy($thumbnail, $shape, imagesx($thumbnail) - $sx - $marge_right, imagesy
            ($thumbnail) - $sy - $marge_bottom, 0, 0, imagesx($shape), imagesy($shape));

        $shape = imagecreatefrompng('images/thumbnail_images/6.png');
        // Set the margins for the stamp and get the height/width of the stamp image
        $marge_right = ($background_width / 4) * 2;
        $marge_bottom = $background_height / 5;

        $sx = imagesx($shape) * 1.5;
        $sy = imagesy($shape);

        // Merge the stamp onto our photo with an opacity of 50%
        imagecopy($thumbnail, $shape, imagesx($thumbnail) - $sx - $marge_right, imagesy
            ($thumbnail) - $sy - $marge_bottom, 0, 0, imagesx($shape), imagesy($shape));

        /*$shadowColorsPallette = array(
        128,
        255,
        0);
        $alphabet = 'A';
        $shadowColorSelection = array_rand($shadowColorsPallette);*/
        $fontSize = 80;
        if (strlen($titleCategory) >= 9) {
            $fontSize = 70;
        }
        $thumbnailText = $titleCategory . ' Names for Kids';
        if ($titleCategory == 'Time') {
            $thumbnailText = 'Learning ' . $titleCategory . ' for Kids';
        }
        if (stripos($titleCategory, 'things') > -1) {
            $thumbnailText = $titleCategory . ' for Kids';
            $fontSize = 70;
        }
        $basicColors = array(0, 255);
        $rgbColor = array(
            'r' => $basicColors[array_rand($basicColors)],
            'g' => $basicColors[array_rand($basicColors)],
            'b' => $basicColors[array_rand($basicColors)]);
        $font = __dir__ . "\\fonts\\arialbd.ttf";

        createTextImage($thumbnailText, $font, $fontSize, $rgbColor, $shadowColorSelection,
            0);
        $thumbnailTextImage = imagecreatefrompng('images/words/0.png');

        $marge_bottom = 500;
        $sx = imagesx($thumbnailTextImage);
        $sy = imagesy($thumbnailTextImage);
        $marge_right = (imagesx($thumbnail) - $sx) / 2;

        // Merge the stamp onto our photo with an opacity of 50%
        imagecopy($thumbnail, $thumbnailTextImage, imagesx($thumbnail) - $sx - $marge_right,
            imagesy($thumbnail) - $sy - $marge_bottom, 0, 0, imagesx($thumbnailTextImage),
            imagesy($thumbnailTextImage));

        imagejpeg($thumbnail, 'videoThumbnails/' . $title . $time . '.jpg');


    }
}

function createVideoFile($category, $wordText, $index)
{
    $imagePath = 'images/AnimatedSlideImages/' . $category . '/Slide' . $index .
        '.gif';
    $audioPath = 'audios/' . $category . '/' . $index . '.mp3';
    /*$mix = "ffmpeg -y -r 30 -s 1920x1080 -loop 1 -i " . $imagePath . " -i " . $audioPath .
    " -vcodec libx264 -crf 25  -pix_fmt yuv420p -shortest videos/" . $category . "/" .
    $index . ".mp4 2>&1";*/
    $mix = "ffmpeg -y -i " . $audioPath . " -ignore_loop 0 -i " . $imagePath .
        " -vf \"scale=trunc(iw/2)*2:trunc(ih/2)*2\" -shortest -strict -2 -c:v libx264 -threads 4 -c:a aac -b:a 192k -pix_fmt yuv420p -shortest videos/" .
        $category . "/" . $index . ".mp4 2>&1";
    shell_exec($mix);
    $data = "file '" . $index . ".mp4'" . PHP_EOL;
    $fp = fopen('videos/' . $category . '/fileList.txt', 'a');
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

function createAudioFile($category, $text, $index)
{
    // Yes French is a beautiful language.
    $lang = "en-IN";

    // MP3 filename generated using MD5 hash
    // Added things to prevent bug if you want same sentence in two different languages
    $file = md5($lang . "?" . urlencode($text));

    // Save MP3 file in folder with .mp3 extension
    $file = "audios/" . $category . "/" . $index . ".mp3";


    // Check folder exists, if not create it, else verify CHMOD
    if (!is_dir("audios/"))
        mkdir("audios/");
    else
        if (substr(sprintf('%o', fileperms('audios/')), -4) != "0777")
            chmod("audios/", 0777);
    $marketingTextArray = array(
       // 0,
       // 1,
        -1);
    if (in_array($index, $marketingTextArray)) {
        $text = $text;
    } else {
        $text = $text . ", " . strtoupper($text[0]) . " for " . $text;
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
    $data = "file '" . $category . "/" . $index . ".mp3'" . PHP_EOL;
    ///if (!(strpos($text, "Thanks for Landing here") > -1) && $index != 27) {
    $data .= "file 'silence.mp3'" . PHP_EOL;
    $data .= "file 'silence.mp3'" . PHP_EOL;
    //}
    //$data .= "file '" . $audiosGapFiles[array_rand($audiosGapFiles)] . ".mp3'" . PHP_EOL;
    $fp = fopen('audios/fileList.txt', 'a');
    fwrite($fp, $data);
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


function copyTransparent($src, $output)
{
    $dimensions = getimagesize($src);
    $x = $dimensions[0];
    $y = $dimensions[1];
    $im = imagecreatetruecolor($x, $y);
    $src_ = imagecreatefrompng($src);
    // Prepare alpha channel for transparent background
    $alpha_channel = imagecolorallocatealpha($im, 0, 0, 0, 127);
    imagecolortransparent($im, $alpha_channel);
    // Fill image
    imagefill($im, 0, 0, $alpha_channel);
    // Copy from other
    imagecopy($im, $src_, 0, 0, 0, 0, $x, $y);
    // Save transparency
    imagesavealpha($im, true);
    // Save PNG
    imagepng($im, $output, 9);
    imagedestroy($im);
}

?>