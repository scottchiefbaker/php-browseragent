<?PHP

//error_reporting(E_ALL);

if (!class_exists('BrowserAgent')) {

	class BrowserAgent
	{

		public function __construct() {
			$verbose = 0;
		}

		public function get_agent_info($agent = "", $piece = "",$opts = array()) {
			if (empty($agent)) {
				$agent = $_SERVER["HTTP_USER_AGENT"];
			}

			$agent_info = $this->parse_agent($agent,$opts);

			if (isset($piece) && isset($agent_info[$piece]) ) {
				return($agent_info[$piece]);
			}

			return $agent_info;
		}

		private function parse_agent($str,$opts = array()) {
			$ret['browser'] = $this->extract_browser($str);
			$ret['os']      = $this->extract_os($str);
			$ret['engine']  = $this->extract_engine($str);

			if ($ret['browser']) {
				$browser  = $ret['browser']['name'];
				$browserv = BrowserAgent::var_set($ret['browser']['version']);

				if ($browserv) {
					# If we're rounding we only return the first two octets (33.1.3.2024 = 33.1)
					if (isset($opts['round_version']) && $opts['round_version']) {
						$bv    = $ret['browser']['version'];

						if (strstr($bv,'.')) {
							$parts = preg_split("/\./",$bv);
							$bv    = $parts[0] . "." . $parts[1];
						}

						$ret['browser']['version'] = $bv;
					}

					$browser .= " " . $ret['browser']['version'];
				}

				if ($ret['os']) {
					$os  = $ret['os']['name'];
					$osv = BrowserAgent::var_set($ret['os']['version']);
					if ($osv) {
						$os .= " " . $ret['os']['version'];
					}
				}

				$ret['full'] = $browser;

				if ($ret['os']) {
					$ret['full'] .= " on $os";
				}
			} else {
				$ret['full']  = "Unknown";
			}

			$ret['agent_string'] = $str;
			$ret['confidence']   = $this->calculate_confidence($ret);

			return $ret;
		}

		private function calculate_confidence($info) {
			$confidence = 0;

			if (isset($info['browser']) && isset($info['browser']['name'])) {
				$confidence++;
			}

			if (isset($info['browser']) && isset($info['browser']['version'])){
				$confidence++;
			}

			if (isset($info['os']) && isset($info['os']['name'])) {
				$confidence++;
			}

			if (isset($info['os']) && isset($info['os']['version'])) {
				$confidence++;
			}

			if (isset($info['engine']) && isset($info['engine']['name'])) {
				$confidence++;
			}

			if (isset($info['engine']) && isset($info['engine']['version'])) {
				$confidence++;
			}

			$ret = intval(($confidence / 6) * 100);

			return $ret;
		}

		private function extract_os($str) {
			$ret = array();

			if (preg_match("|Windows NT ?([\d\.]+)?|",$str,$m)) {
				$ret['name'] = 'Windows';

				$ver = BrowserAgent::var_set($m[1],"");

				if ($ver == 5.0) {
					$ret['version'] = '2000';
				} elseif ($ver == 5.1 || $m[1] == 5.2) { // 5.1 = 32 bit, 5.2 = 64bit
					$ret['version'] = 'XP';
				} elseif ($ver == 6.0) {
					$ret['version'] = 'Vista';
				} elseif ($ver == 6.1) {
					$ret['version'] = '7';
				} elseif ($ver == 6.2) {
					$ret['version'] = '8';
				} elseif ($ver == 6.3) {
					$ret['version'] = '8.1';
				} elseif ($ver >= 10 && $ver < 11) {
					$ret['version'] = $ver + 0;
				} elseif (!$ver) {
					$ret['version'] = 'NT';
				}
			} elseif (preg_match("/(iPad|iPhone).*OS ([\d_\.]+) /i",$str,$m)) {
				$version = preg_replace("/_/",".",$m[2]);

				$ret['name']    = $m[1];
				$ret['version'] = $version;
			} elseif (preg_match("/(Android) ?([\d\.]+)?/",$str,$m)) {
				$ret['name']    = $m[1];
				$ret['version'] = BrowserAgent::var_set($m[2],"");
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
			}

			return $ret;
		}

		private function extract_browser($str) {
			$ret = array();

			// Firefox
			if (preg_match("|(Firefox)/([\d.]+)$|",$str,$m)) {
				$ret['name']    = $m[1];
				$ret['version'] = $m[2];
			// New Opera
			} elseif (preg_match("|OPR\/([\d.]+)|",$str,$m)) {
				$ret['name']    = 'Opera';
				$ret['version'] = $m[1];
			// Edge
			} elseif (preg_match("#\bEdge?/([\d.]+)$#",$str,$m)) {
				$ret['name']    = 'Edge';
				$ret['version'] = $m[1];
			// Chrome
			} elseif (preg_match("#\) (Chrome|CriOS)/([\d.]+)#",$str,$m)) {
				$ret['name']    = 'Chrome';
				$ret['version'] = $m[2];
			// Internet Explorer
			} elseif (preg_match("|MSIE ([\d.abc]+);|",$str,$m)) {
				$ret['name']    = "Internet Explorer";
				$ret['version'] = $m[1];
			// Alternate Internet Explorere
			} elseif (preg_match("|Trident/([\d.]+);.*rv:([\d.]+)|",$str,$m)) {
				$ret['name']    = "Internet Explorer";
				$ret['version'] = $m[2];
			// Safari
			} elseif (preg_match("@\) Version/([\d.]+).*Safari@",$str,$m)) {
				$ret['name']    = "Safari";
				$ret['version'] = $m[1];
			// Old Opera
			} elseif (preg_match("|(Presto).*Version\/([\d.]+)|",$str,$m)) {
				$ret['name']    = 'Opera';
				$ret['version'] = $m[2];
			} elseif (preg_match("|(Opera) ([\d.]+)|",$str,$m)) {
				$ret['name']    = 'Opera';
				$ret['version'] = $m[2];
			} elseif (preg_match("/^(curl|libwww-perl|LWP::Simple)\/([\d.]+)/",$str,$m)) {
				$ret['name']    = $m[1];
				$ret['version'] = $m[2];
			}

			return $ret;
		}

		private function extract_engine($str) {
			$ret = array();

			if (preg_match("/\) (Trident|AppleWebKit|Presto)\/([\d\.]+)/",$str,$m)) {
				$ret['name']    = $m[1];
				$ret['version'] = $m[2];
			} elseif (preg_match("/; Trident\/([\d\.]+)/",$str,$m)) {
				$ret['name']    = 'Trident';
				$ret['version'] = $m[1];
			} elseif (preg_match("/rv:([\d\.]+)\) Gecko/",$str,$m)) {
				$ret['name']    = 'Gecko';
				$ret['version'] = $m[1];
			}

			return $ret;
		}

		private function var_set(&$value, $default = null) {
			if (isset($value)) {
				return $value;
			} else {
				return $default;
			}
		}

	} // End of class

}
