<?php

use PHPUnit\Framework\TestCase;
use MockFileSystem\MockFileSystem as mockfs;
use IntruderAlert\Config;
use IntruderAlert\Config\Check;
use IntruderAlert\Exception\ConfigException;

class CheckTest extends TestCase
{
    /** @var array<string, mixed> $defaults */
    private static array $defaults = [];

    public static function setupBeforeClass(): void
    {
        $reflection = new ReflectionClass(new Config());
        self::$defaults = $reflection->getProperty('config')->getValue(new Config());
    }

    public function setUp(): void
    {
        // Unset environment variables before each test
        putenv('IA_LOG_PATHS');
        putenv('IA_LOG_FOLDER');
        putenv('IA_MAXMIND_LICENSE_KEY');
        putenv('IA_ASN_DATABASE');
        putenv('IA_COUNTRY_DATABASE');
        putenv('IA_TIMEZONE');
        putenv('IA_SYSTEM_LOG_TIMEZONE');
        putenv('IA_DASH_CHARTS');
        putenv('IA_DASH_UPDATES');
        putenv('IA_DASH_DAEMON_LOG');
    }

    /**
     * Test `getConfig`
     */
    public function testGetConfig(): void
    {
        $check = new Check(self::$defaults);
        $this->assertEquals(self::$defaults, $check->getConfig());
    }

    /**
     * Test `IA_LOG_FOLDER`
     */
    public function testLogFolder(): void
    {
        putenv('IA_LOG_FOLDER=backend/tests/files/logs');

        $check = new Check(self::$defaults);
        $check->logFolder();
        $config = $check->getConfig();

        $this->assertEquals('backend/tests/files/logs', $config['log_folder']);
    }

    /**
     * Test config with empty `IA_LOG_FOLDER`
     */
    public function testEmptyLogFolder(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('fail2ban log folder variable can not be empty');

        putenv('IA_LOG_FOLDER=');

        $check = new Check(self::$defaults);
        $check->logFolder();
    }

    /**
     * Test config with `IA_LOG_FOLDER` folder that does not exist
     */
    public function testDoesNotExistLogFolder(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('fail2ban log folder does not exist');

        $folder = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'fail2ban-logs';

        putenv('IA_LOG_FOLDER=' . $folder);

        $check = new Check(self::$defaults);
        $check->logFolder();
    }

    /**
     * Test `IA_LOG_PATHS`
     */
    public function testLogPaths(): void
    {
        putenv('IA_LOG_PATHS=backend/tests/files/logs/has-bans/fail2ban.log');

        $check = new Check(self::$defaults);
        $check->logPaths();
        $config = $check->getConfig();

        $this->assertEquals('backend/tests/files/logs/has-bans/fail2ban.log', $config['log_paths']);
    }

    /**
     * Test empty `IA_LOG_PATHS`
     */
    public function testEmptyLogPaths(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('fail2ban log paths variable can not be empty');

        putenv('IA_LOG_PATHS=');

        $check = new Check(self::$defaults);
        $check->logPaths();
    }

    /**
     * Test `IA_MAXMIND_LICENSE_KEY`
     */
    public function testMaxMindLicenseKey(): void
    {
        putenv('IA_MAXMIND_LICENSE_KEY=qwerty');

        $check = new Check(self::$defaults);
        $check->maxMindLicenseKey();
        $config = $check->getConfig();

        $this->assertEquals('qwerty', $config['maxmind_license_key']);
    }

    /**
     * Test empty `IA_MAXMIND_LICENSE_KEY`
     */
    public function testEmptyMaxMindLicenseKey(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('MaxMind license key can not be empty');

        putenv('IA_MAXMIND_LICENSE_KEY=');

        $check = new Check(self::$defaults);
        $check->maxMindLicenseKey();
    }

    /**
     * Test `IA_ASN_DATABASE` and `IA_COUNTRY_DATABASE`
     */
    public function testDatabasePaths(): void
    {
        $asn = 'backend/tests/files/mmdb/GeoLite2-ASN-Test.mmdb';
        $country = 'backend/tests/files/mmdb/GeoLite2-Country-Test.mmdb';

        putenv('IA_ASN_DATABASE=' . $asn);
        putenv('IA_COUNTRY_DATABASE=' . $country);

        $check = new Check(self::$defaults);
        $check->databases();
        $config = $check->getConfig();

        $this->assertEquals($asn, $config['asn_database_path']);
        $this->assertEquals($country, $config['country_database_path']);
    }

    /**
     * Test empty `IA_ASN_DATABASE`
     */
    public function testEmptyAsnDatabasePath(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 ASN database path must be set');

        putenv('IA_ASN_DATABASE=');

        $check = new Check(self::$defaults);
        $check->databases();
    }

    /**
     * Test empty `IA_COUNTRY_DATABASE`
     */
    public function testEmptyCountryDatabasePath(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 Country database path must be set');

        putenv('IA_ASN_DATABASE=backend/tests/files/mmdb/GeoLite2-ASN-Test.mmdb');
        putenv('IA_COUNTRY_DATABASE=');

        $check = new Check(self::$defaults);
        $check->databases();
    }

    /**
     * Test `IA_ASN_DATABASE` with path that does not exist
     */
    public function testNotExistAsnDatabasePath(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 ASN database not found');

        putenv('IA_ASN_DATABASE=fake.path');
        putenv('IA_COUNTRY_DATABASE=backend/tests/files/mmdb/GeoLite2-Country-Test.mmdb');

        $check = new Check(self::$defaults);
        $check->databases();
    }

