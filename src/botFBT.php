<?php

declare(strict_types=1);

namespace craz\botFBT;

use Facebook\Facebook;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;

class botFBT
{
    protected $config;
    protected $debug = true;
    protected $fbPost;
    protected $tgPost;
    protected $error;
    public function __construct($fbPost = true,$tgPost=true,$deferred = false)
    {
        $this->config = include $_SERVER['DOCUMENT_ROOT'] . "/config.php";
        if (!$deferred){
            if($fbPost){
                $this->fbPost = $fbPost;
            }
            if($tgPost){
                $this->tgPost = $tgPost;
            }
        }
    }

    public function send($channel = 'gresstil', $text, $picture)
    {
        if($this->fbPost){
            $this->sendToFB($channel,$text,$picture);
        }
        if ($this->tgPost){
            $this->sendToTG($channel,$text,$picture);
        }
        if($this->error['fb'] =="" && $this->error['tg'] == '' && ($this->fbPost || $this->tgPost)){
            return 'success';
        }else{
            return 'error';
        }
    }

    protected function sendToFB($channel, $text, $picture)
    {
        $config = $this->config;
        $fb = new Facebook($config['facebook']);

        $error = '';
        try {
            // Returns a `FacebookFacebookResponse` object
            $response = $fb->post(
                "/$channel/photos",
                array(
                    'message' => $text,
                    'picture' => $fb->fileToUpload($picture)
                ),
                $config['facebook']['page_marker']
            );
        } catch (FacebookExceptionsFacebookResponseException $e) {
            $this->error['fb'] .= 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (FacebookExceptionsFacebookSDKException $e) {
            $this->error['fb'] .= 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $graphNode = $response->getGraphNode();
        if($this->debug) {
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/logs/fb.log", var_export($graphNode, true), FILE_APPEND);
            if ($this->error['fb'] != '') {
                file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/logs/fb_error.log", var_export($error, true), FILE_APPEND);
            }
        }
    }

    protected function sendToTG($channel, $text, $picture)
    {
        $config = $this->config;
        $t = new Api($config['telegram']['access_token'], true);
        $t->sendPhoto(['chat_id' => "@" . $channel, 'photo' => $picture, 'caption' => $text]);
        if($this->debug) {
            file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/logs/tg.log", var_export($t->getLastResponse(), true), FILE_APPEND);
        }
    }

    static function uploadPic($picture)
    {

        $uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';

        if (!is_dir($uploaddir)) {
            mkdir($uploaddir, 0777);
        }
        if (move_uploaded_file($picture['tmp_name'], $uploaddir . basename($picture['name']))) {
            $picturePath = realpath($uploaddir . $picture['name']);
        }
        return $picturePath;
    }
    public function savePost($channel,$text,$picture,$deferred,$fbPost,$tgPost){
        $db = new DB($this->config['mysqli']);
        if ($fbPost){
            $db->createPost($channel,$text,$picture,'fb');
            if($tgPost){
                $db->createPost($channel,$text,$picture,'tg');
            }
        }elseif($tgPost){
            $db->createPost($channel,$text,$picture,'tg');
        }elseif(!deferred){
            /**TODO не явное поведение приложениеЖ сохраняем пост для отсылки в очереди, но ставим flag какой-нибудь**/
            $db->createPost($channel,$text,$picture,'tg',true);
        }

    }
}
