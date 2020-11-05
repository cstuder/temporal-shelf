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

    /**
     * Overwrite option
     * 
     * OverwriteOption::ALLOW_OVERWRITE will silently overwrite files in the target directory.
     * OverwriteOption::EXCEPTION_ON_OVERWRITE will throw an exception.
     */
    protected int $overwriteOption;

    public function __construct(string $shelfDirectory, string $directoryPattern = 'Y/m/d', string $filePrefixPattern = 'U_', string $timezone = 'UTC', int $overwriteOption = Options\OverwriteOptions::EXCEPTION_ON_OVERWRITE)
    {
        $this->shelfDirectory = $shelfDirectory;
        $this->directoryPattern = $directoryPattern;
        $this->filePrefixPattern = $filePrefixPattern;
        $this->timezone = $timezone;
        $this->overwriteOption = $overwriteOption;
    }

    /**
     * Copy a file to the shelve
     * 
     * Overwrites any existing file if it happens to have the same filename.
     * 
     * @param string $filename Full path to the file
     * @param int $timestamp Timestamp of the file
     * @return string Full path to the shelved file
     * @throws Exception on any file errors
     */
    public function shelveFile($filename, int $timestamp = null): string
    {
        // Validate filename
        if (!is_file($filename) || !is_readable($filename)) {
            throw new \Exception("Filename '{$filename}' does not reference a file or is not readable");
        }

        if (is_null($timestamp)) {
            $timestamp = time();
        }

        $date = new \DateTime("@{$timestamp}", new \DateTimeZone($this->timezone));
        $targetDirectory = $this->shelfDirectory . DIRECTORY_SEPARATOR . $date->format($this->directoryPattern);
        $targetFilename = $date->format($this->filePrefixPattern) . basename($filename);
        $shelvedFilename = $targetDirectory . DIRECTORY_SEPARATOR . $targetFilename;

        if (!file_exists($targetDirectory)) {
            $success = mkdir($targetDirectory, 0755, true);

            if (!$success) {
                throw new \Exception("Unable to create target directory '{$targetDirectory}'.");
            }
        }

        // Check overwriting
        if (file_exists($shelvedFilename)) {
            switch ($this->overwriteOption) {
                default:
                    throw new \Exception("Undefined overwrite option set: '{$this->overwriteOption}', see OverwriteOptions enum for valid values.");

                case Options\OverwriteOptions::ALLOW_OVERWRITE:
                    // Overwriting allowed, do nothing.
                    break;

                case Options\OverwriteOptions::EXCEPTION_ON_OVERWRITE:
                    throw new \Exception("Shelved file already exists, will not overwrite: '{$filename}' to '{$shelvedFilename}'.");
            }
        }

        $success = copy($filename, $shelvedFilename);

        if (!$success) {
            throw new \Exception("Unable to copy file '{$filename}' to '{$shelvedFilename}'.");
        }

        return $shelvedFilename;
    }

    public function setShelfDirectory(string $shelfDirectory): void
    {
        $this->shelfDirectory = $shelfDirectory;
    }

    public function getShelfDirectory(): string
    {
        return $this->shelfDirectory;
    }

    public function setDirectoryPattern(string $directoryPattern): void
    {
        $this->directoryPattern = $directoryPattern;
    }

    public function getDirectoryPattern(): string
    {
        return $this->directoryPattern;
    }

    public function setFilePrefixPattern(string $filePrefixPattern): void
    {
        $this->filePrefixPattern = $filePrefixPattern;
    }

    public function getFilePrefixPattern(): string
    {
        return $this->filePrefixPattern;
    }

    public function setTimezone(string $timezone): void
    {
        $this->timezone = $timezone;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function setOverwriteOption(int $overwriteOption): void
    {
        $this->overwriteOption = $overwriteOption;
    }

    public function getOverwriteOption(): int
    {
        return $this->overwriteOption;
    }
}
