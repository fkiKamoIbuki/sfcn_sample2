<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>追加</title>
  <link href="../../css/style.css" rel="stylesheet">
</head>
<body>
<div>
<!-- 各フォームに入力された内容をPOSTする -->
<form method="POST" enctype="multipart/form-data" action="insert_plasspot.php">
    <ul>
        <li><span>スポット情報:</span>
            <label><textarea name="sinfo" cols="50" rows="5" placeholder="自由にお書きください"></textarea></label>
        </li>
        <li><span>写真:位置情報をオンにした状態で撮影したもの:</span>
            <label>画像を選択
            <input type="file" name="simage"></label>
        </li>
        <input type="hidden" name="spot_id" value='<?=$_POST['spot_id']?>'>
        <input type="hidden" name="email" value='<?=$_POST['email']?>'>
        <p><input type="submit" value="追加する" ></p>
    </ul>
</form>
</div>
</body>
</html>