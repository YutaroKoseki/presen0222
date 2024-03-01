
<?php
require_once ('function.php');
session_start();
//destroy();

// ====================================
// 処理
// ====================================

if(empty($_SESSION['start_flg'])){
    // 念の為
    $_SESSION['start_flg'] = true;
    unset($_SESSION['quizzes']);

    $stmt = getQuiz();
    $quizzes = $stmt->fetchAll();
    $_SESSION['quizzes'] = $quizzes;
    $_SESSION['current'] = 0;
    $_SESSION['point'] = 0;

} else {
    // POSTリクエスト後の処理で必要な変数をセッションから復元
    $quizzes = $_SESSION['quizzes'];
    $point = $_SESSION['point']; // 修正が必要な場合はここでポイントを更新
}

//var_dump($_SESSION['quizzes']);


    // クイズ情報をそれぞれ変数に
    $currentQuiz = $quizzes[$_SESSION['current']];
    $correct = $currentQuiz['correct'];
    $problem = $currentQuiz['problem'];
    $answers = [
        $currentQuiz['answer1'],
        $currentQuiz['answer2'],
        $currentQuiz['answer3'],
        $currentQuiz['answer4'],
    ];


// 回答された場合
if(!empty($_POST)){
    $correct = $currentQuiz['correct'];

    if($_POST['answer'] === $correct){
        // 正解は+10点
        $_SESSION['point'] += 10;
    }else{
        // 不正解は-5点(最低値は0)
        $_SESSION['point'] = ($_SESSION['point'] > 5)? $_SESSION['point'] -= 5 : 0;
    }

    // 現在の問題と残り問題の数を比較
    if($_SESSION['current'] + 1 < count($quizzes)){
        // 問題が残っている場合は次の問題へ
        $_SESSION['current']++;
    }else{
        // 全問終了した場合は結果ページへ。セッションに保存した点数情報と共に・・・。
        unset($_SESSION['current']);
        unset($_SESSION['start_flg']);
        unset($_SESSION['quizzes']);
        header('Location: result.php');
        exit;
    }

    header("Location:". $_SERVER['PHP_SELF']);
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
    <title>presen0222_QUIZ</title>
</head>
<body>
<div class="c-view p-quiz">
    <!--  問題文  -->
    <h2 class="p-quiz__title"><?php echo sanitize($problem);?></h2>

    <!--  回答の選択肢  -->
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <?php
        // 答えをシャッフル
        shuffle($answers);

        foreach ($answers as $answer):
            ?>
            <input type="submit" name="answer" value="<?php echo sanitize($answer)?>" class="c-input p-quiz__answer">
        <?php endforeach;?>
    </form>

</div>

<a href="mypage.php" class="c-link">戻る</a>
</body>
</html>