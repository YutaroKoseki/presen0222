<?php
require_once ('function.php');
session_start();

$u_id  = $_SESSION['user_id'];
$score = $_SESSION['point'];
// スコアを比較してハイスコアなら更新
updateScore($u_id,$score);

if(!empty($_POST['replay'])){
    unset($_SESSION['point']);

    header("Location:quiz.php");
    exit;
}
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="presen0222.css">
    <title>presen0222_result</title>
</head>
<body>
<div class="c-view p-result">
    <h2 class="p-result__title">結果は<?php echo $_SESSION['point']?>点でした！</h2>
</div>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="p-result--form">
    <a href="mypage.php">マイページへ</a>
    <input class="p-result--form__input" name="replay" type="submit" value="もう一度プレイする">
</form>

</body>
</html>
