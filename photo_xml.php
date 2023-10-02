<?php 
include "db_connect.php";

$result =	$conn->query("select `id`,`title`,`author`,`date` from `photo`");
$conn = null;

$doc = new DOMDocument;
$root = $doc->createElement("photos");
$doc->appendChild($root);

while($row = $result->fetch()){
	$tpl = $doc->createElement("photo");
	foreach($row as $k => $v){
		if(is_numeric($k))continue;
		$elm = $doc->createElement(trim($k));
		$txt = $doc->createTextNode($v);
		$elm->appendChild($txt);
		$tpl->appendChild($elm);
	}
	$root->appendChild($tpl);
}

header("Content-type: text/xml");
echo $doc->saveXML();

?>