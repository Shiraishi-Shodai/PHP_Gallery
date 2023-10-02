<?php
//=====================================================================
//Program		dp_img.php
//=====================================================================
include "db_connect.php";
//---------------------------------------------------------------------
$result =	$conn->query(
				"select * from `photo` where(`id`='"
				.$_GET['id']
				."')"
			);
if($row = $result->fetch()){
	if (PHP_VERSION<5.3){
// php4 >= 4.3.0 and php5 < 5.3.0
		header("Content-type: ".mime_content_type($row['image'])); //画像データの一部を見てファイルの種類を判断
	}else{
// php5 >= 5.3.0
		$finfo = new finfo(FILEINFO_MIME);
		header("Content-type: ".$finfo->buffer($row['image']));  //画像データの一部を見てファイルの種類を判断
	}
	if($row['file']!=''){
		//Content-DispositionはMIMEタイプを指定するとwebに表示させるように設定できる。ダウンロードさせることもできる
		//ここではダウンロードする際のファイル名を指定している
		header("Content-Disposition: Filename=".$row['file']); 
	}else{
		header("Content-Disposition: Filename=".$row['id']);
	}
	echo $row['image']; //レスポンスメッセージのbody部分に格納(printにすると標準出力になってしまう)
	// レスポンスのbodyにバイナリを格納すると、自動でbase64にエンコーディングしてくれる

}else{
	echo "<HTML>\n<HEAD><TITLE>File not Found</TITLE></HEAD>\n";
	echo "<BODY>\nFile Not Found.<BR>";
	echo "Please Check Link or Argument and Reload.<BR>";
	echo "<HR>Standard Document Archive.<BR>";
	echo "</BODY>\n</HTML>";
}
?>
