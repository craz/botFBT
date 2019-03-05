<?php
require __DIR__ . '/vendor/autoload.php';


$picture = $_FILES['picture'];
$text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
$channel = filter_input(INPUT_POST, 'channel', FILTER_SANITIZE_STRING);
$deferred = filter_input(INPUT_POST, 'deferred', FILTER_VALIDATE_BOOLEAN);
$fbPost = filter_input(INPUT_POST, 'fbPost', FILTER_VALIDATE_BOOLEAN);
$tgPost = filter_input(INPUT_POST, 'tgPost', FILTER_VALIDATE_BOOLEAN);

$picturePath = \craz\botFBT\botFBT::uploadPic($picture);

$bot = new \craz\botFBT\botFBT($fbPost,$tgPost,$deferred);

if (!$deferred) {
    echo $bot->send($channel, $text, $picturePath);
    $bot->savePost($channel,$text,$picturePath,$deferred,$fbPost,$tgPost);
}