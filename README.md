PHP Browser Agent
=================

A PHP library that parses a browser's user agent string and reports:

1. Browser name and version
2. Operating system name and version
3. Rendering engine name and version

Requirements
------------

PHP 8.2 or later.

Usage
-----

```php
<?php
require_once('agent.class.php');

$a = new BrowserAgent;

// Parse the current request's User-Agent header
$info = $a->get_agent_info();

print '<pre>' . print_r($info, true) . '</pre>';
```

To parse a string directly, pass it as the first argument:

```php
$info = $a->get_agent_info('Mozilla/5.0 (Windows NT 10.0; Win64; x64) ...');
```

API
---

```php
get_agent_info($agent = "", $piece = "", $opts = array())
```

- `$agent` - The user agent string to parse. Defaults to `$_SERVER['HTTP_USER_AGENT']`.
- `$piece` - Optional. Return only one part of the result, such as `'browser'`, `'os'`, or `'engine'`.
- `$opts` - Optional array of settings:
  - `round_version` (bool) - Trim the browser version to its first two parts, so `33.1.3.2024` becomes `33.1`.

Return value
------------

An array with these keys:

| Key            | Description                                              |
|----------------|----------------------------------------------------------|
| `browser`      | `name` and `version` of the browser                      |
| `os`           | `name` and `version` of the operating system             |
| `engine`       | `name` and `version` of the rendering engine             |
| `full`         | Human-readable summary, e.g. `Firefox 120.0 on Windows 10` |
| `agent_string` | The user agent string that was parsed                    |
| `confidence`   | Integer from 0 to 100 showing how many fields were found |

When a part cannot be detected, its sub-array is empty (for example `browser` is `array()`), and `full` is `Unknown` if no browser is found.

License
-------

This project is licensed under the GNU General Public License, version 2.0. See <https://www.gnu.org/licenses/old-licenses/gpl-2.0.html> for details.
