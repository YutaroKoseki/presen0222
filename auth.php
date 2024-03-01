<?php
require_once ('function.php');

if(!empty($_POST['logout'])){
    destroy();
    header("Location:login.php");
    exit;
}

echo '<header class="p-header">';
echo '<a class="c-link c-header--common" href="index.php">HOME</a>';

if(!empty($_SESSION['login_flg'])){
    // ログイン済み
    echo '<form method="post" action="auth.php"><input type="submit" name="logout" class="c-input c-header--common p-logout" value="ログアウト"></form>';
}else{
    // 未ログイン
    echo '<a href="login.php" class="c-link c-header--common">ログイン</a>';
}
echo '</header>';

?>
