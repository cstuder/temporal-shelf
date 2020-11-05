<?php

use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream,
    org\bovigo\vfs\vfsStreamDirectory;

/**
 * Testing file shelving
 */
class TemporalShelfTest extends TestCase
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
    }

    public function testTemporalShelf(): void
    {
        // Minimal options
        $shelf = new \cstuder\TemporalShelf\TemporalShelf(
            $this->root->url() . DIRECTORY_SEPARATOR . self::SHELVE_DIRECTORY
        );
        $now = new \DateTime('now', new \DateTimeZone($shelf->getTimezone()));

        // Shelve file
        $shelvedFile = $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, $now->getTimestamp());

        // Check target file
        $this->assertTrue(file_exists($shelvedFile));
        $this->assertStringEqualsFile($shelvedFile, self::TESTFILECONTENT);

        // Check path
        $expectedPath = $this->root->url() . DIRECTORY_SEPARATOR . self::SHELVE_DIRECTORY . DIRECTORY_SEPARATOR;
        $expectedPath .= $now->format($shelf->getDirectoryPattern());

        $this->assertEquals($expectedPath, dirname($shelvedFile));

        // Check filename
        $expectedFilename = $now->format($shelf->getFilePrefixPattern()) . self::TESTFILENAME;

        $this->assertEquals($expectedFilename, basename($expectedFilename));
    }

    public function testOverwriteOptionDefault(): void
    {
        $this->expectException(Exception::class);

        // Minimal options
        $shelf = new \cstuder\TemporalShelf\TemporalShelf(
            $this->root->url() . DIRECTORY_SEPARATOR . self::SHELVE_DIRECTORY
        );
        $now = new \DateTime('now', new \DateTimeZone($shelf->getTimezone()));

        // Shelve file
        $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, $now->getTimestamp());

        // Shelve again at the same time
        $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, $now->getTimestamp());
    }

    public function testOverwriteOptionAllow(): void
    {
        // Minimal options
        $shelf = new \cstuder\TemporalShelf\TemporalShelf(
            $this->root->url() . DIRECTORY_SEPARATOR . self::SHELVE_DIRECTORY
        );
        $shelf->setOverwriteOption(\cstuder\TemporalShelf\Options\OverwriteOptions::ALLOW_OVERWRITE);
        $now = new \DateTime('now', new \DateTimeZone($shelf->getTimezone()));

        // Shelve file
        $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, $now->getTimestamp());

        // Shelve again at the same time
        $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, $now->getTimestamp());
    }

    public function testOverwriteOptionException(): void
    {
        $this->expectException(Exception::class);

        // Minimal options
        $shelf = new \cstuder\TemporalShelf\TemporalShelf(
            $this->root->url() . DIRECTORY_SEPARATOR . self::SHELVE_DIRECTORY
        );
        $shelf->setOverwriteOption(\cstuder\TemporalShelf\Options\OverwriteOptions::EXCEPTION_ON_OVERWRITE);
        $now = new \DateTime('now', new \DateTimeZone($shelf->getTimezone()));

        // Shelve file
        $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, $now->getTimestamp());

        // Shelve again at the same time
        $shelf->shelveFile($this->root->url() . DIRECTORY_SEPARATOR . self::TESTFILENAME, $now->getTimestamp());
    }
}
