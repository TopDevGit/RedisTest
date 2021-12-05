<?php
	require "predis/autoload.php";
	Predis\Autoloader::register();

	$xml = simplexml_load_file("./config.xml");
	$children = $xml->children();
	$data = [];
	foreach ($children as $child) {
		if ($child->getName() == "subdomains") {
			$domains = [];
			foreach ($child->children() as $domain){
				$domains[] = $domain->__toString();
			}
			//$data['subdomains'] ='['.implode(',', $domains).']';
			$data['subdomains'] =json_encode($domains);
		}else if ($child->getName() == 'cookies') {
			foreach ($child->children() as $cookie){
				$key = "cookie";
				foreach($cookie->attributes() as $attr){
					$key .= ':'.$attr;
				}
				$data[$key] = $cookie->__toString();
			}
		}
	}

	// set values
	$redis = new Predis\Client();
	foreach($data as $key => $value){
		$redis->set($key, $value);
	}

	$cnt = count($argv);
	if ($cnt > 1 && $argv[1] == "-v"){
		// print all keys...
		$path = "";
		if ($cnt > 2)
			$path = $argv[2];
		$path .= "keys.txt";
		$keys = $redis->keys("*");
		file_put_contents($path, join("\n",$keys));
	}
?>