<?php

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream,
    org\bovigo\vfs\vfsStreamDirectory;

use cstuder\TemporalShelf\TemporalShelf;
use cstuder\TemporalShelf\Options;

/**
 * Testing file shelving sort ordering
 */
class TemporalShelfSortOrderTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;

    const SHELVE_DIRECTORY = 'shelf';
    const TESTFILENAME = 'testFile.txt';
    const TESTFILECONTENT = 'new file';

    public function setUp(): void
    {
        $this->root = vfsStream::setup('testDirectory');
        mkdir($this->root->url() . DIRECTORY_SEPARATOR . self::SHELVE_DIRECTORY);
        file_put_contents($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, self::TESTFILECONTENT);
    }

    public function testDefaultSortOrder(): void
    {
        // Shelve the test file a couple of times
        $shelf = new TemporalShelf($this->root->url() . DIRECTORY_SEPARATOR . self::SHELVE_DIRECTORY);

        $fileTwo = $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, 2);
        $fileOne = $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, 1);
        $fileThree = $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, 10000000);

        // Get them back
        $allFiles = $shelf->findAllShelvedFiles();

        $this->assertEquals(3, count($allFiles));
        $this->assertEquals($fileOne, $allFiles[0]);
        $this->assertEquals($fileThree, $allFiles[2]);
    }

    public function testSortOrderAscending(): void
    {
        // Shelve the test file a couple of times
        $shelf = new TemporalShelf($this->root->url() . DIRECTORY_SEPARATOR . self::SHELVE_DIRECTORY);

        $fileTwo = $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, 2);
        $fileOne = $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, 1);
        $fileThree = $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, 10000000);

        // Get them back
        $allFiles = $shelf->findAllShelvedFiles(Options\SortOrderOptions::ASCENDING);

        $this->assertEquals(3, count($allFiles));
        $this->assertEquals($fileOne, $allFiles[0]);
        $this->assertEquals($fileThree, $allFiles[2]);
    }

    public function testSortOrderDescending(): void
    {
        // Shelve the test file a couple of times
        $shelf = new TemporalShelf($this->root->url() . DIRECTORY_SEPARATOR . self::SHELVE_DIRECTORY);

        $fileTwo = $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, 2);
        $fileOne = $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, 1);
        $fileThree = $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, 10000000);

        // Get them back
        $allFiles = $shelf->findAllShelvedFiles(Options\SortOrderOptions::DESCENDING);

        $this->assertEquals(3, count($allFiles));
        $this->assertEquals($fileOne, $allFiles[2]);
        $this->assertEquals($fileThree, $allFiles[0]);
    }
}
