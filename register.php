<?php
require_once ('function.php');
session_start();

if(!empty($_POST['register'])){

    $_SESSION['err_msg'] = '';
    $name = (!empty($_POST['name']))? $_POST['name'] : '';
    $pass = (!empty($_POST['pass']))? $_POST['pass'] : '';
    $pass_re = (!empty($_POST['pass_re']))? $_POST['pass_re'] : '';

    // バリデーション
    validRequire($name);
    validExist($name);

    validPass($pass);
    validPass($pass_re);

    if($pass !== $pass_re){
        $_SESSION['err_msg'] = ERR06;
    }

    if(empty($_SESSION['err_msg'])){
        try{
            // DB接続＆SQLクエリの準備
            $dbh = dbConnect();
            $sql = 'INSERT INTO users (name, password) VALUES (:name, :password)';
            $data = [
                ':name' => $name,
                ':password' => password_hash($pass, PASSWORD_DEFAULT)
            ];


            //$stmt = $dbh->prepare($sql);
            //$stmt->bindValue(':name', $name);
            //$stmt->bindValue(':password', password_hash($pass, PASSWORD_DEFAULT));
            //$result = $stmt->execute();
            $stmt = query($dbh, $sql, $data);


            // 登録成功した場合
            if($stmt){
                if(!empty($_SESSION['err_msg'])){
                    unset($_SESSION['err_msg']);
                }
                // ユーザーIDをセッションに保存
                $_SESSION['user_id'] = $dbh->lastInsertId();
                $_SESSION['login_flg'] = true;
                // マイページへリダイレクト
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
<div class="c-view p-register">
    <h2 class="c-title p-register__title">新規登録</h2>
    <div class="p-container">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" class="p-register">
            <input type="text" name="name" placeholder="ユーザー名を入力してください" required class="c-input c-input--login p-login__name">
            <input type="password" name="pass" placeholder="パスワードを入力してください" required class="c-input c-input--login p-login__pass">
            <input type="password" name="pass_re" placeholder="パスワード（再入力）" required class="c-input c-input--login p-login__pass">
            <p class="c-error"><?php echo (!empty($_SESSION['err_msg']))? $_SESSION['err_msg']: '' ;?></p>
            <input type="submit" value="登録する！" name="register" class="c-input c-submit p-login__submit">
        </form>
    </div>

</div>
</body>
</html>

