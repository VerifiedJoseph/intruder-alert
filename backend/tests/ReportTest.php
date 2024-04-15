<?php

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use MockFileSystem\MockFileSystem as mockfs;
use IntruderAlert\Report;
use IntruderAlert\Lists;
use IntruderAlert\Logger;
use IntruderAlert\Config;

#[CoversClass(Report::class)]
#[UsesClass(IntruderAlert\Config::class)]
#[UsesClass(IntruderAlert\Helper\File::class)]
#[UsesClass(IntruderAlert\Helper\Json::class)]
#[UsesClass(IntruderAlert\Helper\Output::class)]
#[UsesClass(IntruderAlert\List\AbstractList::class)]
#[UsesClass(IntruderAlert\List\Addresses::class)]
#[UsesClass(IntruderAlert\List\Dates::class)]
#[UsesClass(IntruderAlert\Lists::class)]
#[UsesClass(IntruderAlert\Logger::class)]
class ReportTest extends AbstractTestCase
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
        $this->expectOutputRegex('/Created report JSON file/');

        $path = mockfs::getUrl('/report.json');
        $timezone = 'UTC';

        $report = new Report(
            self::$lists->get(),
            self::$lists->getCounts(),
            $path,
            $timezone,
            new Logger()
        );

        $reflection = new ReflectionClass($report);
        $property = $reflection->getProperty('date');
        $property->setAccessible(true);
        $property->setValue($report, new DateTimeImmutable('2024-03-13 00:00:00'));

        $report->generate();

        $expected = self::getJsonFile('./backend/tests/files/expected-report.json');
        $actual = self::getJsonFile(mockfs::getUrl('/report.json'));

        $this->assertGreaterThan(0, strtotime($actual['updated']));
        $this->assertMatchesRegularExpression(
            '/Last run: ([\d]{4}-[\d]{2}-[\d]{2} [\d]{2}:[\d]{2}:[\d]{2})/',
            $actual['log'][0]
        );

        $actual['updated'] = '2024-03-13 00:00:00';
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
