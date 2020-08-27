<?php

/**
 * Example usage of temporal-shelf
 */

require __DIR__ . '/../vendor/autoload.php';

$filename = realpath(__DIR__ . '/file.txt');
$targetDirectory = sys_get_temp_dir();

$shelf = new \cstuder\TemporalShelf\TemporalShelf($targetDirectory);

$shelvedFilename = $shelf->shelveFile($filename);
sleep(5);
$shelvedFilename2 = $shelf->shelveFile($filename);

var_dump($shelvedFilename, $shelvedFilename2);
