<!DOCTYPE html>
<html lang="ja">
<meta charset="utf-8">
<title>index</title>
    <p>sample</p>
</html>
<?php
//util.phpを読み込む
  require_once("../lib_cken/lib/util.php");
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
    $email = $_POST["email"];
    
    //MySQLデータサーバに接続する
  try {
    $pdo = new PDO($dsn, $user, $password);
    //プリペアドステートメントのエミュレーションを無効する
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //例外がスローされる設定にする
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //SQL文を作る
    $sql = "SELECT sname,comment,hname,comments.data_time,pic_content
            FROM comments,users,spots
            WHERE comments.spot_id = :spot_id && comments.user_id = users.user_id && spots.spot_id = comments.spot_id";

    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //$stm2 = $pdo->prepare($sql2);

    $stm->bindValue(':spot_id', $spot_id, PDO::PARAM_STR);
    //$stm2->bindValue(':spot_id', $spot_id, PDO::PARAM_STR);
    //$stm2->bindValue(':spot_id2', $spot_id, PDO::PARAM_STR);
    //SQL文と実行する
    $stm->execute();
    //$stm2->execute();
    //結果の取得
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    //$result2 = $stm2->fetchAll(PDO::FETCH_ASSOC);
    //テーブル作成
    // echo "<p> 現在のスポットデータベース</p>";
    // echo "<table border='1'>";
    // echo "<thead><tr>";
    // echo "<th>", "スポット名", "</th>";
    // echo "</tr></thead>";
    // echo "<tbody>";
?>
<?php
    echo "<table border='1'>";
    echo "<thead><tr>";
    echo "<th>", "時間", "</th>";
    echo "<th>", "コメント", "</th>";
    echo "<th>", "ハンドルネーム", "</th>";
    echo "<th>", "写真", "</th>";
    echo "</tr></thead>";
    echo "<tbody>";
?>
 <?php
    echo "<p>", es($result[0]['sname']), "</p>";
    //1行ずつテーブルに入れる
    foreach ($result as $row){
?>

<tr>
    <?php
        if($row['pic_content'] === ""){
            $img = "";
        }else{
            $img = base64_encode($row['pic_content']);
        }

        echo "<td>", es($row['data_time']), "</td>";
        echo "<td>", es($row['comment']), "<g/td>";
        echo "<td>", es($row['hname']), "</td>";
        if($img != ""){
            echo "<td>","<img src=data:image/png;base64,$img width='200' height='auto'>","</td>";
        }
    ?>
    <?php
        }
        echo "</tbody>";
        echo "</table>";
    ?>
    <form action="plasspot_form.php" method="POST">
        <input type="submit" value="追加投稿する" id="button">
        <input type="hidden" name="spot_id" value='<?=$spot_id?>'>
        <input type="hidden" name="email" value='<?=$email?>'>
    </form>
<?php
//接続に失敗したら実行される処理
} catch (Exception $e){
    echo '<span class="error">エラーがありました。</span><br>';
    echo $e->getMessage();
    exit();
}
?>