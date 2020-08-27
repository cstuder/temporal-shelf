<?php

namespace cstuder\TemporalShelf;

/**
 * Temporal file shelver
 */
class TemporalShelf
{
    /**
     * Target directory for the shelved files
     */
    protected string $shelfDirectory;

    /**
     * Subdirectory pattern with datetime formatting
     * 
     * @example 'Y/m/d' Yearly/monthly/daily subdirectories
     * @link https://www.php.net/manual/en/datetime.format.php
     */
    protected string $directoryPattern;

    /**
     * File prefix pattern with datetime formatting
     * 
     * If no timestamping of files is required, set this to ''.
     * 
     * @example 'U_' Prefixes timestamp
     * @link https://www.php.net/manual/en/datetime.format.php
     */
    protected string $filePrefixPattern;

    /**
     * Time zone string
     * 
     * @example 'UTC' Coordinated Universal Time
     * @link https://www.php.net/manual/en/timezones.php 
     */
    protected string $timezone;

    public function __construct(string $shelfDirectory, string $directoryPattern = '%Y/%m/%d', string $filePrefixPattern = '%s_', string $timezone = 'UTC')
    {
        $this->shelfDirectory = $shelfDirectory;
        $this->directoryPattern = $directoryPattern;
        $this->filePrefixPattern = $filePrefixPattern;
        $this->timezone = $timezone;
    }

    public function shelveFile($filename, int $timestamp = null): string
    {
        if (is_null($timestamp)) {
            $timestamp = time();
        }

        // TODO implement this
        $shelvedFilename = $filename;

        return $shelvedFilename;
    }
}
