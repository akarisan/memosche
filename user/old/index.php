<?php
//session_start();
//$user_id = $_SESSION['user'];

require_once('../php/userSessionCheck.php');
require_once('../php/userSelectData.php');
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
            <p>メモしたイベント</p>
            <a href="index.php" class="button _info">メモしたイベント</a>
            <a href="event.php" class="button _info">イベント一覧</a>
        </header>
        
        <?php
        if ($select_state == 1) {
        ?>
        <div class="container card-wrap">
            <?php 
            for($i = 0; $i < count($user_data_arr); $i++){
                $data_row = $con->query("SELECT * FROM data WHERE data_id = '$user_data_arr[$i]'");
                foreach($data_row as $data){
            ?>
            
            <div class="card">
                <div class="card-event">
                    <a href="<?php echo $data['data_entrysite']; ?>" class="card-over-link" target="_blank"></a>
                    <div class="card-title">
                        <?php echo date('Y/m/d',strtotime($data['data_day'])); ?><br>
                        <small><?php echo $data['data_name']; ?></small>
                    </div>
                    <p>締切　　<b><?php echo date('Y/m/d',strtotime($data['data_limit'])); ?></b></p>
                    <p>参加費　¥ <?php echo $data['data_entryfee']; ?></p>
                </div>
                <div class="card-link">
                    <a href="../php/userEditData.php?mode=del&user=<?php echo $user_id; ?>&data=<?php echo $data['data_id']; ?>" class="card-a _delete">×</a>
                </div>
            </div>
            
            <?php
                }
            }
            ?>
        </div>
        <?php
        } elseif ($select_state == 2) {
        ?>
        <div class="container _alignCenter">
            <p>イベントがありません。</p>
        </div>
        <?php
        } else { echo("Error"); }
        ?>
        
        <footer class="jumbo _indigo">
            <p>&copy;2019-2020 MemoSche by <a href="https://irohaori.work" target="_blank">irohaori work</a></p>
        </footer>
        
        <div id="snackSuccess" class="snackbar _danger _box _shadow">削除しました</div>
        <div id="snackError" class="snackbar _box _shadow">削除できませんでした</div>
        
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
        if ($console == 1) {
            echo "<script>showsnackbar('snackSuccess');</script>";
        } elseif ($console == 2) {
            echo "<script>showsnackbar('snackError');</script>";
        }
        ?>
    </body>
</html>