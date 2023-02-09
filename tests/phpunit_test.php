<?php

$file = __DIR__ . "/../agent.class.php";
require($file);

use PHPUnit\Framework\TestCase;

final class phpunit_test extends TestCase
{
    public function test_firefox() {
		$a = new BrowserAgent();

		$this->assertEquals($a->get_agent_info('Mozilla/5.0 (X11; Linux x86_64; rv:29.0) Gecko/20100101 Firefox/29.0','full'),'Firefox 29.0 on Linux x86_64');
		$this->assertEquals($a->get_agent_info('Mozilla/5.0 (Windows NT 6.1; rv:22.0) Gecko/20130405 Firefox/22.0','full'),'Firefox 22.0 on Windows 7');
		$this->assertEquals($a->get_agent_info('Mozilla/5.0 (Windows NT 6.1; WOW64; rv:21.0) Gecko/20130331 Firefox/21.0','full'),'Firefox 21.0 on Windows 7');
		$this->assertEquals($a->get_agent_info('Mozilla/5.0 (X11; Ubuntu; Linux armv7l; rv:17.0) Gecko/20100101 Firefox/17.0','full'),'Firefox 17.0 on Linux armv7l');
		$this->assertEquals($a->get_agent_info('Mozilla/5.0 (X11; Ubuntu; Linux armv7l; rv:17.0) Gecko/20100101 Firefox/17.0','full'),'Firefox 17.0 on Linux armv7l');
		$this->assertEquals($a->get_agent_info('Mozilla/5.0 (Macintosh; Intel Mac OS X 10.8; rv:24.0) Gecko/20100101 Firefox/24.0','full'),'Firefox 24.0 on OS X 10.8');
		$this->assertEquals($a->get_agent_info('Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:25.0) Gecko/20100101 Firefox/25.0','full'),'Firefox 25.0 on OS X 10.6');

		$this->assertEquals('Firefox 5.0 on FreeBSD amd64', $a->get_agent_info('Mozilla/5.0 (X11; FreeBSD amd64; rv:5.0) Gecko/20100101 Firefox/5.0','full'));
		$this->assertEquals('Firefox 10.0 on OS X 10.4', $a->get_agent_info('Mozilla/5.0 (Macintosh; PPC Mac OS X 10.4; rv:10.0) Gecko/20100101 Firefox/10.0','full'));
		$this->assertEquals('Firefox 10.0 on Linux i686', $a->get_agent_info('Mozilla/5.0 (X11; Linux i686 on x86_64; rv:10.0) Gecko/20100101 Firefox/10.0','full'));
		$this->assertEquals('Firefox 25.0 on Windows 7', $a->get_agent_info('Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:25.0) Gecko/20100101 Firefox/25.0','full'));
		$this->assertEquals('Firefox 27.0 on Linux x86_64', $a->get_agent_info('Mozilla/5.0 (X11; Linux x86_64; rv:27.0) Gecko/20100101 Firefox/27.0','full'));
    }

    public function test_chrome() {
		$a = new BrowserAgent();

		$this->assertEquals('Chrome 30.0.1599.17 on Windows 8', $a->get_agent_info('Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.17 Safari/537.36','full'));
		$this->assertEquals('Chrome 30.0.1599.17 on Windows 8', $a->get_agent_info('Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.17 Safari/537.36','full'));
		$this->assertEquals('Chrome 24.0.1290.1 on OS X 10.8.2', $a->get_agent_info('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.13 (KHTML, like Gecko) Chrome/24.0.1290.1 Safari/537.13','full'));
		$this->assertEquals('Chrome 24.0.1295.0 on Windows 8', $a->get_agent_info('Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.15 (KHTML, like Gecko) Chrome/24.0.1295.0 Safari/537.15','full'));
		$this->assertEquals('Chrome 32.0.1667.0 on Windows 8', $a->get_agent_info('Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1667.0 Safari/537.36','full'));
		$this->assertEquals('Chrome 19.0.1084.9 on Linux x86_64', $a->get_agent_info('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.9 Safari/536.5','full'));
		$this->assertEquals('Chrome 19.0.1041.0 on Linux i686', $a->get_agent_info('Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.21 (KHTML, like Gecko) Chrome/19.0.1041.0 Safari/535.21','full'));
		$this->assertEquals('Chrome 18.6.872.0 on Windows 7', $a->get_agent_info('Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/18.6.872.0 Safari/535.2 UNTRUSTED/1.0 3gpp-gba UNTRUSTED/1.0','full'));
		$this->assertEquals('Chrome 19.0.1055.1 on OS X 10.7.2', $a->get_agent_info('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_2) AppleWebKit/535.24 (KHTML, like Gecko) Chrome/19.0.1055.1 Safari/535.24','full'));
		$this->assertEquals('Chrome 19.0.1084.60 on iPhone 5.1.1', $a->get_agent_info('Mozilla/5.0 (iPhone; U; CPU iPhone OS 5_1_1 like Mac OS X; en) AppleWebKit/534.46.0 (KHTML, like Gecko) CriOS/19.0.1084.60 Mobile/9B206 Safari/7534.48.3','full'));

		#$this->assertEquals('', $a->get_agent_info('','full'));
	}

