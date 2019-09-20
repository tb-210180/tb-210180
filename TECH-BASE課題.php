<html lang = "ja">

<head>
<meta chrset = "utf-8">
<title>mission_5</title>
</head>
<body>
<h2>mission_5</h2>


<?php

//アドバイス
//3-5課題のファイルを開いて実行する部分を4の課題のデータベース関連の関数に書き換えれば大体、大丈夫です。アドバイスとしてsql文はかなり気て作成したほうがいいかと??。sql文に半角のスペースがないだけで、エラーをはかないのにうまくいかない、という結果になってどこをなおせばいいのかわからなくなりました

//前提
$name = @$_POST["name"];
$comment = @$_POST["comment"];
$pass = @$_POST["pass"];
$soushin = @$_POST["soushin"];
$delete = @$_POST["delete"];
$edit = @$_POST["edit"];
$now = date("Y-m-d H:i:s");
$value = @$_POST["value"];

//mission_4-2を利用
$dsn ='データベース名';
//$dsnの式の中にスペースを入れないこと！
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//データベース内にテーブルを作成する。<br>
//上記の際にはcreateコマンドを使用する。<br>
$sql = "CREATE TABLE IF NOT EXISTS new3tbtest"
//これがないと既存のテーブルを作成することになり　エラーが発生する。<br>
."("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "pass TEXT,"
. "now TEXT"
.");";
$stmt = $pdo -> query($sql);



//3-5はより複雑な内容である。条件分岐を簡潔にするため、switch文を用いる。
switch($value):
	//送信ボタンが押された場合。その文章は動作に支障をきたした場合は消すこと。
	case "送信":
		if(empty($name) && !empty($comment) && !empty($pass)){
			echo "名前を入力して下さい。"."<br>"."<br>";
		}elseif(!empty($name) && empty($comment) && !empty($pass)){
			echo "コメントを入力して下さい。"."<br>"."<br>";
		}elseif(!empty($name) && !empty($comment) && empty($pass)){
			echo "パスワードを入力して下さい。"."<br>"."<br>";
		}elseif(empty($name) && empty($comment) && !empty($pass)){
			echo "名前とコメントを入力して下さい。"."<br>"."<br>";
		}elseif(empty($name) && !empty($comment) && empty($pass)){
			echo "名前とパスワードを入力して下さい。"."<br>"."<br>";
		}elseif(!empty($name) && empty($comment) && empty($pass)){
			echo "コメントとパスワードを入力して下さい。"."<br>"."<br>";
		}elseif(empty($name) && empty($comment) && empty($pass)){
			echo "名前とコメントとパスワードを入力して下さい。"."<br>"."<br>";
		//新規投稿の際の動作。
		}elseif(!empty($name)&&!empty($comment)&&empty($soushin)&&!empty($pass)){
			$dsn = 'データベース名';
			$user = 'ユーザー名';
			$password = 'パスワード';
			$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
			//作成したテーブルに、insertを行ってデータを入力する。
			$sql = $pdo -> prepare("INSERT INTO new3tbtest (name, comment, pass, now) VALUES (:name, :comment, :pass, :now)");
			$sql -> bindParam(':name', $name, PDO::PARAM_STR);
			$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
			$sql -> bindParam(':now', $now, PDO::PARAM_STR);
			$sql -> execute();

		//編集投稿の際の動作。
		}elseif(!empty($name) && !empty($comment) && !empty($soushin) && !empty($pass)){
			//mission_4-7を利用。
			$dsn ='データベース名';
			//$dsnの式の中にスペースを入れないこと！
			$user = 'ユーザー名';
			$password = 'パスワード';
			$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


			//変更する投稿番号
			$id = @$soushin;
			//変更したい名前、変更したいコメントは自分で決めること

			$sql = 'update new3tbtest set name=:name,comment=:comment,pass=:pass,now=:now where id=:id';
			$stmt = $pdo->prepare($sql);
			//PDO::PARAM_STRやINT	指定すると型が変わる。
			//一個目で :name のようにさっき与えたパラメータを指定。
			//２個目に、それに入れる変数を指定します。bindParam には直接数値を入れれない。変数のみです。
			//３個目で型を指定。PDO::PARAM_STR は「文字列だよ」って事
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
			$stmt->bindParam(':now', $now, PDO::PARAM_STR);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
		}
	break;

	case "削除"://削除フォームの場合
		if(empty($delete) && !empty($pass)){
			echo "削除したい投稿番号を入力してください。"."<br>"."<br>";
		}elseif(!empty($delete) && empty($pass)){
			echo "パスワードを入力してください。"."<br>"."<br>";
		}elseif(empty($delete) && empty($pass)){
			echo "削除したい投稿番号とパスワードを入力してください。"."<br>"."<br>";
		}elseif(!empty($delete) && !empty($pass)){
			$dsn ='データベース名';
			//$dsnの式の中にスペースを入れないこと！
			$user = 'ユーザー名';
			$password = 'パスワード';
			$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

			$sql = 'SELECT * FROM new3tbtest';
			$stmt = $pdo->query($sql);
			$results = $stmt->fetchAll();
			foreach ($results as $row):
				if($row['id'] == $delete && $row['pass'] == $pass){
					//mission_4-8を利用
					$dsn ='データベース名';
					//$dsnの式の中にスペースを入れないこと！
					$user = 'ユーザー名';
					$password = 'パスワード';
					$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

					$id = @$delete;
					$sql = 'delete from new3tbtest where id=:id';
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->execute();
				}elseif($row['id'] == $delete && $row['pass'] != $pass){
					echo "パスワードに誤りがあります。";
				}
			endforeach;
		}
		//ここの}がエラーの原因か確かめるために削除する
		//削除したらbreakに関してエラーが出た
	break;

	case "編集"://編集フォームの場合
		if(empty($edit) && !empty($pass)){
			echo "編集したい投稿番号を入力して下さい。"."<br>"."<br>";
		}elseif(!empty($edit) && empty($pass)){
			echo "パスワードを入力して下さい。"."<br>"."<br>";
		}elseif(empty($edit) && empty($pass)){
			echo "編集したい投稿番号とパスワードを入力して下さい。"."<br>"."<br>";
		}elseif(!empty($edit) && !empty($pass)){
			$dsn ='データベース名';
			//$dsnの式の中にスペースを入れないこと！
			$user = 'ユーザー名';
			$password = 'パスワード';
			$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

			$sql = 'SELECT * FROM new3tbtest';
			$stmt = $pdo->query($sql);
			$results = $stmt->fetchAll();
			foreach ($results as $row):
				if($row['id'] == $edit && $row['pass'] == $pass){
					$editnum = $row['id'];
					$editname = $row['name'];
					$editcomment = $row['comment'];
				}elseif($row['id'] == $edit && $row['pass'] != $pass){
					echo "パスワードに誤りがあります。";
				}
			endforeach;
		}
	break;
