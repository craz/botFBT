<?php
/**
 * Copyright (c) 2019 Alex Pavlov
 * Created: 04.03.2019
 * Author: Alex Pavlov
 * Email: cccrazzz@gmail.com
 */

namespace craz\botFBT;


class DB
{
    protected $config;
    protected $link;

    public function __construct($config)
    {
        $this->config = $config;
        $this->link = mysqli_connect($config['host'], $config["user"], $config['password'], $config['db']);
    }

    public function createPost($channel, $text, $picture, $target, $neverSend = false)
    {
        if ($neverSend) {
            $this->link->query("INSERT INTO `botFBT`.`post` (`channel`, `text`) VALUES ('gresstil', 'текст2');");
        } else {
            $file = file_get_contents($picture);
            $sql = "INSERT INTO `botFBT`.`post` (`channel`, `text`,`target`) VALUES ($channel,$text,$target);";

            $this->link->query($sql);
            file_put_contents(
                $_SERVER['DOCUMENT_ROOT']."/logs/mysql.log",
                var_export(['error'=>$this->link->error,'errorno'=>$this->link->errno],true),
                FILE_APPEND);
        }
    }
}