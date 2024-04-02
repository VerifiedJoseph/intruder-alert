<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use MockFileSystem\MockFileSystem as mockfs;
use IntruderAlert\Database\Updater\TimestampFile;
use IntruderAlert\Helper\File;

#[CoversClass(TimestampFile::class)]
#[UsesClass(File::class)]
class TimestampFileTest extends AbstractTestCase
{
    public function setup(): void
    {
        mockfs::create();
    }

    /**
     * Test `getTimestamp()`
     */
    public function testGetTimestamp(): void
    {
        $date = new DateTime();

        $file = mockfs::getUrl('/database.timestamp');
        file_put_contents($file, $date->format('Y-m-d H:i:s'));

        $tsFile = new TimestampFile($file);
        $this->assertEquals($date->getTimestamp(), $tsFile->getTimestamp());
    }

    /**
     * Test `getTimestamp()` with no timestamp file
     */
    public function testGetTimestampNoFile(): void
    {
        $file = mockfs::getUrl('/database.timestamp');

        $tsFile = new TimestampFile($file);
        $this->assertEquals(0, $tsFile->getTimestamp());
    }

    /**
     * Test `isOutdated()`
     */
    public function testIsOutdated(): void
    {
        $date = new DateTime();
        $date->modify('-1 day');

        $file = mockfs::getUrl('/database.timestamp');
        file_put_contents($file, $date->format('Y-m-d H:i:s'));

        $tsFile = new TimestampFile($file);
        $this->assertTrue($tsFile->isOutdated());
    }

    /**
     * Test `isOutdated()` returning false
     */
    public function testIsOutdatedFalse(): void
    {
        $date = new DateTime();

        $file = mockfs::getUrl('/database.timestamp');
        file_put_contents($file, $date->format('Y-m-d H:i:s'));

        $tsFile = new TimestampFile($file);
        $this->assertFalse($tsFile->isOutdated());
    }

    /**
     * Test `update()`
     */
    public function testUpdate(): void
    {
        $date = new DateTime();

        $file = mockfs::getUrl('/database.timestamp');

        $tsFile = new TimestampFile($file);
        $tsFile->update();

        $this->assertEquals($date->format('Y-m-d H:i:s'), File::read($file));
    }
}
