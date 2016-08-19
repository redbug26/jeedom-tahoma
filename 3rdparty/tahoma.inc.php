<?php

function tahomaGetConfigurationData($userId, $userPassword) {
	// Juste utilisé par le bouton "send to dev"

	return tahomaGetModules($userId, $userPassword, 0);
}

function tahomaGetScenarios($userId, $userPassword, $decode = 1) {
	$url = "https://www.tahomalink.com/enduser-mobile-web/enduserAPI/login";

	$postData = "userId=$userId&userPassword=$userPassword";

	$ckfile = tempnam("/tmp", "CURLCOOKIE");

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);

	$output = curl_exec($ch);

	curl_close($ch);

	if ($output == "") {
		echo "Invalid return";
	}

	$url = "https://www.tahomalink.com/enduser-mobile-web/externalAPI/json/getActionGroups";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

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

	$url = "https://www.tahomalink.com/enduser-mobile-web/enduserAPI/login";

	$postData = "userId=$userId&userPassword=$userPassword";

	$ckfile = tempnam("/tmp", "CURLCOOKIE");

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);

	$output = curl_exec($ch);

	curl_close($ch);

	if ($output == "") {
		echo "Invalid return";
	}

/*
$url="https://www.tahomalink.com/enduser-mobile-web/enduserAPI/setup/devices/states/refresh";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);


$output=curl_exec($ch);
 */

	$url = "https://www.tahomalink.com/enduser-mobile-web/externalAPI/refreshAllStates";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

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

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

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
	$url = "https://www.tahomalink.com/enduser-mobile-web/enduserAPI/login";

	$postData = "userId=$userId&userPassword=$userPassword";

	$ckfile = tempnam("/tmp", "CURLCOOKIE");

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);

	$output = curl_exec($ch);

	curl_close($ch);

	if ($output == "") {
		echo "Invalid return";
	}

	$url = "https://www.tahomalink.com/enduser-mobile-web/externalAPI/json/cancelExecutions";
//	$url = "https://www.tahomalink.com/enduser-mobile-web/enduserAPI//exec/cancelExecutions";

	log::add('tahoma', 'debug', "cancelExecutions: (" . $execId . ")");

	$postData = array('execId' => $execId);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

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

	$url = "https://www.tahomalink.com/enduser-mobile-web/enduserAPI/login";

	$postData = "userId=$userId&userPassword=$userPassword";

	$ckfile = tempnam("/tmp", "CURLCOOKIE");

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);

	$output = curl_exec($ch);

	curl_close($ch);

	if ($output == "") {
		echo "Invalid return";
	}

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
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

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

	$url = "https://www.tahomalink.com/enduser-mobile-web/enduserAPI/login";

	$postData = "userId=$userId&userPassword=$userPassword";

	$ckfile = tempnam("/tmp", "CURLCOOKIE");

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);

	$output = curl_exec($ch);

	curl_close($ch);

	if ($output == "") {
		echo "Invalid return";
	}

	$url = sprintf("https://www.tahomalink.com/enduser-mobile-web/externalAPI/json/scheduleActionGroup?oid=%s&delay=%d", $oid, $delay);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");

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