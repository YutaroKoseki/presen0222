<?php
require_once ('function.php');
session_start();

$u_id = $_SESSION['user_id'];

// ユーザー情報の取得
$user = getUser($u_id);
// ユーザー情報を変数に
$name    = $user['name'];
$hi_score = $user['hi_score'];

?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="presen0222.css">
    <title>presen0222_mypage</title>
</head>
<body>
<?php require_once ('auth.php')?>
<div class="c-view p-mypage">
    <h1 class="p-mypage__title">マイページ</h1>

    <div class="p-user">
        <p class="p-user__name"><?php echo $name?> さん</p>
    </div>

    <div class="p-score">
        これまでのハイスコア
        <p class="p-score__show"><?php echo $hi_score?> 点</p>
    </div>

    <a href="quiz.php" class="c-link p-mypage__link">クイズをする！</a>
</div>

</body>
</html>
