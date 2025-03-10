<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\CoversClass;
use IntruderAlert\Database\Updater\Url;

#[CoversClass(Url::class)]
class UrlTest extends AbstractTestCase
{
    /**
     * Test `get()`
     */
    public function testGet(): void
    {
        $expected = 'https://download.maxmind.com/app/geoip_download?edition_id=GeoLite2-ASN' .
            '&license_key=qwerty&suffix=tar.gz.sha256';

        $buildUrl = new Url('https://download.maxmind.com/app/geoip_download?', 'qwerty');
        $url = $buildUrl->get('GeoLite2-ASN', 'tar.gz.sha256');

        $this->assertEquals($expected, $url);
    }
}
