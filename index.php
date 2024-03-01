<?php
session_start();
?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="presen0222.css">
    <title>presen0222</title>
</head>
<body>
<div class="c-view p-welcome">
    <h2 class="c-title p-welcome__title">難読漢字クイズ</h2>

    <div class="p-welcome__container">
        <a href="<?php echo (!empty($_SESSION['login_flg']))? 'mypage.php' : 'login.php'?>" class="c-link c-link--welcome p-welcome__login">ログイン</a>
        <?php if(empty($_SESSION['login_flg'])): ?>
          <a href="register.php" class="c-link c-link--welcome p-welcome__register">新規登録</a>
        <?php endif;?>
    </div>
</div>
</body>
</html>

