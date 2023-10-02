<?php include "session.php";
//Login/Logout Page ===================================================
//Program		login.php
//=====================================================================
//設定
$pgm_id = basename(__FILE__);
include "config.php";
include "db_connect.php";
//---------------------------------------------------------------------
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html style="direction: ltr;">
<head>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<title>Login</title>
<meta name="author" content="h_k_sim">
<meta name="description" content="PHPによるWEBプログラミングの実習用に作成したギャラリーページ">
<meta name="keyword" content="PHP,プログラミング,実習,ギャラリー,投稿,表示">
</head>
<body>
<div align="center">
<h1>Login</h1>
<?php
if(isset($_POST['name'])){
//POSTで受け取ったユーザ名がある場合（名前登録時） 受け取った名前をセッションに代入
	$statement =	$conn->prepare("select count(*) from `users` "
							."where (`u_name` = ? ) "
							."and (`u_pass` = ? )"
				);
	$statement->bindParam(1, $_POST['name']);
	$statement->bindParam(2, $_POST['pass']);
	$statement->execute();

	$rows = $statement->fetchColumn();
	if($rows>0){
		$_SESSION['name_sess'] = $_POST['name'];
	}else{
		echo "ユーザ名・パスワードを正しく入力してください。";
	}
	$conn = null;
}

if(isset($_POST['logout'])){	//POSTで受け取ったログアウトデータがある場合（ログアウト時）ログアウトの処理  
	session_destroy();				//セッションデータを破棄する
	unset($_SESSION['name_sess']);	//セッション変数を消去
}
if(empty($_SESSION['name_sess'])) {	//名前のセッションデータがない場合（一番最初）　form で名前を入力する欄を表示
?>
	<hr><table><tr><td align="right">
    <form method="POST" action="<?php echo $pgm_id; ?>">
        ユーザー名<input type="text" name="name"><br>
        パスワード<input type="text" name="pass"><br><br>
        <input type="submit" value="ログイン">
    </form>
	</td></tr></table><hr>
<?php
	echo "<br><br><a href='",$register,"'>新規登録</a>";
}else{
    $name_show = $_SESSION['name_sess'];	//セッションからデータを受け取る
    echo "ユーザ：",$name_show,"<br><br>\n";
?>
	<hr><table><tr><td align="right">
    <form method="POST" action="<?php echo $pgm_id; ?>">
        <input type="hidden" name="logout" value="0">
        <input type="submit" value="ログアウト">
    </form>
	</td></tr></table><hr>
<?php
}
echo "<br><br><a href='./",$gallery,"'>Gallery</a>";
?>
</div>
</body>
</html>