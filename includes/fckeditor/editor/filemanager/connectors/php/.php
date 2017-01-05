<?php
//dezend by  QQ:2172298892
function SendError($number, $text)
{
	senduploadresults($number, '', '', $text);
}

require './config.php';
require './util.php';
require './io.php';
require './commands.php';
require './phpcompat.php';

if (!$Config['Enabled']) {
	senduploadresults('1', '', '', 'This file uploader is disabled. Please check the "editor/filemanager/connectors/php/config.php" file');
}

$sCommand = 'QuickUpload';
$sType = (isset($_GET['Type']) ? $_GET['Type'] : 'File');
$sCurrentFolder = getcurrentfolder();

if (!isallowedcommand($sCommand)) {
	senduploadresults('1', '', '', 'The ""' . $sCommand . '"" command isn\'t allowed');
}

if (!isallowedtype($sType)) {
	senduploadresults(1, '', '', 'Invalid type specified');
}

fileupload($sType, $sCurrentFolder, $sCommand);

?>
