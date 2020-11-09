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
     * @var  vfsStreamDirectory
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

        // Shelve the test file a couple of times

    }

    public function testDefaultSortOrder(): void
    {
    }
}