    public function test_edge() {
		$a = new BrowserAgent();

		$this->assertEquals('Edge 12.10136 on Windows 10', $a->get_agent_info('Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.10136','full'));
		$this->assertEquals('Edge 13.7 on Windows 10.1', $a->get_agent_info('Mozilla/5.0 (Windows NT 10.1) Edge/13.7','full'));
		$this->assertEquals('Edge 107.0.1418.30 on Windows 10', $a->get_agent_info('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Edg/107.0.1418.30','full'));
		$this->assertEquals('Edge 91.0.866.0 on Windows 10', $a->get_agent_info('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4501.0 Safari/537.36 Edg/91.0.866.0','full'));
	}

    public function test_ie() {
		$a = new BrowserAgent();

		$this->assertEquals('Internet Explorer 6.0 on Windows XP', $a->get_agent_info('Mozilla/5.0 (Windows; U; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 2.0.50727)','full'));
		$this->assertEquals('Internet Explorer 6.0 on Windows 2000', $a->get_agent_info('Mozilla/4.0 (MSIE 6.0; Windows NT 5.0)','full'));
		$this->assertEquals('Internet Explorer 5.01 on Windows NT', $a->get_agent_info('Mozilla/4.0 (compatible; MSIE 5.01; Windows NT)','full'));
		$this->assertEquals('Internet Explorer 4.5 on Windows 98', $a->get_agent_info('Mozilla/4.0 (compatible; MSIE 4.5; Windows 98; )','full'));
		$this->assertEquals('Internet Explorer 4.01 on Windows CE', $a->get_agent_info('Mozilla/4.0 (compatible; MSIE 4.01; Windows CE)','full'));
		$this->assertEquals('Internet Explorer 4.0 on Windows 95', $a->get_agent_info('Mozilla/4.0 (compatible; MSIE 4.0; Windows 95)','full'));
		$this->assertEquals('Internet Explorer 9.0 on Windows 7', $a->get_agent_info('Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; chromeframe/11.0.696.57)','full'));
		$this->assertEquals('Internet Explorer 10.0 on Windows 7', $a->get_agent_info('Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)','full'));
		$this->assertEquals('Internet Explorer 10.0 on Windows 7', $a->get_agent_info('Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; WOW64; Trident/6.0)','full'));
		$this->assertEquals('Internet Explorer 8.0 on Windows 8', $a->get_agent_info('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.2; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0)','full'));
		$this->assertEquals('Internet Explorer 7.0b on Windows XP', $a->get_agent_info('Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.1; .NET CLR 1.1.4322; Alexa Toolbar)','full'));
		$this->assertEquals('Internet Explorer 7.0 on Windows XP', $a->get_agent_info('Mozilla/5.0 (compatible; MSIE 7.0; Windows NT 5.2; WOW64; .NET CLR 2.0.50727)','full'));
		$this->assertEquals('Internet Explorer 6.1 on Windows XP', $a->get_agent_info('Mozilla/4.0 (compatible; MSIE 6.1; Windows XP; .NET CLR 1.1.4322; .NET CLR 2.0.50727)','full'));
		$this->assertEquals('Internet Explorer 5.5b1 on Macintosh PowerPC', $a->get_agent_info('Mozilla/4.0 (compatible; MSIE 5.5b1; Mac_PowerPC)','full'));
		$this->assertEquals('Internet Explorer 10.0 on OS X 10.7.3', $a->get_agent_info('Mozilla/5.0 (compatible; MSIE 10.0; Macintosh; Intel Mac OS X 10_7_3; Trident/6.0)','full'));
		$this->assertEquals('Internet Explorer 11.0 on Windows 7', $a->get_agent_info('Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; MDDRJS; rv:11.0) like Gecko','full'));
	}

