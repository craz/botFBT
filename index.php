<?php
/**
 * Copyright (c) 2019 Alex Pavlov
 * Created: 26.02.2019
 * Author: Alex Pavlov
 * Email: cccrazzz@gmail.com
 */
require __DIR__ . '/vendor/autoload.php';

$config = include "config.php";

$db = new \craz\botFBT\DB($config['mysqli']);

print_r($db->readPost(24));