endswitch;
?>






<form action = "mission_5-soudan.php" method = "POST">
	【投稿フォーム】<br>
	<td>名前　　　：</td><input type = "text" name = "name" placeholder = "名前" value = <?php echo @$editname; ?> > <br>
	<td>コメント　：</td><input type = "text" name = "comment" placeholder = "コメント" value = <?php echo @$editcomment; ?> > <br>
	<td>パスワード：</td><input type = "text" name = "pass" placeholder = "パスワード"><br>
	<input type = "submit" name = "value" value = "送信"> <br>
	<input type = "hidden" name = "soushin" value = <?php echo @$editnum; ?> ><br>
</form>

<form action = "mission_5-soudan.php" method = "POST">
	【削除フォーム】<br>
	<td>投稿番号　：</td><input type = "text" name = "delete" placeholder = "削除対象番号"> <br>
	<td>パスワード：</td><input type = "text" name = "pass" placeholder = "パスワード"><br>
	<input type = "submit" name = "value" value  = "削除"> <br>
</form><br>

<form action = "mission_5-soudan.php" method = "POST">
	【編集フォーム】<br>
	<td>投稿番号　：</td><input type = "text" name = "edit" placeholder = "編集対象番号"> <br>
	<td>パスワード：</td><input type = "text" name = "pass" placeholder = "パスワード"><br>
	<input type = "submit" name = "value" value = "編集"> <br>

</form>
<br><br>
</html>






<?php
//mission_4-6をしてし利用して表示。
$dsn ='データベース名';
//$dsnの式の中にスペースを入れないこと！
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = 'SELECT * FROM new3tbtest';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].' ';
	echo $row['name'].' ';
	echo $row['comment'].' ';
	echo $row['now'].'<br>';
	echo "<hr>";
}
?>


