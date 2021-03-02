<?php
//session_start();
//$user_id = $_SESSION['user'];

require_once('../php/userSessionCheck.php');
require_once('../php/userSelectData.php');
require_once('../php/configDatabase.php');
$event_data_row = $con->query("SELECT * FROM data ORDER BY data_day ASC");
if ($event_data_row->rowCount() < 1) {
    $event_select_state = false;
} else {
    $event_select_state = true;
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">
        <title>めもすけ</title>
        <link rel="stylesheet" href="../css/beauter.css">
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>

        <header class="jumbo _indigo">
            <h1 onclick="document.location.href='../';">MemoSche</h1>
            <p>イベント一覧</p>
            <a href="index.php" class="button _info">メモしたイベント</a>
            <a href="event.php" class="button _info">イベント一覧</a>
        </header>
        
        <?php
        if ($event_select_state == true) {
        ?>

        <div class="container">
            <ul>
            <?php 
            foreach($event_data_row as $event_data){
            ?>
                  
                <li>
                    <div class="li-title">
                        <?php echo date('Y/m/d',strtotime($event_data['data_day'])); ?><br>
                        <small><?php echo $event_data['data_name']; ?></small>
                        <p>参加費　¥ <?php echo $event_data['data_entryfee']; ?></p>
                    </div>
                    <div class="li-link _success"><a href="../php/userEditData.php?mode=add&user=<?php echo $user_id; ?>&data=<?php echo $event_data['data_id']; ?>">メモ</a></div>
                </li>
                
            <?php
            }
            ?>
            </ul>
        </div>
        
        <?php
        } else {
        ?>
        <div class="container _alignCenter">
            <p>イベントがありません。</p>
        </div>
        <?php
        }
        ?>
        
        <footer class="jumbo _indigo">
            <p>&copy;2019-2020 MemoSche by <a href="https://irohaori.work" target="_blank">irohaori work</a></p>
        </footer>
        
        <div id="snackAddSuccess" class="snackbar _success _box _shadow">追加しました</div>
        <div id="snackAddError" class="snackbar _box _shadow">追加できませんでした</div>
        <div id="snackAddErrorDiff" class="snackbar _box _shadow">すでに登録されています</div>
        

        <script>
            docCookies.setItem("temp", "true"); //適当なcookieの書き込みを行い、
            if("true" == docCookies.getItem("temp")){ //正常に利用できる環境であれば実行
                if("true" != docCookies.getItem("refresh")){ //初回訪問時は実行される
                    docCookies.setItem("refresh", "true"); //2回目以降は、cookieに値が書き込まれているので実行されない
                    docCookies.removeItem("temp");
                    location.reload(true); //ブラウザのキャッシュを使わずにリロードを実行
                }else{
                    docCookies.removeItem("temp");
                }
            }
        </script>
        
        <script src="../js/beauter.js"></script>
        <?php
        $console = $_GET['c'];
        if ($console == 10) {
            echo "<script>showsnackbar('snackAddSuccess');</script>";
        } elseif ($console == 20) {
            echo "<script>showsnackbar('snackAddError');</script>";
        } elseif ($console == 30) {
            echo "<script>showsnackbar('snackAddErrorDiff');</script>";
        }
        ?>
    </body>
</html>