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
<form method="POST" enctype="multipart/form-data" action="insert_spot.php">
    <ul>
        <li><span>スポット名:</span>
            <label><input type="text" name="sname" placeholder=""></label>
        </li>
        <li><span>スポット情報:</span>
            <label><textarea name="sinfo" cols="50" rows="5" placeholder="自由にお書きください"></textarea></label>
        </li>
        <li><span>写真:位置情報をオンにした状態で撮影したもの:</span>
            <label>画像を選択
            <input type="file" name="simage" required></label>
        </li>
        <!-- <li><span>クラス:</span>
            <label><input type="radio" name="kurasu" value="1-70" checked>1-70</label>
            <label><input type="radio" name="kurasu" value="1-74">1-74</label>
            <label><input type="radio" name="kurasu" value="1-77">1-77</label>
        </li> -->
        <!-- <li><span>コース:</span>
            <select name="corse[]" size="2" multiple>
                <option value="システム開発">システム開発</option>
                <option value="ネットワークセキュリティ">ネットワーク</option>
                <option value="ITビジネス" >ITビジネス</option>
                <option value="IT公務員" >IT公務員</option>
            </select>
        </li> -->
        <!-- <li><span>趣味:</span>
            <label><input type="checkbox" name="suki[]" value="運動">運動</label>
            <label><input type="checkbox" name="suki[]" value="読書">読書</label>
            <label><input type="checkbox" name="suki[]" value="ゲーム">ゲーム</label>
            <label><input type="checkbox" name="suki[]" value="睡眠" >睡眠</label>
        </li> -->
        <p><input type="submit" value="追加する" ></p>
    </ul>
</form>
</div>
</body>
</html>