    public function test_android() {
		$a = new BrowserAgent();

		$this->assertEquals('Safari 4.0 on Android 2.3.3', $a->get_agent_info('Mozilla/5.0 (Linux; U; Android 2.3.3; zh-tw; HTC_Pyramid Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1','full'));
		$this->assertEquals('Safari 4.0 on Android 4.0.3', $a->get_agent_info('Mozilla/5.0 (Linux; U; Android 4.0.3; ko-kr; LG-L160L Build/IML74K) AppleWebkit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30','full'));
		$this->assertEquals('Safari 4.0 on Android 2.2.1', $a->get_agent_info('Mozilla/5.0 (Linux; U; Android 2.2.1; en-gb; HTC_DesireZ_A7272 Build/FRG83D) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1','full'));
		$this->assertEquals('Firefox 13.0 on Android', $a->get_agent_info('Mozilla/5.0 (Android; Tablet; rv:13.0) Gecko/13.0 Firefox/13.0','full'));
		$this->assertEquals('Chrome 18.0.1025.133 on Android 4.0.4', $a->get_agent_info('Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19','full'));
		$this->assertEquals('Chrome 30.0.1599.105 on Android 4.4', $a->get_agent_info('Mozilla/5.0 (Linux; Android 4.4; Nexus 7 Build/KOT24) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.105 Safari/537.36','full'));
	}

	public function test_apple() {
		$a = new BrowserAgent();

		$this->assertEquals('Safari 4.0.4 on iPad 3.2', $a->get_agent_info('Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10','full'));
		$this->assertEquals('Safari 4.0.4 on iPad 3.2', $a->get_agent_info('Mozilla/5.0(iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10','full'));
		$this->assertEquals('Safari 5.1 on iPad 5.1', $a->get_agent_info('Mozilla/5.0 (iPad; CPU OS 5_1 like Mac OS X; en-us) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9B176 Safari/7534.48.3','full'));
		$this->assertEquals('Safari 5.0.2 on iPhone 4.3.3', $a->get_agent_info('Mozilla/5.0 (iPod; U; CPU iPhone OS 4_3_3 like Mac OS X; ja-jp) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5','full'));
		$this->assertEquals('Safari 5.1 on iPad 5.1', $a->get_agent_info('Mozilla/5.0 (iPad; CPU OS 5_1 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko ) Version/5.1 Mobile/9B176 Safari/7534.48.3','full'));
		$this->assertEquals('Safari 3.2 on OS X 10.5.5', $a->get_agent_info('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_5; sv-se) AppleWebKit/525.26.2 (KHTML, like Gecko) Version/3.2 Safari/525.26.12','full'));
		$this->assertEquals('Safari 3.1.1 on iPhone 2.0.1', $a->get_agent_info('Mozilla/5.0 (Mozilla/5.0 (iPhone; U; CPU iPhone OS 2_0_1 like Mac OS X; fr-fr) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5G77 Safari/525.20','full'));
		$this->assertEquals('Safari 4.1 on Windows 2000', $a->get_agent_info('Mozilla/5.0 (Windows; U; Windows NT 5.0; en-en) AppleWebKit/533.16 (KHTML, like Gecko) Version/4.1 Safari/533.16','full'));
		$this->assertEquals('Safari 7.0 on iPhone 7.0', $a->get_agent_info(' Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53','full'));
	}

	public function test_opera() {
		$a = new BrowserAgent();

		$this->assertEquals('Opera 11.62 on Windows 7', $a->get_agent_info('Opera/9.80 (Windows NT 6.1; WOW64; U; pt) Presto/2.10.229 Version/11.62','full'));
		$this->assertEquals('Opera 15.0.1147.100 on Windows 7', $a->get_agent_info('Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.52 Safari/537.36 OPR/15.0.1147.100','full'));
		$this->assertEquals('Opera 12.14 on Windows Vista', $a->get_agent_info('Mozilla/5.0 (Windows NT 6.0; rv:2.0) Gecko/20100101 Firefox/4.0 Opera 12.14','full'));
	}

    public function test_other() {
		$a = new BrowserAgent();

		$this->assertEquals('LWP::Simple 6.00', $a->get_agent_info('LWP::Simple/6.00 libwww-perl/6.05','full'));
		$this->assertEquals('curl 7.32.0', $a->get_agent_info('curl/7.32.0','full'));
		$this->assertEquals('libwww-perl 6.03', $a->get_agent_info('libwww-perl/6.03','full'));
	}

    public function test_bogus() {
		$a = new BrowserAgent();

		$this->assertEquals('Unknown', $a->get_agent_info('Bogus agent','full'));
		$this->assertEquals(0, $a->get_agent_info('Bogus agent','confidence'));
	}

} // End of class
?>
