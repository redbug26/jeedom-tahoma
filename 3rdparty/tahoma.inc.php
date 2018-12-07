<?php

$logged = false;
$ckfile = tempnam("/tmp", "CURLCOOKIE");
$useragent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_1) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.0.1 Safari/605.1.15";

function tahomaGetConfigurationData($userId, $userPassword) {
	// Juste utilisé par le bouton "send to dev"

	return tahomaGetModules($userId, $userPassword, 0);
}

function tahomaLogon($userId, $userPassword) {
	global $logged, $ckfile, $useragent;

	if (!$logged) {

		$url = "https://www.tahomalink.com/enduser-mobile-web/enduserAPI/login";

		$postData = "userId=$userId&userPassword=$userPassword";

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);

		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
		curl_setopt($ch, CURLOPT_REFERER, 'https://www.tahomalink.com/enduser-mobile-web/steer-html5-client/tahoma/');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Origin: https://www.tahomalink.com'));

		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);

		$output = curl_exec($ch);

		curl_close($ch);

		if ($output == "") {
			echo "Invalid return";
		}

		$logged = true;
	}

}

function tahomaGetScenarios($userId, $userPassword, $decode = 1) {
	global $ckfile, $useragent;

	tahomaLogon($userId, $userPassword);

	$url = "https://www.tahomalink.com/enduser-mobile-web/externalAPI/json/getActionGroups";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch, CURLOPT_REFERER, 'https://www.tahomalink.com/enduser-mobile-web/steer-html5-client/tahoma/');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Origin: https://www.tahomalink.com'));

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);

	$output = curl_exec($ch);

	curl_close($ch);

	if ($output == "") {
		echo "Invalid return";
	}

	if ($decode == 0) {
		return $output;
	}

	$tahoma = json_decode($output);

	return $tahoma->actionGroups;

}

function tahomaGetModules($userId, $userPassword, $decode = 1) {
	global $ckfile, $useragent;

	tahomaLogon($userId, $userPassword);

	$url = "https://www.tahomalink.com/enduser-mobile-web/externalAPI/refreshAllStates";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch, CURLOPT_REFERER, 'https://www.tahomalink.com/enduser-mobile-web/steer-html5-client/tahoma/');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Origin: https://www.tahomalink.com'));

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);

	$output = curl_exec($ch);

	curl_close($ch);

	if ($output == "") {
		echo "Invalid return";
	}

	$url = "https://www.tahomalink.com/enduser-mobile-web/externalAPI/json/getSetup?_=1434999539745";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch, CURLOPT_REFERER, 'https://www.tahomalink.com/enduser-mobile-web/steer-html5-client/tahoma/');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Origin: https://www.tahomalink.com'));

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);

	$output = curl_exec($ch);

	curl_close($ch);

	if ($output == "") {
		echo "Invalid return";
	}

	if ($decode == 0) {
		return $output;
	}

	$tahoma = json_decode($output);

	return $tahoma->setup->devices;
}

function tahomaCancelExecutions($userId, $userPassword, $execId) {
	global $ckfile, $useragent;

	tahomaLogon($userId, $userPassword);

	$url = "https://www.tahomalink.com/enduser-mobile-web/externalAPI/json/cancelExecutions";

	log::add('tahoma', 'debug', "cancelExecutions: (" . $execId . ")");

	$postData = array('execId' => $execId);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch, CURLOPT_REFERER, 'https://www.tahomalink.com/enduser-mobile-web/steer-html5-client/tahoma/');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Origin: https://www.tahomalink.com'));

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);

	$output = curl_exec($ch);

	curl_close($ch);

	log::add('tahoma', 'debug', "return http cancelExecutions: (" . $output . ")");

	if ($output == "") {
		echo "Invalid return";
		return "";
	}

	return "";
}

function tahomaSendCommand($userId, $userPassword, $deviceURL, $commandName, $parameters, $equipmentName = "Equipment") {
	global $ckfile, $useragent;

	tahomaLogon($userId, $userPassword);

	$url = "https://www.tahomalink.com/enduser-mobile-web/enduserAPI//exec/apply";

	$action["deviceURL"] = $deviceURL;

	$command["name"] = $commandName;

	if ($parameters != "") {
		$command["parameters"] = $parameters; // array(100);
	}

	$commands[] = $command;

	$action["commands"] = $commands;

	$actions[] = $action;

	$row["label"] = $equipmentName;
	$row["actions"] = $actions;

	$postData = json_encode($row);

	log::add('tahoma', 'debug', $postData);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch, CURLOPT_REFERER, 'https://www.tahomalink.com/enduser-mobile-web/steer-html5-client/tahoma/');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Origin: https://www.tahomalink.com', 'Content-Type: application/json'));

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);

	$output = curl_exec($ch);

	curl_close($ch);

	if ($output == "") {
		echo "Invalid return";
		return "";
	}

	$outputJson = json_decode($output);
	return $outputJson->execId;
}

function tahomaExecAction($userId, $userPassword, $oid, $delay = 0) {
	global $ckfile, $useragent;

	tahomaLogon($userId, $userPassword);

	$url = sprintf("https://www.tahomalink.com/enduser-mobile-web/externalAPI/json/scheduleActionGroup?oid=%s&delay=%d", $oid, $delay);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch, CURLOPT_REFERER, 'https://www.tahomalink.com/enduser-mobile-web/steer-html5-client/tahoma/');
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Origin: https://www.tahomalink.com', 'Content-Type: application/json'));

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);

	$output = curl_exec($ch);

	curl_close($ch);

	if ($output == "") {
		echo "Invalid return";
		return "";
	}

	$outputJson = json_decode($output);
	return $outputJson->actionGroup;
}

?>