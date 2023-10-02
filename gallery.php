<?php include "session.php";
//Gallery Page ========================================================
//Program		gallery.php
//=====================================================================
//設定
$pgm_id = basename(__FILE__);
include "config.php";
include "db_connect.php";
//---------------------------------------------------------------------
function roundup($a){
	$b=(integer)$a;
	if($a>$b) $b++;
	return $b;
}
//---------------------------------------------------------------------
$sort=0;			//日付の新しい順
if (isset($_GET['sort'])){	//パラメータ取得：表示するページ
	$sort = $_GET['sort'];
}
$page=0;			//ページ番号（０から開始）
if (isset($_GET['page'])){	//パラメータ取得：表示するページ
	$page = $_GET['page'];
}
$sort_o = array(	//ソート
			"date desc",
			"date asc",
			"title asc",
			"author asc");
$sort_t = array(	//ソートタイトル
			"新着",
			"日付",
			"タイトル",
			"投稿者");
$pg_link = "Sort: ".$sort_t[$sort]."-&gt;";//各頁へのリンク作成：変数に格納

// DB
$result =	$conn->query("select count(*) from `photo`");
$rows = $result->fetchColumn();
$result =	$conn->query(
				"select `id`,`title`,`author`,`date` from `photo` "
				."order by " 
				.$sort_o[$sort]
				." limit ". $col*$row*$page
				.", ". $col*$row
			);
$conn　= null;

$pg = roundup($rows/($col*$row));	//ページ数計算（切り上げ）
for($i=($page>$dp_pg?$page-$dp_pg:0);
	$i<($page+$dp_pg+1<$pg?$page+$dp_pg+1:$pg);
	$i++){
	$pg_link	.= '<a href="'
			. $pgm_id
			. '?page='
			. $i
			. '&sort='
			. $sort
			. '">['
			. ($i + 1)
			. ']</a> ';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html style="direction: ltr;">
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<title>Gallery Page <?php echo $page+1 ?></title>
<meta name="author" content="h_k_sim">
<meta name="description" content="PHPによるWEBプログラミングの実習用に作成したギャラリーページ">
<meta name="keyword" content="PHP,プログラミング,実習,ギャラリー,投稿,表示">
<style>
td{
 border:  1px solid #cccccc;
}
</style>
</head>
<body>
<div align="center">
<h1>Gallery Page <?php echo $page+1 ?></h1>
<?php
if(!empty($_SESSION['name_sess'])){
	echo "ユーザ：".$_SESSION['name_sess']."<br>";
?>
<a href="<?php echo $login; ?>?sort=0">ログアウト</a><br><br>
<?php
}else{
?>
<a href="<?php echo $login; ?>?sort=0">ログイン</a><br><br>
<?php
}
?>
[<a href="<?php echo $pgm_id; ?>?sort=0">新着順</a>]
[<a href="<?php echo $pgm_id; ?>?sort=1">日付順</a>]
[<a href="<?php echo $pgm_id; ?>?sort=2">タイトル順</a>]
[<a href="<?php echo $pgm_id; ?>?sort=3">投稿者順</a>]
<br>
<br>
<?php echo $pg_link; ?>
<hr>
<table>
<tbody>
<?php		//セル内へ画像とリンクを書き出し
$num = 0;	//ページ内の画像表示数
while($r = $result->fetch()){
	$num ++;
	if($num % $col == 1) echo "<tr>";		//左端のセル
	echo "<td width='$width' align='center' valign='top'>";
	echo "<a href='$viewer?id=".$r['id']."'>";
	echo "<img width='$width' border='0' src='$disp?id=",
			$r['id'], "'></a><br>";
	echo $r['title'], "<br>by ", $r['author'], "<br>",
			date('y/m/d H:i:s',$r['date']), "</td>\n";
	if($num % $col == 0) echo "</tr>";		//右端のセル
}
if ($num %$col > 0) echo "</tr>";			//行の途中で終わった場合
?>
</tbody>
</table>
<hr>
<?php echo $pg_link;
if(isset($_SESSION['name_sess']))
	echo "\n<br><br><a href='$uploader'>Upload</a>";
?>
</div>
</body>
</html>