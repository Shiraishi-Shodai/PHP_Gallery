<?php include "session.php";
//Uploader Page =======================================================
//Program		uploader.php
//=====================================================================
//設定
$pgm_id = basename(__FILE__);
include "config.php";
include "db_connect.php";
//---------------------------------------------------------------------
$title = "";
$author = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html style="direction: ltr;" lang="ja">
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<title>Uploader</title>
<meta name="author" content="h_k_sim">
<meta name="description" content="PHPによるWEBプログラミングの実習用に作成したアップロードフォーム">
<meta name="keyword" content="PHP,プログラミング,実習,フォーム,投稿,アップロード">
<style>
input.tx{
	width:320px;
}
</style>
</head>
<body>
<div align="center">
<h1>Uploader</h1>
<?php	//アップロード前の処理
if(empty($_SESSION['name_sess'])){
	echo "ログインしてください。";
	echo "<br><br><a href='",$gallery,"'>Gallery</a>";
}else{
	echo "ユーザ：".$_SESSION['name_sess']."<br><br>";
	if (empty($_FILES['userfile']['name'])) {
?>
<hr><table><tr><td align="left">
<form enctype="multipart/form-data" action="<?php echo $pgm_id; ?>" method="post">
<input name="MAX_FILE_SIZE" value="<?php echo $maxsize; ?>" type="hidden">
Upload File:<br>
<input class="tx" name="userfile" type="file" size="40"><br>
Title:<br>
<input class="tx" name="title" type="text" size="40"><br>
<br>
<div align="center">
<input value="Upload" type="submit">
</div>
</form>
</td></tr></table>
<?php	//アップロード後の処理
	} else {
		$filename = $_FILES['userfile']['name'];
		if (isset($_POST['title'])){
			$title=$_POST['title'];
		}else{
			$title=$filename;
		}
		$author=$_SESSION['name_sess'];
//一時ファイルからデータを読み取り
		if (!($img = file_get_contents($_FILES['userfile']['tmp_name']))){
			echo("<hr>Upload failed : Error_code=");		//失敗したらエラーコードを表示
			echo($_FILES['userfile']['error']."<br>");
		} else {
// DB
			$statement =	$conn->prepare(
							"insert into `photo`(`title`, `author`, `file`, `image`,`date`) "
							."values(:title, :author, :filename, :img, :date)" 
							);
//バインド機構による型と長さのチェック
			$statement->bindParam(':title',$title,PDO::PARAM_STR,32);
			$statement->bindParam(':author',$author,PDO::PARAM_STR,32);
			$statement->bindParam(':filename',$filename,PDO::PARAM_STR,32);
			$statement->bindParam(':img',$img,PDO::PARAM_LOB);	//バイナリのままバインド
			$date = time();	//変数に格納してバインドする
			$statement->bindParam(':date',$date,PDO::PARAM_INT);
			if($statement->execute())			//成功したらアップロードファイル名を表示
				echo("<hr><b> $filename </b> uploaded<br>");
			else								//失敗したらエラーコードを表示
				echo("<hr>Upload failed : DB Error");

			$conn = null;
		}
		unlink($_FILES['userfile']['tmp_name']);
?>
<br><br><form action="<?php echo $pgm_id; ?>" method="get"><input value="確認" type="submit"></form>
<?php
	}
}
?>
<hr><a href="<?php echo $gallery; ?>">Gallery</a><br>
</div>
</body>
</html>