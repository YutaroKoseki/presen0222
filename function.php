<?php

// ========================================================
// DB関連
// ========================================================

// DB接続関数
function dbConnect(){
    // PDOでDBに接続するための準備
    $dsn = 'mysql:dbname=drill;host=localhost;charset=utf8;unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock';
    $user = 'root';
    $pass = 'root';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // エラーモードの設定
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // フェッチモードのデフォ
    ];

    $db = new PDO($dsn, $user, $pass, $options);
    return $db;
}

// クエリ実行関数
function query($dbh, $sql, $data){
    // DBへ接続、SQL実行のための準備
    $stmt = $dbh->prepare($sql);

    // SQLの実行
    if(!$stmt->execute($data)){
        $_SESSION['err_msg'] = ERR08;
        return false;
    }

    return $stmt;
}

// ユーザー情報取得
function getUser($u_id){
    try{
        $dbh = dbConnect();
        $sql = 'SELECT * FROM users WHERE id = :u_id';
        $data = [':u_id' => $u_id];

        $stmt = query($dbh, $sql, $data);

        // ユーザー情報をフェッチしてリターン
        $result = $stmt->fetch();
        return $result;

    }catch (PDOException $e){
        $_SESSION['err_msg'] = $e->getMessage();
    }
}

// クイズ取得
function getQuiz(){
    try{
        $dbh = dbConnect();
        // ランダムに5件取得
        $sql = 'SELECT * FROM quizzes ORDER BY RAND() LIMIT 5';
        $data = [];

        $stmt = query($dbh, $sql, $data);
        return $stmt;

    }catch (PDOException $e){
        $_SESSION['err_msg']  = ERR08;
    }
}

// スコアの比較と更新
function updateScore($u_id, $score){
    $user = getUser($u_id);
    $dbScore = $user['hi_score'];

    if($dbScore < $score){
        try {
            $dbh = dbConnect();
            $sql = 'UPDATE users SET hi_score = :hi_score WHERE id = :u_id';
            $data = [
                ':u_id' => $u_id,
                ':hi_score' => $score
            ];

            $stmt = query($dbh, $sql, $data);

            if($stmt->rowCount() > 0){
                $_SESSION['success'] = MSG02;
            }

        }catch (PDOException $e){
            $_SESSION['err_msg'] = ERR08;
        }
    }
}


// ========================================================
// セッション関連
// ========================================================

// 関数
function destroy(){
    // セッションが既に開始されているか確認
    if (session_status() === PHP_SESSION_NONE) {
        // セッションが開始されていない場合、開始する
        session_start();
    }
    // セッション変数を全て削除
    $_SESSION = array();
    // セッションクッキーも削除
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 3600,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    // セッションを破棄
    session_destroy();
}



// ========================================================
// 定数
// ========================================================

define('ERR01', '未入力の項目があります');
define('ERR02', '8文字以上必要です');
define('ERR03', '255文字以内で入力してください');
define('ERR04', '半角英数字のみご利用可能です');
define('ERR05', 'ユーザー情報に誤りがあります');
define('ERR06', 'パスワード（再入力）が一致しません');
define('ERR07', 'すでに存在するユーザー名です');
define('ERR08', 'DBとの接続でエラーが発生しました');

define('MSG01', 'ログインしました！');
define('MSG02', 'ハイスコアを更新しました！');



// ========================================================
// バリデーション
// ========================================================

// 未入力
function validRequire($str){
    if($str == ''){
        $_SESSION['err_msg'] = ERR01;
        return;
    }
}
// 最小文字数
function validMinLen($str){
    if(mb_strlen($str) < 8){
        $_SESSION['err_msg'] = ERR02;
    }
}

// 最大文字数
function validMaxLen($str){
    if(mb_strlen($str) > 255){
        $_SESSION['err_msg'] = ERR03;
    }
}

// 半角英数字
function validHalf($str){
    if(!preg_match("/^[a-zA-Z0-9]+$/",$str)){
        $_SESSION['err_msg'] = ERR04;
    }
}

// パスワード一式
function validPass($str){
    validRequire($str);
    //最少・最大文字数チェック
    validMinLen($str);
    validMaxLen($str);
    //半角チェック
    validHalf($str);
}

// パスワードマッチ
function passMatch($str1, $str2){
    if(!password_verify($str1, $str2)){
        $_SESSION['err_msg'] = ERR05;
    }
}

// ユーザー重複チェック
function ValidExist($name){
    try{
        $dbh = dbConnect();
        $sql = 'SELECT count(*) as count FROM users WHERE name = :name';
        $data = [':name' => $name];

        $stmt = query($dbh, $sql, $data);
        $result = $stmt->fetch();

        // ユーザー情報がヒットする場合はエラー
        if($result && $result['count'] > 0){
            $_SESSION['err_msg'] = ERR07;
        }

    }catch (PDOException $e){
        $_SESSION['err_msg'] = ERR08;
        //log($e->getMessage());
    }
}


// ========================================================
// その他
// ========================================================

// サニタイズ
function sanitize($str){
    return htmlspecialchars($str);
}