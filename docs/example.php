<?php

/**
 * Example usage of temporal-shelf
 */

require __DIR__ . '/../vendor/autoload.php';

$filename = realpath(__DIR__ . '/file.txt');
$targetDirectory = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'temporal-shelf';

$shelf = new \cstuder\TemporalShelf\TemporalShelf($targetDirectory);

// Shelve some files
$shelvedFilename = $shelf->shelveFile($filename);
sleep(5);
$shelvedFilename2 = $shelf->shelveFile($filename);

var_dump($shelvedFilename, $shelvedFilename2);

// Find all shelved files
$shelvedFiles = $shelf->findAllShelvedFiles();

var_dump($shelvedFiles);
