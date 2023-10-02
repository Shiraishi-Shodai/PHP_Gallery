<?php include "session.php";
//Viewer Page =========================================================
//Program		viewer.php
//=====================================================================
//設定
$pgm_id = basename(__FILE__);
include "config.php";
include "db_connect.php";
//---------------------------------------------------------------------
$id="";			//画像id
if (isset($_GET['id'])){	//パラメータ取得：表示する画像
	$id = $_GET['id'];
}
if ($id!=""){
// DB
	$result =	$conn->query(
					"select `id`,`title`,`author`,`date` from `photo` "
					."where `id`='" 
					.$id."'"
				);
	$conn = null;
	$row = $result->fetch();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html style="direction: ltr;" lang="ja">
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<title>Viewer Page <?php echo $id ?></title>
<meta name="author" content="h_k_sim">
<meta name="description" content="PHPによるWEBプログラミングの実習用に作成したギャラリーページ">
<meta name="keyword" content="PHP,プログラミング,実習,ギャラリー,投稿,表示">
</head>
<body>
<div align="center">
<h1>Viewer Page</h1>
<?php
if(!empty($_SESSION['name_sess'])){
	echo "ユーザ：".$_SESSION['name_sess']."<br>";
}
?>
<a href="#" onclick="history.go(-1)">戻る</a><br>
<hr>
<?php
if($id==""){
?>
No Image Found!
<?php
}else{
?>
<table>
<tbody>
<tr><td width='<?php echo $width_v; ?>' align='center' valign='top'>
<h2><?php echo $row['title']; ?></h2>
<img width='<?php echo $width_v; ?>' border='0' src='<?php echo $disp.'?id='.$row['id']; ?>'><br><br>
by <?php echo $row['author']; ?><br>
<?php echo date('y/m/d H:i',$row['date']); ?></td>
</tr>
</tbody>
</table>
<?php
}
?>
<hr>
<a href="#" onclick="history.go(-1)">戻る</a>
</div>
</body>
</html>