    /**
     * Test `IA_COUNTRY_DATABASE` with path that does not exist
     */
    public function testNotExistCountryDatabasePath(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 Country database not found');

        putenv('IA_ASN_DATABASE=backend/tests/files/mmdb/GeoLite2-ASN-Test.mmdb');
        putenv('IA_COUNTRY_DATABASE=fake.path');

        $check = new Check(self::$defaults);
        $check->databases();
    }

    /**
     * Test `isDatabaseValid()`
     */
    public function testIsDatabaseValid(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('GeoLite2 database is invalid: backend/tests/files/fake-database.file');

        $check = new Check(self::$defaults);
        $check->isDatabaseValid('backend/tests/files/fake-database.file');
    }

    /**
     * Test `IA_DASH_CHARTS`
     */
    public function testDashCharts(): void
    {
        putenv('IA_DASH_CHARTS=false');

        $check = new Check(self::$defaults);
        $check->dashboard();
        $config = $check->getConfig();

        $this->assertFalse($config['dash_charts']);
    }

    /**
     * Test `IA_DASH_CHARTS` variable type
     */
    public function testDashChartVarType(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Charts environment variable must be true or false');

        putenv('IA_DASH_CHARTS=string');

        $check = new Check(self::$defaults);
        $check->dashboard();
    }

    /**
     * Test `IA_DASH_UPDATES`
     */
    public function testDashUpdates(): void
    {
        putenv('IA_DASH_UPDATES=false');

        $check = new Check(self::$defaults);
        $check->dashboard();
        $config = $check->getConfig();

        $this->assertFalse($config['dash_updates']);
    }

    /**
     * Test `IA_DASH_UPDATES` variable type
     */
    public function testDashUpdatesVarType(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Dashboard updates environment variable must be true or false');

        putenv('IA_DASH_UPDATES=string');

        $check = new Check(self::$defaults);
        $check->dashboard();
    }

    /**
     * Test `IA_DASH_DAEMON_LOG`
     */
    public function testDashDaemonLog(): void
    {
        putenv('IA_DASH_DAEMON_LOG=false');

        $check = new Check(self::$defaults);
        $check->dashboard();
        $config = $check->getConfig();

        $this->assertFalse($config['dash_daemon_log']);
    }

    /**
     * Test `IA_DASH_DAEMON_LOG` variable type
     */
    public function testDashDaemonLogVarType(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Dashboard daemon log environment variable must be true or false');

        putenv('IA_DASH_DAEMON_LOG=string');

        $check = new Check(self::$defaults);
        $check->dashboard();
    }

    /**
     * Test no `IA_TIMEZONE` variable
     */
    public function testNoTimezone(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Timezone environment variable must be set');

        $check = new Check(self::$defaults);
        $check->timezones();
    }

    /**
     * Test `IA_TIMEZONE` unknown timezone
     */
    public function testUnknownTimezone(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Unknown timezone given');

        putenv('IA_TIMEZONE=Europe/Coventry');

        $check = new Check(self::$defaults);
        $check->timezones();
    }

    /**
     * Test `IA_SYSTEM_LOG_TIMEZONE`
     */
    public function testSystemLogTimezone(): void
    {
        putenv('IA_TIMEZONE=Europe/London');
        putenv('IA_SYSTEM_LOG_TIMEZONE=UTC');

        $check = new Check(self::$defaults);
        $check->timezones();
        $config = $check->getConfig();

        $this->assertEquals('UTC', $config['log_timezone']);
    }

    /**
     * Test empty `IA_SYSTEM_LOG_TIMEZONE`
     */
    public function testEmptySystemLogTimezone(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Timezone can not be empty [IA_SYSTEM_LOG_TIMEZONE]');

        putenv('IA_TIMEZONE=Europe/London');
        putenv('IA_SYSTEM_LOG_TIMEZONE=');

        $check = new Check(self::$defaults);
        $check->timezones();
    }

    /**
     * Test `IA_SYSTEM_LOG_TIMEZONE` with unknown timezone
     */
    public function testUnknownSystemLogTimezone(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Unknown timezone given [IA_SYSTEM_LOG_TIMEZONE]');

        putenv('IA_TIMEZONE=Europe/London');
        putenv('IA_SYSTEM_LOG_TIMEZONE=Europe/Coventry');

        $check = new Check(self::$defaults);
        $check->timezones();
    }

    /**
     * Test not setting `IA_SYSTEM_LOG_TIMEZONE`
     */
    public function testNotSettingSystemLogTimezone(): void
    {
        putenv('IA_TIMEZONE=Europe/London');

        $tz = date_default_timezone_get();

        $check = new Check(self::$defaults);
        $check->timezones();
        $config = $check->getConfig();

        $this->assertEquals($tz, $config['log_timezone']);
    }

    /**
     * Test `folder()` folder creation failure
     */
    public function testFolderCreationFailure(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('Failed to create folder');

        mockfs::create();
        $folder = mockfs::getUrl('/data');

        stream_context_set_default(
            [
                'mfs' => [
                    'mkdir_fail' => true,
                ]
            ]
        );

        $check = new Check(self::$defaults);
        $check->folder($folder);
    }
}
