<?php

if(file_exists(__DIR__. '/../../vendor/autoload.php')){
    $autoloader = include __DIR__. '/../../vendor/autoload.php';
} elseif (file_exists(__DIR__. '/../vendor/autoload.php')) {
    $autoloader = include __DIR__. '/../vendor/autoload.php';
} else {
    die("[ERROR] No vendor/autolaod.php found");
}

$autoloader->addPsr4('Fwk\\',__DIR__.'/../src');
$autoloader->addPsr4('Fwk\\Test\\',__DIR__.'/src');