<?php
$account = $_POST['twitter'];
$account = htmlspecialchars($account);

require('configTwitter.php');

# 1文字目が@かどうかを判断
if (mb_substr($account,0,1) != '@') {
    echo "<script>alert('アカウント名は@から始めてください。');document.location.href = 'https://irohaori.work/pj/memosche/';</script>";
    
    //header("Location: https://irohaori.work/pj/memosche/");
    exit();
}else {

require_once('configDatabase.php');

$datas = $con->query("SELECT user_id FROM user WHERE user_account = '{$account}'");

if ($datas->rowCount() < 1) {
    # 新規登録の場合
    $user_id = mt_rand(10000000,99999999);

    # DBへ登録
    try {
        $stmt = $con->prepare("INSERT INTO user (user_id,user_account,user_info) VALUES (:id, :account, :info)");
        $stmt->bindValue(':id', $user_id, PDO::PARAM_STR);
        $stmt->bindValue(':account', $account, PDO::PARAM_STR);
        $stmt->bindValue(':info', 'twitter', PDO::PARAM_STR);
        $stmt->execute();
        $new_state = true;

        setcookie('user',$user_id,time()+60*60*24*30,'/pj/memosche/','irohaori.work');
        
        # セッションに保存
        session_start();
        $_SESSION['user'] = $user_id;

    } catch(Exeption $ex) {
        $new_state = false;
    }
    
    # dev_teerから御礼リプライを発信
    $tweet_content = "{$account} さん、MemoScheへのご登録ありがとうございます！";
    $result = $twitter->post("statuses/update",["status" => $tweet_content]);
    
} else {
    # すでに登録されている場合、ログイン
    foreach($datas as $data){
        $user_id = $data['user_id'];
    }
    
    # Cookieに保存
    setcookie('user',$user_id,time()+60*60*24*30,'/pj/memosche/','irohaori.work');

    # セッションに保存
    session_start();
    $_SESSION['user'] = $user_id;

    header("Location: https://irohaori.work/pj/memosche/user/");
    exit();
}
}
?>


<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>めもすけ</title>
        <link rel="stylesheet" href="../css/beauter.css">
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>

        <header class="jumbo _indigo">
            <h1>MemoSche</h1>
            <p>Twitter版</p>
            <hr>
            <?php
            if ($new_state ==true) {
                echo "<h3>新規登録完了</h3>";
            } else {
                echo "<h3>新規登録エラー</h3>";
            }
            ?>
        </header>

        <div class="jumbo">
            <?php
            if ($new_state ==true) {
                echo "<p>MemoScheへのご登録ありがとうございます。<br>早速、イベントスケジュールをメモしましょう！</p><a href='../user/' class='button _xlarge _round'>イベント一覧へ</a>";
            } else {
                echo "<p>何かしらのエラーが発生したため、登録できませんでした。<br>ご迷惑をおかけいたしますが、管理者にお問い合わせください。</p><a href='https://twitter.com/himjiu3' class='button _xlarge _round' target='_blank'>管理者のツイッターへ</a>";
            }
            ?>
        </div>

        <script src="../js/beauter.js"></script>
    </body>
</html>