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
    
    //MySQLデータサーバに接続する
  try {
    $pdo = new PDO($dsn, $user, $password);
    //プリペアドステートメントのエミュレーションを無効する
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //例外がスローされる設定にする
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //SQL文を作る
    $sql = "SELECT pic_content,spots.spot_id,users.email FROM spots,comments,users WHERE users.user_id = spots.user_id && spots.com_id = comments.com_id";
    //プリペアドステートメントを作る
    $stm = $pdo->prepare($sql);
    //SQL文と実行する
    $stm->execute();
    //結果の取得
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);
    //テーブル作成
    echo "<p> 現在のスポットデータベース</p>";
    echo "<table border='1'>";
    // echo "<thead><tr>";
    // echo "<th>", "スポット名", "</th>";
    // echo "<th>", "コメント", "</th>";
    // echo "<th>", "ハンドルネーム", "</th>";
    // echo "<th>", "時間", "</th>";
    // echo "<th>", "写真", "</th>";
    // echo "</tr></thead>";
    echo "<tbody>";
?>
    <?php
    //1行ずつテーブルに入れる
    foreach ($result as $row){
    ?>
    <tr>
    <?php
        $img = base64_encode($row['pic_content']);

        // echo "<td>", es($row['sname']), "</td>";
        // echo "<td>", es($row['comment']), "<g/td>";
        // echo "<td>", es($row['hname']), "</td>";
        // echo "<td>", es($row['data_time']), "</td>";
        echo "<td>","<img src=data:image/png;base64,$img width='200' height='auto'>","</td>";
    ?>
        <tr>
            <td>
                <ul>
                    <form action="gr2.php" method="POST">
                        <input type="submit" value="詳細を見る" id="button">
                        <input type="hidden" name="spot_id" value="<?=$row['spot_id']?>">
                        <input type="hidden" name="email" value="<?=$row['email']?>">
                    </form>
                </ul>
            </td>
        </tr>
    <!-- <td>
        <ul>
            <form action="comment.php" method="POST">
                <input type="submit" value="追加投稿する" id="button">
                <input type="hidden" name="id" value="<?=$row['spot_id']?>">
            </form>
        </ul>
    </td> -->
    </tr>
        
</form>
    <?php
    }
    echo "</tbody>";
    echo "</table>";
    ?>
<?php
//接続に失敗したら実行される処理
} catch (Exception $e){
    echo '<span class="error">エラーがありました。</span><br>';
    echo $e->getMessage();
    exit();
}
?>