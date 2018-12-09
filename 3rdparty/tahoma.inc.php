<?php

global $ckfile;
global $useragent;

$useragent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_1) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/12.0.1 Safari/605.1.15";
$ckfile = "/tmp/tahomacurlcookie";

function tahomaGetConfigurationData($userId, $userPassword) {
	// Juste utilisé par le bouton "send to dev"

	return tahomaGetModules($userId, $userPassword, 0);
}

function tahomaLogon($userId, $userPassword) {
	global $ckfile, $useragent;

	log::add('tahoma', 'debug', "logon with " . $userId . " and cookie " . $ckfile);

	$url = "https://www.tahomalink.com/enduser-mobile-web/enduserAPI/login";

	$postData = "userId=$userId&userPassword=$userPassword";

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true);

	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	// curl_setopt($ch, CURLOPT_REFERER, 'https://www.tahomalink.com/enduser-mobile-web/steer-html5-client/tahoma/');
	// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Origin: https://www.tahomalink.com'));

	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);

	$output = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	curl_close($ch);

	if (($output == "") || ($httpcode == 401)) {
		log::add('tahoma', 'debug', "new cookie - logon: ko: " . $httpcode . "(" . $retour . ")");
		return false;
	}

	log::add('tahoma', 'debug', "new cookie - logon: ok: " . $httpcode);
	return true;

}

function tahomaGetScenarios($userId, $userPassword, $decode = 1) {

	$url = "https://www.tahomalink.com/enduser-mobile-web/externalAPI/json/getActionGroups";

	$options = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => false,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $postData,
		CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
		CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
	);

	$output = tahomaExecCurlAndRetry($userId, $userPassword, $options);

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

	$url = "https://www.tahomalink.com/enduser-mobile-web/externalAPI/refreshAllStates";

	$options = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => false,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $postData,
		CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
		CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
	);

	$output = tahomaExecCurlAndRetry($userId, $userPassword, $options);

	if ($output == "") {
		echo "Invalid return";
	}

	$url = "https://www.tahomalink.com/enduser-mobile-web/externalAPI/json/getSetup?_=1434999539745";

	$options = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => false,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $postData,
		CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
		CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
	);

	$output = tahomaExecCurlAndRetry($userId, $userPassword, $options);

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

	$url = "https://www.tahomalink.com/enduser-mobile-web/externalAPI/json/cancelExecutions";

	log::add('tahoma', 'debug', "cancelExecutions: (" . $execId . ") from tahoma.inc");

	$postData = array('execId' => $execId);

	$options = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => false,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $postData,
		CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
		CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
	);

	$output = tahomaExecCurlAndRetry($userId, $userPassword, $options);

	log::add('tahoma', 'debug', "return http cancelExecutions: (" . $output . ")");

	if ($output == "") {
		echo "Invalid return";
		return "";
	}

	return "";
}

function tahomaSendCommandZ($userId, $userPassword, $deviceURL, $commandName, $parameters, $equipmentName = "Equipment") {

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
		log::add('tahoma', 'debug', "Invalid return");
		return "";
	} else {
		log::add('tahoma', 'debug', $output);
	}

	$outputJson = json_decode($output);
	return $outputJson->execId;
}

function tahomaSendCommand($userId, $userPassword, $deviceURL, $commandName, $parameters, $equipmentName = "Equipment") {

	log::add('tahoma', 'debug', "send command " . $commandName . " to " . $deviceURL);

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

	$options = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => false,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $postData,
		CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
		CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
	);

	$output = tahomaExecCurlAndRetry($userId, $userPassword, $options);

	if ($output != "") {
		log::add('tahoma', 'debug', "send command: " . $output);
	}

	$outputJson = json_decode($output);
	return $outputJson->execId;

}

function tahomaExecCurlAndRetry($userId, $userPassword, $options) {
	global $ckfile, $useragent;

	$ch = curl_init();
	curl_setopt_array($ch, $options);

	curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
	curl_setopt($ch, CURLOPT_USERAGENT, $useragent);

	$output = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	curl_close($ch);

	if (($output == "") || ($httpcode == 401)) {
		unlink($ckfile);

		log::add('tahoma', 'debug', "send command: ko: " . $httpcode . ". Will retry");

		if (!tahomaLogon($userId, $userPassword)) {
			return "";
		}

		$ch = curl_init();
		curl_setopt_array($ch, $options);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);

		$output = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		if (($output == "") || ($httpcode == 401)) {
			log::add('tahoma', 'debug', "send command bis: ko: " . $httpcode);
		}
	}

	return $output;
}

function tahomaExecAction($userId, $userPassword, $oid, $delay = 0) {

	log::add('tahoma', 'debug', "exec action " . $oid);

	$url = sprintf("https://www.tahomalink.com/enduser-mobile-web/externalAPI/json/scheduleActionGroup?oid=%s&delay=%d", $oid, $delay);

	$options = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => false,
		CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
		CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
	);

	$output = tahomaExecCurlAndRetry($userId, $userPassword, $optionns);

	if ($output == "") {
		echo "Invalid return";
		return "";
	}

	$outputJson = json_decode($output);
	return $outputJson->actionGroup;
}

?>
