<?PHP

require('agent.class.php');

/////////////////////////////////////////////////////

$a = new BrowserAgent;

$str  = $_GET['agent'];
$info = $a->get_agent_info($str);

$out = print_r($info,1);
print "<pre>$out</pre>";
