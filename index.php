<?php

use sosat1101\openSubtitles\OpenSubtitlesImp;

require 'vendor/autoload.php';
$openSubtitlesImp = new OpenSubtitlesImp();
$login = $openSubtitlesImp->login("sosat1101", "dfcmbb977");
var_dump($login);
