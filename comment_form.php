<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>追加</title>
  <link href="../../css/style.css" rel="stylesheet">
</head>
<body>
<div>
<?php
    $spot_id = $_POST["spot_id"];
?>
<!-- 各フォームに入力された内容をPOSTする -->
<form method="POST" action="insert_comment.php">
    <ul>
        <li><span>スポット情報:</span>
            <label><textarea name="scom" cols="50" rows="5" placeholder="自由にお書きください"></textarea></label>
            <input type="hidden" name="id" value="<?=$row['spot_id']?>">
        </li>
        <p><input type="submit" value="コメントする" ></p>
    </ul>
</form>
</div>
</body>
</html>