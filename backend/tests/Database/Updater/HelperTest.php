<?php

use PHPUnit\Framework\TestCase;
use MockFileSystem\MockFileSystem as mockfs;
use IntruderAlert\Database\Updater\Helper;

class HelperTest extends TestCase
{
    public function setup(): void
    {
        mockfs::create();
    }

    /**
     * Test `checkIntegrity()`
     */
    public function testCheckIntegrity(): void
    {
        $this->expectNotToPerformAssertions();

        $file = mockfs::getUrl('/test.file');
        file_put_contents($file, uniqid());
        $checksum = hash_file('sha256', $file);

        $helper = new Helper();
        $helper->checkIntegrity((string) $checksum, $file);
    }

    /**
     * Test `checkIntegrity()` with hashes that do not match
     */
    public function testCheckIntegrityFailure(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Integrity check failed');

        $file = mockfs::getUrl('/test.file');
        file_put_contents($file, uniqid());
        $checksum = hash_file('sha1', $file);

        $helper = new Helper();
        $helper->checkIntegrity((string) $checksum, $file);
    }

    /**
     * Test `checkDatabaseUpdateStatus()` with a missing file
     */
    public function testCheckDatabaseUpdateStatusMissingFile(): void
    {
        $helper = new Helper();
        $this->assertTrue($helper->checkDatabaseUpdateStatus('not-found.file'));
    }
}
