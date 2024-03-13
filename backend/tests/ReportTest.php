<?php

use PHPUnit\Framework\TestCase;
use MockFileSystem\MockFileSystem as mockfs;
use IntruderAlert\Report;
use IntruderAlert\Lists;
use IntruderAlert\Helper\Logger;

class ReportTest extends TestCase
{
    private static Lists $lists;

    public static function setUpBeforeClass(): void
    {
        mockfs::create();

        $data = self::getJsonFile('./backend/tests/files/list-data.json');

        self::$lists = new Lists();
        foreach ($data['events'] as $item) {
            self::$lists->addIp($item);
        }
    }

    /**
     * Test `generate()`
     */
    public function testGenerate(): void
    {
        ///$this->expectOutputRegex('/Created report JSON file/');

        $report = new Report(
            self::$lists->get(),
            self::$lists->getCounts(),
            mockfs::getUrl('/report.json'),
        );

        $report->generate();

        $expected = self::getJsonFile('./backend/tests/files/expected-report.json');
        $actual = self::getJsonFile(mockfs::getUrl('/report.json'));

        $this->assertGreaterThan(0, strtotime($actual['updated']));
        /*$this->assertMatchesRegularExpression(
            '/Last run: ([\d]{4}-[\d]{2}-[\d]{2} [\d]{2}:[\d]{2}:[\d]{2})/',
            $actual['log'][0]
        );*/

        $actual['updated'] = '1970-01-01 00:00:00';
        $actual['log'] = [];

        $this->assertEquals($expected, $actual);
    }

    /**
     * Get json file and return array
     *
     * @param string $path filepath
     * @return array<mixed>
     */
    private static function getJsonFile(string $path): array
    {
        return json_decode(
            (string)
            file_get_contents($path),
            associative: true
        );
    }
}
