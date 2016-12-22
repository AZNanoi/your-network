<?php
// Put XML content into a string
$xmlstr = trim(ob_get_contents());
ob_end_clean();

// Check if there was an error
if (!is_null(error_get_last()))
{
	// There was an error, print it and exit
	echo utf8_decode($xmlstr);
	exit;
}

// Load the XML string into a DOMDocument
$xml = new DOMDocument;
$xml->loadXML($xmlstr);

// Make a DOMDocument for the XSL stylesheet
$xsl = new DOMDocument;

// See which user agent is connecting
$UA = getenv('HTTP_USER_AGENT');
if (preg_match('/Opera|Symbian|Motorola|Nokia|Siemens|Samsung|Ericsson|LG|NEC|SEC|MIDP|Windows CE/', $UA))
{
	// If a mobile phone (or Opera), use a wml stylesheet and set appropriate MIME type
	header('Content-type: text/vnd.wap.wml');
	$xsl->load('index-wml.xsl');
}
else
{
	// If not a mobile phone, use a html stylesheet
	header('Content-type: text/html');
	$xsl->load('yn-feed-rss.xsl');
}
$userName=$_SESSION["yn-userName"];
$userID=$_SESSION["userID"];
$patterns = '/\s/';
$replacements = '%20';
$userName = preg_replace($patterns, $replacements, $userName);
// Make the transformation and print the result
$proc = new XSLTProcessor;
$proc->importStyleSheet($xsl); // Attach the XSL rules
$proc->setParameter('', 'userName', $userName);
$proc->setParameter('', 'userID', $userID);
echo utf8_decode($proc->transformToXML($xml));
?>
