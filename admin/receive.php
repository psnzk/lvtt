<?php
//dezend by  QQ:2172298892
function appendParam($returnStr, $paramId, $paramValue)
{
	if ($returnStr != '') {
		if ($paramValue != '') {
			$returnStr .= '&' . $paramId . '=' . $paramValue;
		}
	}
	else if ($paramValue != '') {
		$returnStr = $paramId . '=' . $paramValue;
	}

	return $returnStr;
}

$key = '65ZS4C5WYKKLLGJN';
$version = $_GET['version'];
$signType = $_GET['signType'];
$merchantMbrCode = $_GET['merchantMbrCode'];
$requestId = $_GET['requestId'];
$userId = $_GET['userId'];
$userEmail = $_GET['userEmail'];
$userName = $_GET['userName'];
$orgName = $_GET['orgName'];
$ext1 = $_GET['ext1'];
$ext2 = $_GET['ext2'];
$applyResult = $_GET['applyResult'];
$errorCode = $_GET['errorCode'];
$signMsg = $_GET['signMsg'];
$$signMsgVal = '';
$signMsgVal = appendparam($signMsgVal, 'version', $version);
$signMsgVal = appendparam($signMsgVal, 'signType', $signType);
$signMsgVal = appendparam($signMsgVal, 'merchantMbrCode', $merchantMbrCode);
$signMsgVal = appendparam($signMsgVal, 'requestId', $requestId);
$signMsgVal = appendparam($signMsgVal, 'userId', $userId);
$signMsgVal = appendparam($signMsgVal, 'userEmail', $userEmail);
$signMsgVal = appendparam($signMsgVal, 'userName', urlencode($userName));
$signMsgVal = appendparam($signMsgVal, 'orgName', urlencode($orgName));
$signMsgVal = appendparam($signMsgVal, 'ext1', urlencode($ext1));
$signMsgVal = appendparam($signMsgVal, 'ext2', urlencode($ext2));
$signMsgVal = appendparam($signMsgVal, 'applyResult', $applyResult);
$signMsgVal = appendparam($signMsgVal, 'errorCode', $errorCode);
$signMsgVal = appendparam($signMsgVal, 'key', $key);
$mysignMsg = strtoupper(md5($signMsgVal));

if ($mysignMsg == $signMsg) {
	$status = '1';
	$signMsgVal = '';
	$signMsgVal = appendparam($signMsgVal, 'version', $version);
	$signMsgVal = appendparam($signMsgVal, 'signType', $signType);
	$signMsgVal = appendparam($signMsgVal, 'merchantMbrCode', $merchantMbrCode);
	$signMsgVal = appendparam($signMsgVal, 'requestId', $requestId);
	$signMsgVal = appendparam($signMsgVal, 'userId', $userId);
	$signMsgVal = appendparam($signMsgVal, 'status', $status);
	$reParam = $signMsgVal;
	$signMsgVal = appendparam($signMsgVal, 'key', key);
	$signMsg = strtoupper(md5($signMsgVal));
	$reParam .= '&signMsg=' . $signMsg;
	echo $reParam;
}
else {
	echo '验证错误';
}

?>
