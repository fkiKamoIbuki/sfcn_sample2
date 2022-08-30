<?php
  require_once("../lib_cken/lib/util.php");
  $gobackURL2 = "insertform.php";
  //$gobackURL2 = "index.php";
  // 文字エンコードの検証
  if (!cken($_POST)){
    $encoding = mb_internal_encoding();
    $err = "Encoding Error! The expected encoding is " . $encoding ;
    // エラーメッセージを出して、以下のコードをすべてキャンセルする
    exit($err);
  }
  // HTMLエスケープ（XSS対策）
  $_POST = es($_POST);
?>
<?php
  // エラーメッセージを入れる配列
  $errors = [];

  //簡単なエラー処理
  if(!isset($_POST["sname"])||($_POST["sname"]==="")){
      $errors[] = "スポット名がありません";
  }
  if(!isset($_POST["sinfo"])||($_POST["sinfo"]==="")){
    $errors[] = "スポット情報がありません";
  }
  //エラーがあったとき
  if(count($errors)>0){
      echo '<ol class="error">';
      foreach ($errors as $value){
          echo "<li>", $value , "</li>";
      }
      echo "</ol>";
      echo "<hr>";
      echo "<a href=", $gobackURL2, ">戻る</a>";
      exit();
  }
?>
<?php
    //データベースユーザ
    $user = 'testuser';
    $password = 'pw4testuser';
    //利用するデータベース
    $dbName = 'spot2';
    //MySQLサーバ
    $host = 'localhost:3306';
    //MySQLのDSN文字列
    $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";

    //POSTされた値を取り出す
    $sname = $_POST["sname"];
    $sinfo = $_POST["sinfo"];
    $userid = 1;
    //echo var_dump($_FILES);
    $simage = file_get_contents($_FILES['simage']['tmp_name']);
    //$corse = implode(",", $_POST["corse"]);
    //$hobby = implode(",", $_POST["suki"]);
    
  try {
    $pdo = new PDO($dsn, $user, $password);
    //プリペアドステートメントのエミュレーションを無効する
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //例外がスローされる設定にする
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "データを追加しました。" , "<br><hr>";
    //SQL文を作る
    $sql = "INSERT INTO spots (sname,user_id,com_id,data_time) VALUES (:sname,:userid,(SELECT (max(com_id)+1) FROM comments),now())";
    $sql2 = "INSERT INTO comments (comment,user_id,data_time,spot_id,pic_content) VALUES (:sinfo,:userid,now(),(SELECT max(spot_id) FROM spots),:simage)";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    $stm2 = $pdo->prepare($sql2);
    //プレースホルダーに値をバインドする
    $stm->bindValue(':sname', $sname, PDO::PARAM_STR);
    $stm->bindValue(':userid', $userid, PDO::PARAM_STR);

    $stm2->bindValue(':sinfo', $sinfo, PDO::PARAM_STR);
    $stm2->bindValue(':userid', $userid, PDO::PARAM_STR);
    $stm2->bindValue(':simage', $simage, PDO::PARAM_STR);
    //SQL文を実行
    $stm->execute();
    $stm2->execute();

    echo "<a href=", $gobackURL2, ">戻る</a>";
//エラーが発生した場合の処理
} catch (Exception $e){
    echo '<span class="error">エラーがありました。</span><br>';
    echo $e->getMessage();
    exit();
}
?>