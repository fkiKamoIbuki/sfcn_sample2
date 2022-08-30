<?php
  require_once("../lib_cken/lib/util.php");
  $gobackURL2 = "plasspot_form.php";
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
    $sinfo = $_POST["sinfo"];
    $spot_id = $_POST["spot_id"];
    $email = $_POST["email"];
    $userid = 1;
    //echo var_dump($_FILES);
    if (!empty($_FILES['simage']['name'])) {
      $simage = file_get_contents($_FILES['simage']['tmp_name']);
    }else {
      $simage = "";
    }
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
    //$sql = "INSERT INTO spots (spot_id,user_id,pic_id,com_id,data_time) VALUES (:spot_id,:userid,(SELECT max(pic_id) FROM picture),(SELECT max(com_id) FROM comments),now())";
    $sql2 = "INSERT INTO comments (comment,user_id,data_time,spot_id,pic_content) VALUES (:sinfo,:userid,now(),:spot_id,:simage)";
    //$sql3 = "INSERT INTO picture (pic_content,user_id,spot_id) VALUES (:simage,:userid,:spot_id)";
    //プリペアドステートメントを作る
    //$stm = $pdo->prepare($sql);
    $stm2 = $pdo->prepare($sql2);
    //$stm3 = $pdo->prepare($sql3);
    //プレースホルダーに値をバインドする
    //$stm->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    //$stm->bindValue(':userid', $userid, PDO::PARAM_STR);

    $stm2->bindValue(':sinfo', $sinfo, PDO::PARAM_STR);
    $stm2->bindValue(':userid', $userid, PDO::PARAM_STR);
    $stm2->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    if($simage === ""){
      $stm2->bindValue(':simage',"",PDO::PARAM_STR);
    }else{
      $stm2->bindValue(':simage', $simage, PDO::PARAM_STR);
    }

    //$stm3->bindValue(':simage', $simage, PDO::PARAM_STR);
    //$stm3->bindValue(':userid', $userid, PDO::PARAM_STR);
    //$stm3->bindValue(':spot_id', $spot_id, PDO::PARAM_INT);
    //SQL文を実行
    //$stm3->execute();
    $stm2->execute();
    $to = "fki2166225@stu.o-hara.ac.jp";
    $subject = "TEST";
    $message = "This is TEST.\r\nHow are you?";
    $headers = "From: SPOT";
    mb_send_mail($to, 'スポットシェア', 'あなたが投稿したスポットに追加投稿がありました。',$headers);
    //$stm->execute();

    echo "<a href=", $gobackURL2, ">戻る</a>";
//エラーが発生した場合の処理
} catch (Exception $e){
    echo '<span class="error">エラーがありました。</span><br>';
    echo $e->getMessage();
    exit();
}
?>