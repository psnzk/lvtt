<?php
//dezend by  QQ:2172298892
function DoResponse()
{
	if (!isset($_GET)) {
		global $_GET;
	}

	if (!isset($_GET['Command']) || !isset($_GET['Type']) || !isset($_GET['CurrentFolder'])) {
		return NULL;
	}

	$sCommand = $_GET['Command'];
	$sResourceType = $_GET['Type'];
	$sCurrentFolder = getcurrentfolder();

	if (!isallowedcommand($sCommand)) {
		senderror(1, 'The "' . $sCommand . '" command isn\'t allowed');
	}

	if (!isallowedtype($sResourceType)) {
		senderror(1, 'Invalid type specified');
	}

	if ($sCommand == 'FileUpload') {
		fileupload($sResourceType, $sCurrentFolder, $sCommand);
		return NULL;
	}

	createxmlheader($sCommand, $sResourceType, $sCurrentFolder);

	switch ($sCommand) {
	case 'GetFolders':
		getfolders($sResourceType, $sCurrentFolder);
		break;

	case 'GetFoldersAndFiles':
		getfoldersandfiles($sResourceType, $sCurrentFolder);
		break;

	case 'CreateFolder':
		createfolder($sResourceType, $sCurrentFolder);
		break;
	}

	createxmlfooter();
	exit();
}

ob_start();
require './config.php';
require './util.php';
require './io.php';
require './basexml.php';
require './commands.php';
require './phpcompat.php';

if (!$Config['Enabled']) {
	senderror(1, 'This connector is disabled. Please check the "editor/filemanager/connectors/php/config.php" file');
}

doresponse();

?>
