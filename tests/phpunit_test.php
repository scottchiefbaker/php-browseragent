<?php

$file = __DIR__ . "/../agent.class.php";
require($file);

use PHPUnit\Framework\TestCase;

final class phpunit_test extends TestCase
{
    private BrowserAgent $agent;

    /** @var string|null Backup of $_SERVER['HTTP_USER_AGENT'] for fallback tests. */
    private $serverUaBackup;

    /** @var bool Whether the backup key existed. */
    private bool $serverUaExisted = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->agent = new BrowserAgent();
    }

    protected function tearDown(): void
    {
        // Restore $_SERVER state mutated by fallback tests.
        if ($this->serverUaExisted) {
            $_SERVER['HTTP_USER_AGENT'] = $this->serverUaBackup;
        } else {
            unset($_SERVER['HTTP_USER_AGENT']);
        }
        parent::tearDown();
    }

    private function backupServerUa(): void
    {
        $this->serverUaExisted = array_key_exists('HTTP_USER_AGENT', $_SERVER);
        $this->serverUaBackup = $this->serverUaExisted ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    // ------------------------------------------------------------------
    // Browser corpus (converted to data providers; duplicates removed;
    // assertSame($expected, $actual) ordering throughout).
    // ------------------------------------------------------------------

    /** @dataProvider firefoxProvider */
    public function test_firefox(string $ua, string $expected): void
    {
        $this->assertSame($expected, $this->agent->get_agent_info($ua, 'full'));
    }

    public function firefoxProvider(): array
    {
        return [
            'linux x86_64'         => ['Mozilla/5.0 (X11; Linux x86_64; rv:29.0) Gecko/20100101 Firefox/29.0', 'Firefox 29.0 on Linux x86_64'],
            'win7 rv22'            => ['Mozilla/5.0 (Windows NT 6.1; rv:22.0) Gecko/20130405 Firefox/22.0', 'Firefox 22.0 on Windows 7'],
            'win7 wow64 rv21'      => ['Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20130331 Firefox/21.0', 'Firefox 21.0 on Windows 7'],
            'linux armv7l'         => ['Mozilla/5.0 (X11; Ubuntu; Linux armv7l; rv:17.0) Gecko/20100101 Firefox/17.0', 'Firefox 17.0 on Linux armv7l'],
            'osx 10.8'             => ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:24.0) Gecko/20100101 Firefox/24.0', 'Firefox 24.0 on OS X 10.8'],
            'osx 10.6'             => ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:25.0) Gecko/20100101 Firefox/25.0', 'Firefox 25.0 on OS X 10.6'],
            'freebsd amd64'        => ['Mozilla/5.0 (X11; FreeBSD amd64; rv:5.0) Gecko/20100101 Firefox/5.0', 'Firefox 5.0 on FreeBSD amd64'],
            'osx ppc 10.4'         => ['Mozilla/5.0 (Macintosh; PPC Mac OS X 10.4; rv:10.0) Gecko/20100101 Firefox/10.0', 'Firefox 10.0 on OS X 10.4'],
            'linux i686 on x86_64' => ['Mozilla/5.0 (X11; Linux i686 on x86_64; rv:10.0) Gecko/20100101 Firefox/10.0', 'Firefox 10.0 on Linux i686'],
            'win7 win64'           => ['Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:25.0) Gecko/20100101 Firefox/25.0', 'Firefox 25.0 on Windows 7'],
            'linux x86_64 rv27'    => ['Mozilla/5.0 (X11; Linux x86_64; rv:27.0) Gecko/20100101 Firefox/27.0', 'Firefox 27.0 on Linux x86_64'],
            // Moved here from test_edge(): exercises Windows NT >= 10 branch.
            'win future nt 13.1'   => ['Mozilla/5.0 (Windows NT 13.1.0; Win64; x64; rv:157.0) Gecko/20100101 Firefox/157.0', 'Firefox 157.0 on Windows 13.1.0'],
        ];
    }

    /** @dataProvider chromeProvider */
    public function test_chrome(string $ua, string $expected): void
    {
        $this->assertSame($expected, $this->agent->get_agent_info($ua, 'full'));
    }

    public function chromeProvider(): array
    {
        return [
            'win8 wow64'       => ['Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.17 Safari/537.36', 'Chrome 30.0.1599.17 on Windows 8'],
            'osx 10.8.2'       => ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.13 (KHTML, like Gecko) Chrome/24.0.1290.1 Safari/537.13', 'Chrome 24.0.1290.1 on OS X 10.8.2'],
            'win8 wow64 v24'   => ['Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.15 (KHTML, like Gecko) Chrome/24.0.1295.0 Safari/537.15', 'Chrome 24.0.1295.0 on Windows 8'],
            'win8 win64 v32'   => ['Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1667.0 Safari/537.36', 'Chrome 32.0.1667.0 on Windows 8'],
            'linux x86_64 v19' => ['Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.9 Safari/536.5', 'Chrome 19.0.1084.9 on Linux x86_64'],
            'linux i686'       => ['Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.21 (KHTML, like Gecko) Chrome/19.0.1041.0 Safari/535.21', 'Chrome 19.0.1041.0 on Linux i686'],
            'win7 untrusted'   => ['Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/18.6.872.0 Safari/535.2 UNTRUSTED/1.0 3gpp-gba UNTRUSTED/1.0', 'Chrome 18.6.872.0 on Windows 7'],
            'osx 10.7.2'       => ['Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/535.24 (KHTML, like Gecko) Chrome/19.0.1055.1 Safari/535.24', 'Chrome 19.0.1055.1 on OS X 10.7.2'],
            'crios iphone'     => ['Mozilla/5.0 (iPhone; U; CPU iPhone OS 5_1_1 like Mac OS X; en) AppleWebKit/534.46.0 (KHTML, like Gecko) CriOS/19.0.1084.60 Mobile/9B206 Safari/7534.48.3', 'Chrome 19.0.1084.60 on iPhone 5.1.1'],
        ];
    }

    /** @dataProvider edgeProvider */
    public function test_edge(string $ua, string $expected): void
    {
        $this->assertSame($expected, $this->agent->get_agent_info($ua, 'full'));
    }

    public function edgeProvider(): array
    {
        return [
            'edgehtml 12'      => ['Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.10136', 'Edge 12.10136 on Windows 10'],
            'edgehtml 13'      => ['Mozilla/5.0 (Windows NT 10.1) Edge/13.7', 'Edge 13.7 on Windows 10.1'],
            'chromium edg 107' => ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Edg/107.0.1418.30', 'Edge 107.0.1418.30 on Windows 10'],
            'chromium edg 91'  => ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4501.0 Safari/537.36 Edg/91.0.866.0', 'Edge 91.0.866.0 on Windows 10'],
        ];
    }

    /** @dataProvider ieProvider */
    public function test_ie(string $ua, string $expected): void
    {
        $this->assertSame($expected, $this->agent->get_agent_info($ua, 'full'));
    }

    public function ieProvider(): array
    {
        return [
            'ie6 xp sv1'           => ['Mozilla/5.0 (Windows; U; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)', 'Internet Explorer 6.0 on Windows XP'],
            'ie6 win2000'          => ['Mozilla/4.0 (MSIE 6.0; Windows NT 5.0)', 'Internet Explorer 6.0 on Windows 2000'],
            'ie5.01 nt'            => ['Mozilla/4.0 (compatible; MSIE 5.01; Windows NT)', 'Internet Explorer 5.01 on Windows NT'],
            'ie4.5 win98'          => ['Mozilla/4.0 (compatible; MSIE 4.5; Windows 98; )', 'Internet Explorer 4.5 on Windows 98'],
            'ie4.01 wince'         => ['Mozilla/4.0 (compatible; MSIE 4.01; Windows CE)', 'Internet Explorer 4.01 on Windows CE'],
            'ie4.0 win95'          => ['Mozilla/4.0 (compatible; MSIE 4.0; Windows 95)', 'Internet Explorer 4.0 on Windows 95'],
            'ie9 win7 chromeframe' => ['Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; chromeframe/11.0.696.57)', 'Internet Explorer 9.0 on Windows 7'],
            'ie10 win7'            => ['Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)', 'Internet Explorer 10.0 on Windows 7'],
            'ie8 win8'             => ['Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.2; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0)', 'Internet Explorer 8.0 on Windows 8'],
            'ie7b xp'              => ['Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.1; .NET CLR 1.1.4322; Alexa Toolbar)', 'Internet Explorer 7.0b on Windows XP'],
            'ie7 xp64'             => ['Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 5.2; WOW64; .NET CLR 2.0.50727)', 'Internet Explorer 7.0 on Windows XP'],
            'ie6.1 xp'             => ['Mozilla/4.0 (compatible; MSIE 6.1; Windows XP; .NET CLR 1.1.4322; .NET CLR 2.0.50727)', 'Internet Explorer 6.1 on Windows XP'],
            'ie5.5b1 mac ppc'      => ['Mozilla/4.0 (compatible; MSIE 5.5b1; Mac_PowerPC)', 'Internet Explorer 5.5b1 on Macintosh PowerPC'],
            'ie10 osx'             => ['Mozilla/5.0 (compatible; MSIE 10.0; Macintosh; Intel Mac OS X 10_7_3; Trident/6.0)', 'Internet Explorer 10.0 on OS X 10.7.3'],
            'ie11 trident rv'      => ['Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; MDDRJS; rv:11.0) like Gecko', 'Internet Explorer 11.0 on Windows 7'],
        ];
    }

    /** @dataProvider androidProvider */
    public function test_android(string $ua, string $expected): void
    {
        $this->assertSame($expected, $this->agent->get_agent_info($ua, 'full'));
    }

    public function androidProvider(): array
    {
        return [
            'safari android 2.3.3'         => ['Mozilla/5.0 (Linux; U; Android 2.3.3; zh-tw; HTC_Pyramid Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1', 'Safari 4.0 on Android 2.3.3'],
            'safari android 4.0.3'         => ['Mozilla/5.0 (Linux; U; Android 4.0.3; ko-kr; LG-L160L Build/IML74K) AppleWebkit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30', 'Safari 4.0 on Android 4.0.3'],
            'safari android 2.2.1'         => ['Mozilla/5.0 (Linux; U; Android 2.2.1; en-gb; HTC_DesireZ_A7272 Build/FRG83D) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1', 'Safari 4.0 on Android 2.2.1'],
            'firefox tablet no os version' => ['Mozilla/5.0 (Android; Tablet; rv:13.0) Gecko/13.0 Firefox/13.0', 'Firefox 13.0 on Android'],
            'chrome android 4.0.4'         => ['Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19', 'Chrome 18.0.1025.133 on Android 4.0.4'],
            'chrome android 4.4'           => ['Mozilla/5.0 (Linux; Android 4.4; Nexus 7 Build/KOT24) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.105 Safari/537.36', 'Chrome 30.0.1599.105 on Android 4.4'],
        ];
    }

    /** @dataProvider appleProvider */
    public function test_apple(string $ua, string $expected): void
    {
        $this->assertSame($expected, $this->agent->get_agent_info($ua, 'full'));
    }

    public function appleProvider(): array
    {
        return [
            'ipad 3.2'                => ['Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10', 'Safari 4.0.4 on iPad 3.2'],
            'ipad iphone-os 3.2'      => ['Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10', 'Safari 4.0.4 on iPad 3.2'],
            'ipad 5.1'                => ['Mozilla/5.0 (iPad; CPU OS 5_1 like Mac OS X; en-us) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9B176 Safari/7534.48.3', 'Safari 5.1 on iPad 5.1'],
            'ipod 4.3.3'              => ['Mozilla/5.0 (iPod; U; CPU iPhone OS 4_3_3 like Mac OS X; ja-jp) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5', 'Safari 5.0.2 on iPhone 4.3.3'],
            'ipad 5.1 trailing space' => ['Mozilla/5.0 (iPad; CPU OS 5_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko ) Version/5.1 Mobile/9B176 Safari/7534.48.3', 'Safari 5.1 on iPad 5.1'],
            'osx 10.5.5'              => ['Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_5; sv-se) AppleWebKit/525.26.2 (KHTML, like Gecko) Version/3.2 Safari/525.26.12', 'Safari 3.2 on OS X 10.5.5'],
            'nested mozilla token'    => ['Mozilla/5.0 (Mozilla/5.0 (iPhone; U; CPU iPhone OS 2_0_1 like Mac OS X; fr-fr) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5G77 Safari/525.20', 'Safari 3.1.1 on iPhone 2.0.1'],
            'safari win2000'          => ['Mozilla/5.0 (Windows; U; Windows NT 5.0; en-en) AppleWebKit/533.16 (KHTML, like Gecko) Version/4.1 Safari/533.16', 'Safari 4.1 on Windows 2000'],
            'leading space'           => [' Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53', 'Safari 7.0 on iPhone 7.0'],
        ];
    }

    /** @dataProvider operaProvider */
    public function test_opera(string $ua, string $expected): void
    {
        $this->assertSame($expected, $this->agent->get_agent_info($ua, 'full'));
    }

    public function operaProvider(): array
    {
        return [
            'presto 11.62'            => ['Opera/9.80 (Windows NT 6.1; WOW64; U; pt) Presto/2.10.229 Version/11.62', 'Opera 11.62 on Windows 7'],
            'blink opr 15'            => ['Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.52 Safari/537.36 OPR/15.0.1147.100', 'Opera 15.0.1147.100 on Windows 7'],
            'opera 12.14 gecko token' => ['Mozilla/5.0 (Windows NT 6.0; rv:2.0) Gecko/20100101 Firefox/4.0 Opera 12.14', 'Opera 12.14 on Windows Vista'],
        ];
    }

    /** @dataProvider otherProvider */
    public function test_other(string $ua, string $expected): void
    {
        $this->assertSame($expected, $this->agent->get_agent_info($ua, 'full'));
    }

    public function otherProvider(): array
    {
        return [
            'lwp simple'  => ['LWP::Simple/6.00 libwww-perl/6.05', 'LWP::Simple 6.00'],
            'curl'        => ['curl/7.32.0', 'curl 7.32.0'],
            'libwww-perl' => ['libwww-perl/6.03', 'libwww-perl 6.03'],
        ];
    }

    // ------------------------------------------------------------------
    // Input edge cases (replaces commented-out empty-string assertion).
    // ------------------------------------------------------------------

    public function test_bogus(): void
    {
        $this->assertSame('Unknown', $this->agent->get_agent_info('Bogus agent', 'full'));
        $this->assertSame(0, $this->agent->get_agent_info('Bogus agent', 'confidence'));
    }

    /** @dataProvider emptyProvider */
    public function test_empty_without_server_fallback(string $ua): void
    {
        $this->backupServerUa();
        unset($_SERVER['HTTP_USER_AGENT']);

        $info = $this->agent->get_agent_info($ua);

        $this->assertSame('Unknown', $info['full']);
        $this->assertSame(0, $info['confidence']);
        $this->assertSame('', $info['agent_string']);
        $this->assertSame([], $info['browser']);
        $this->assertSame([], $info['os']);
        $this->assertSame([], $info['engine']);
    }

    public function emptyProvider(): array
    {
        return [
            'empty string' => [''],
        ];
    }

    // ------------------------------------------------------------------
    // Contract tests: return shape, pieces, engine, confidence, options.
    // ------------------------------------------------------------------

    public function test_default_call_returns_full_array_shape(): void
    {
        $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Edg/107.0.1418.30';
        $info = $this->agent->get_agent_info($ua);

        $this->assertIsArray($info);
        foreach (['browser', 'os', 'engine', 'full', 'agent_string', 'confidence'] as $key) {
            $this->assertArrayHasKey($key, $info);
        }
        $this->assertSame($ua, $info['agent_string']);
        $this->assertSame('Edge 107.0.1418.30 on Windows 10', $info['full']);
        // assertEquals (not Same): os version is float(10), brittle under Same.
        $this->assertEquals(['name' => 'Edge', 'version' => '107.0.1418.30'], $info['browser']);
        $this->assertEquals(['name' => 'Windows', 'version' => '10'], $info['os']);
        $this->assertEquals(['name' => 'AppleWebKit', 'version' => '537.36'], $info['engine']);
        $this->assertSame(100, $info['confidence']);
    }

    public function test_individual_pieces(): void
    {
        $ua = 'Mozilla/5.0 (X11; Linux x86_64; rv:29.0) Gecko/20100101 Firefox/29.0';

        $this->assertSame('Firefox 29.0 on Linux x86_64', $this->agent->get_agent_info($ua, 'full'));
        $this->assertEquals(['name' => 'Firefox', 'version' => '29.0'], $this->agent->get_agent_info($ua, 'browser'));
        $this->assertEquals(['name' => 'Linux', 'version' => 'x86_64'], $this->agent->get_agent_info($ua, 'os'));
        $this->assertEquals(['name' => 'Gecko', 'version' => '29.0'], $this->agent->get_agent_info($ua, 'engine'));
        $this->assertSame($ua, $this->agent->get_agent_info($ua, 'agent_string'));
        $this->assertSame(100, $this->agent->get_agent_info($ua, 'confidence'));
    }

    /** @dataProvider engineProvider */
    public function test_engine_extraction(string $ua, array $expected): void
    {
        $this->assertEquals($expected, $this->agent->get_agent_info($ua, 'engine'));
    }

    public function engineProvider(): array
    {
        return [
            'gecko' => [
                'Mozilla/5.0 (X11; Linux x86_64; rv:29.0) Gecko/20100101 Firefox/29.0',
                ['name' => 'Gecko', 'version' => '29.0'],
            ],
            'applewebkit paren' => [
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Edg/107.0.1418.30',
                ['name' => 'AppleWebKit', 'version' => '537.36'],
            ],
            'trident semicolon' => [
                'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; MDDRJS; rv:11.0) like Gecko',
                ['name' => 'Trident', 'version' => '7.0'],
            ],
            'presto' => [
                'Opera/9.80 (Windows NT 6.1; WOW64; U; pt) Presto/2.10.229 Version/11.62',
                ['name' => 'Presto', 'version' => '2.10.229'],
            ],
            'none for curl' => [
                'curl/7.32.0',
                [],
            ],
        ];
    }

    /** @dataProvider confidenceProvider */
    public function test_confidence_tiers(string $ua, int $expected): void
    {
        $this->assertSame($expected, $this->agent->get_agent_info($ua, 'confidence'));
    }

    public function confidenceProvider(): array
    {
        return [
            // 0/6 signals.
            'unknown' => ['Bogus agent', 0],
            // browser name+version only (2/6 -> 33).
            'curl' => ['curl/7.32.0', 33],
            // browser + os, no engine (4/6 -> 66).
            'ie6 without engine token' => ['Mozilla/5.0 (Windows; U; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)', 66],
            // browser + os + engine (6/6 -> 100).
            'edge full' => ['Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Edg/107.0.1418.30', 100],
        ];
    }

    public function test_round_version_option(): void
    {
        $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Edg/107.0.1418.30';

        $rounded = $this->agent->get_agent_info($ua, 'browser', ['round_version' => true]);
        $this->assertSame('107.0', $rounded['version']);
        $this->assertSame('Edge 107.0 on Windows 10', $this->agent->get_agent_info($ua, 'full', ['round_version' => true]));

        // Without the option the full 4-part version is preserved.
        $plain = $this->agent->get_agent_info($ua, 'browser');
        $this->assertSame('107.0.1418.30', $plain['version']);
    }

    public function test_empty_string_falls_back_to_server_ua(): void
    {
        $this->backupServerUa();
        $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Edg/107.0.1418.30';
        $_SERVER['HTTP_USER_AGENT'] = $ua;

        $this->assertSame('Edge 107.0.1418.30 on Windows 10', $this->agent->get_agent_info('', 'full'));
        $this->assertSame($ua, $this->agent->get_agent_info('', 'agent_string'));
    }

    public function test_unknown_piece_returns_full_array(): void
    {
        $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Edg/107.0.1418.30';

        $viaUnknownPiece = $this->agent->get_agent_info($ua, 'nonexistent');
        $viaDefault = $this->agent->get_agent_info($ua);

        $this->assertIsArray($viaUnknownPiece);
        $this->assertEquals($viaDefault, $viaUnknownPiece);
    }
} // End of class
