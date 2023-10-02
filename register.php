<?php include "session.php";
//Register Page =======================================================
//Program		register.php
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
<title>Register</title>
<meta name="author" content="h_k_sim">
<meta name="description" content="PHPによるWEBプログラミングの実習用に作成したギャラリーページ">
<meta name="keyword" content="PHP,プログラミング,実習,ギャラリー,投稿,表示">
</head>
<body>
<div align="center">
<h1>Register</h1>
<?php
if(!empty($_SESSION['name_sess'])){
	echo "ログイン中：",$_SESSION['name_sess'];
	echo "<br><br><a href='",$gallery,"'>Gallery</a>";
}elseif(! empty($_POST['name'])){
//POSTで受け取ったユーザ名がある場合（名前登録時）
//受け取った名前をセッションに代入
	$statement =	$conn->prepare("select count(*) from `users` "
							."where (`u_name` = ? )"
				);
	$statement->bindParam(1,$_POST['name']);
	$statement->execute();
	$rows = $statement->fetchColumn();
	if($rows==0){
		$statement = $conn->prepare(
					"insert into `users`(`u_name`, `u_pass`) values( ?, ? )");
		$statement->bindParam(1,$_POST['name']);
		$statement->bindParam(2,$_POST['pass']);
		if ($statement->execute()){
			echo "登録完了：".$_POST['name'];
		}else{
			echo "登録失敗";
			echo "<br><br><a href='./",$pgm_id,"'>再試行</a>";
		}
	}else{
		echo "ユーザ名・パスワードを正しく入力してください。";
		echo "<br><br><a href='./",$pgm_id,"'>再試行</a>";
	}
	echo "<br><br><a href='",$login,"'>ログイン</a>";
	echo "<br><br><a href='",$gallery,"'>Gallery</a>";
	$conn = null;
}else{
//名前のセッションデータがない場合（一番最初）
//form で名前を入力する欄を表示
?>
	<hr><table><tr><td align="right">
    <form method="POST" action="<?php echo $pgm_id; ?>">
        ユーザー名<input type="text" name="name"><br>
        パスワード<input type="text" name="pass"><br><br>
        <input type="submit" value="登録">
    </form>
	</td></tr></table><hr>
<?php
	echo "<br><br><a href='",$login,"'>ログイン</a>";
	echo "<br><br><a href='",$gallery,"'>Gallery</a>";
}
?>
</div>
</body>
</html>