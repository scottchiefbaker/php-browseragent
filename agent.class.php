<?PHP

error_reporting(E_ALL);

class BrowserAgent
{

	public function __construct() {
		$verbose = 0;
	}

	public function get_agent_info($agent = "", $piece = "") {
		$agent || $agent = $_SERVER["HTTP_USER_AGENT"];

		$agent_info = $this->parse_agent($agent);

		if ($piece) {
			return($agent_info[$piece]);
		}

		return $agent_info;
	}

	private function parse_agent($str) {
		$ret['browser'] = $this->extract_browser($str);
		$ret['os']      = $this->extract_os($str);
		$ret['engine']  = $this->extract_engine($str);

		if ($ret['browser'] && $ret['os']) {
			$browser = $ret['browser']['name'];
			if ($ret['browser']['version']) {
				$browser .= " " . $ret['browser']['version'];
			}

			$os = $ret['os']['name'];
			if ($ret['os']['version']) {
				$os .= " " . $ret['os']['version'];
			}

			$ret['full']  = sprintf("%s on %s",$browser,$os);
		} else {
			$ret['full']  = "Unknown";
		}

		$ret['agent_string'] = $str;
		$ret['confidence']   = $this->calculate_confidence($ret);

		return $ret;
	}

	private function calculate_confidence($info) {
		$confidence = 0;

		if ($info['browser'] && $info['browser']['name']) {
			$confidence++;
		}

		if ($info['browser'] && $info['browser']['version']) {
			$confidence++;
		}

		if ($info['os'] && $info['os']['name']) {
			$confidence++;
		}

		if ($info['os'] && $info['os']['version']) {
			$confidence++;
		}

		if ($info['engine'] && $info['engine']['name']) {
			$confidence++;
		}

		if ($info['engine'] && $info['engine']['version']) {
			$confidence++;
		}

		$ret = intval(($confidence / 6) * 100);

		return $ret;
	}

	private function extract_os($str) {
		if (preg_match("|Windows NT ?([\d\.]+)?|",$str,$m)) {
			$ret['name'] = 'Windows';

			if ($m[1] == 5.0) {
				$ret['version'] = '2000';
			} elseif ($m[1] == 5.1 || $m[1] == 5.2) { // 5.1 = 32 bit, 5.2 = 64bit
				$ret['version'] = 'XP';
			} elseif ($m[1] == 6.0) {
				$ret['version'] = 'Vista';
			} elseif ($m[1] == 6.1) {
				$ret['version'] = '7';
			} elseif ($m[1] == 6.2) {
				$ret['version'] = '8';
			} elseif ($m[1] == 6.3) {
				$ret['version'] = '8.1';
			} elseif (!$m[1]) {
				$ret['version'] = 'NT';
			}
		} elseif (preg_match("/(iPad|iPhone).*OS ([\d_\.]+) /i",$str,$m)) {
			$version = preg_replace("/_/",".",$m[2]);

			$ret['name']    = $m[1];
			$ret['version'] = $version;
		} elseif (preg_match("/(Android) ?([\d\.]+)?/",$str,$m)) {
			$ret['name']    = $m[1];
			$ret['version'] = $m[2];
		} elseif (preg_match("/(Linux|FreeBSD) ?(x86_64|i\d+|arm.*?|amd64)?\b/i",$str,$m)) {
			$ret['name']    = $m[1];
			$ret['version'] = $m[2];
		} elseif (preg_match("/Macintosh.*Mac OS X (.+?)[;\)]/",$str,$m)) {
			$version = preg_replace("/_/",".",$m[1]);

			$ret['name']    = 'OS X';
			$ret['version'] = $version;
		} elseif (preg_match("/Mac_PowerPC/",$str,$m)) {
			$ret['name']    = 'Macintosh';
			$ret['version'] = "PowerPC";
		} elseif (preg_match("/Windows (95|98|ME|CE|XP)[;\)]/",$str,$m)) {
			$ret['name']    = 'Windows';
			$ret['version'] = $m[1];
		} else {
			$ret = array();
		}

		return $ret;
	}

	private function extract_browser($str) {
		// Firefox
		if (preg_match("|(Firefox)/([\d.]+)$|",$str,$m)) {
			$ret['name']    = $m[1];
			$ret['version'] = $m[2];
		// New Opera
		} elseif (preg_match("|OPR\/([\d.]+)|",$str,$m)) {
			$ret['name']    = 'Opera';
			$ret['version'] = $m[1];
		// Chrome
		} elseif (preg_match("#\) (Chrome|CriOS)/([\d.]+)#",$str,$m)) {
			$ret['name']    = 'Chrome';
			$ret['version'] = $m[2];
		// Internet Explorer
		} elseif (preg_match("|MSIE ([\d.abc]+);|",$str,$m)) {
			$ret['name']    = "Internet Explorer";
			$ret['version'] = $m[1];
		// Safari
		} elseif (preg_match("@Version/([\d.]+).*Safari@",$str,$m)) {
			$ret['name']    = "Safari";
			$ret['version'] = $m[1];
		// Old Opera
		} elseif (preg_match("|(Presto).*Version\/([\d.]+)|",$str,$m)) {
			$ret['name']    = 'Opera';
			$ret['version'] = $m[2];
		} elseif (preg_match("|(Opera) ([\d.]+)|",$str,$m)) {
			$ret['name']    = 'Opera';
			$ret['version'] = $m[2];
		} else {
			$ret = array();
		}

		return $ret;
	}

	private function extract_engine($str) {
		$ret = "";

		if (preg_match("/(Trident|AppleWebKit|Presto)\/([\d\.]+)/",$str,$m)) {
			$ret['name']    = $m[1];
			$ret['version'] = $m[2];
		} elseif (preg_match("/rv:([\d\.]+)\).*Gecko/",$str,$m)) {
			$ret['name']    = 'Gecko';
			$ret['version'] = $m[1];
		}

		return $ret;
	}

} // End of class
