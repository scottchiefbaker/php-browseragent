PHP Browser Agent
=================

This library parses the browser user agent string to determine
information the following:

1. Browser name/version
2. Operating system name/version
3. Rendering engine name/version

Usage
-----

```PHP
require('agent.class.php');

$a = new BrowserAgent;

$info = $a->get_agent_info();

$out = print_r($info,1);
print "<pre>$out</pre>";
```
