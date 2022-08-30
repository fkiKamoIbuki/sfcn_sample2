<?php
  require_once("../lib_cken/lib/util.php");
  $gobackURL = "comment_form.php";
  $gobackURL2 = "gr.php";
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
  if(!isset($_POST["scom"])||($_POST["scom"]==="")){
      $errors[] = "コメントが空です";
  }
  //エラーがあったとき
  if(count($errors)>0){
      echo '<ol class="error">';
      foreach ($errors as $value){
          echo "<li>", $value , "</li>";
      }
      echo "</ol>";
      echo "<hr>";
      ?>
      <form action="comment_form.php" method="POST">
            <input type="hidden" name="spot_id" value='<?=$_POST['spot_id']?>'>
            <input type="submit" value="戻る" id="button">
      </form>
      <?php
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
    $spot_id = $_POST["spot_id"];
    
  try {
    $pdo = new PDO($dsn, $user, $password);
    //プリペアドステートメントのエミュレーションを無効する
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //例外がスローされる設定にする
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "コメントを追加しました。" , "<br><hr>";
    //SQL文を作る
    $sql = "INSERT INTO spots (spot_id,user_id,pic_id,com_id,data_time) VALUES (:spot_id,:userid,(SELECT max(pic_id) FROM picture),(SELECT max(com_id) FROM comments),now())";
    $sql2 = "INSERT INTO comments (comment,user_id,datatime) VALUES (:sinfo,:userid,now())";
    $sql3 = "INSERT INTO picture (pic_content,user_id) VALUES (:simage,:userid)";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    $stm2 = $pdo->prepare($sql2);
    $stm3 = $pdo->prepare($sql3);
    //プレースホルダーに値をバインドする
    $stm->bindValue(':spot_id', $spot_id, PDO::PARAM_STR);
    $stm->bindValue(':userid', $userid, PDO::PARAM_STR);

    $stm2->bindValue(':sinfo', $sinfo, PDO::PARAM_STR);
    $stm2->bindValue(':userid', $userid, PDO::PARAM_STR);

    $stm3->bindValue(':simage', $simage, PDO::PARAM_STR);
    $stm3->bindValue(':userid', $userid, PDO::PARAM_STR);
    //SQL文を実行
    $stm3->execute();
    $stm2->execute();
    $stm->execute();

    echo "<a href=", $gobackURL2, ">戻る</a>";
//エラーが発生した場合の処理
} catch (Exception $e){
    echo '<span class="error">エラーがありました。</span><br>';
    echo $e->getMessage();
    exit();
}
?>