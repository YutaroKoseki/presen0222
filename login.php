<?php
require_once ('function.php');
session_start();

//　ログイン処理
if(!empty($_POST['login'])){
    $name = (!empty($_POST['name']))? $_POST['name'] : '';
    $pass = (!empty($_POST['pass']))? $_POST['pass'] : '';
    // 初期化
    $_SESSION['err_msg'] = '';

    // バリデーション
    validRequire($name);

    validPass($pass);


    if(!empty($_SESSION['err_msg'])){
        var_dump($_SESSION['err_msg']);
    }


    // エラーが無い場合
    if(empty($_SESSION['err_msg'])){
        // 例外処理
        try{
            // ユーザー情報をDBから取得
            $dbh = dbConnect();
            $sql = 'SELECT * FROM users WHERE name = :name';
            $data = [':name'=> $name];

            $stmt = query($dbh, $sql, $data);

            $result = $stmt->fetch();
            print_r($result['id']);

            // DBから取得成功かつ、パスワードが一致している場合 :passMatch($pass, $result['password'])
            if($result){
                if(!empty($_SESSION['err_msg'])){
                    unset($_SESSION['err_msg']);
                }
                // セッションにログイン情報を置いとく。
                $_SESSION['login_flg'] = true;
                $_SESSION['user_id'] = $result['id'];
                header("Location: mypage.php");
            }

        }catch(PDOException $e){
            $_SESSION['err_msg'] = $e->getMessage();
        }

    }
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
    <title>presen0222</title>
</head>
<body>
<div class="c-view p-login">
    <h2 class="c-title p-login__title">ログイン</h2>
    <div class="p-container">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="p-login">
            <input type="text" name="name" placeholder="ユーザー名を入力してください" required class="c-input c-input--login p-login__name">
            <input type="password" name="pass" placeholder="パスワードを入力してください" required class="c-input c-input--login p-login__pass">
            <p class="c-error"><?php echo (!empty($_SESSION['err_msg']))? $_SESSION['err_msg']: '' ;?></p>
            <input type="submit" value="ログインする" name="login" class="c-input c-submit p-login__submit">
        </form>
    </div>

</div>
</body>
</html>



