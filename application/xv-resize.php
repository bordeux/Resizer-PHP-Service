<?php

include_once(dirname(__FILE__)."/config.php");
$ExplodeURL = explode("/", substr($xvConfig['PATH_INFO'],1));

$infoFile = pathinfo($ExplodeURL[3]);
$URLimage = base64_decode($infoFile["filename"]);

if (strpos($xvConfig['allowed'], strtolower($infoFile['extension'])) === false) {
	echo "Allowed extensions:".$xvConfig['allowed'];
	exit;
}


function mkdir_recursive($pathname, $mode = "0777"){
	is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname), $mode);
	return is_dir($pathname) || @mkdir($pathname, $mode);
}
$DirToSave = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR.'base64'.DIRECTORY_SEPARATOR;

include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'ResizeImage.class.php');
$image = new SimpleImage();
$image->load($URLimage);

switch (strtolower($ExplodeURL[1])) {
	case "resize":
		$WidthHeight = explode("x", strtolower($ExplodeURL[2]));
		$DirToSave .= "resize".DIRECTORY_SEPARATOR.((int) $WidthHeight[0]).'x'.((int) $WidthHeight[1]);
		$image->resize((int) $WidthHeight[0] , (int) $WidthHeight[1]);
		break;
	case "width":
		$DirToSave .= "width".DIRECTORY_SEPARATOR.((int) $ExplodeURL[2]);
		$image->resizeToWidth((int) $ExplodeURL[2]);
		break;
	case "height":
		$DirToSave .= "height".DIRECTORY_SEPARATOR.((int) $ExplodeURL[2]);
		$image->resizeToHeight((int) $ExplodeURL[2]);
		break;
	case "scale":
		$DirToSave .= "scale".DIRECTORY_SEPARATOR.((int) $ExplodeURL[2]);
		$image->scale((int) $ExplodeURL[2]);
		break;
}
$DirToSave .= DIRECTORY_SEPARATOR;
mkdir_recursive($DirToSave);
$image->save($DirToSave.$infoFile["basename"]);

header("Location: ?");

?>