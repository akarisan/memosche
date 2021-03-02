<?php
//session_start();
//$user_id = $_SESSION['user'];

require_once('../php/userSessionCheck.php');
require_once('../php/configDatabase.php');
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
    <body class="fixed">

        <header>
            <div class="jumbo _indigo">
                <h1 onclick="document.location.href='../';">MemoSche</h1>
            </div>
            <div class="tab _hide-mobile _hide-phablet" style="display: flex;">
                <button style="flex: 1;" class="tablinks" onclick="opentab('tab1')">メモしたイベント</button>
                <button style="flex: 1;" class="tablinks" onclick="opentab('tab2')">全てのイベント</button>
            </div>
        </header>

        <!-- メモしたイベント -->
        <section id="tab1" class="tabcontent">
        <?php
        // userからメモしたイベントを抜き取る
        $user_data_row = $con->query("SELECT data_id FROM fav WHERE user_id = '$user_id'");
            
        // favしたイベントがない場合
        if ($user_data_row->rowCount() < 1) {
        ?>
            <div class="_alignCenter">
                <p>イベントがありません。</p>
            </div>
        <?php
        } else {
        ?>
            <div class="card-wrap">
        <?php
            foreach($user_data_row as $user_data_query_row){
                $data_id = $user_data_query_row['data_id'];
                $data_row = $con->query("SELECT * FROM data WHERE data_id = '$data_id' ORDER BY data_limit ASC");
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
        }
        ?>
        </section>
        
        
        
        <!-- 全てのイベント -->
        <section id="tab2" class="tabcontent">
            <?php
            $event_data_row = $con->query("SELECT * FROM data ORDER BY data_day ASC");
            if ($event_data_row->rowCount() < 1) {
                // イベントがない時
            ?>
            <div class="container _alignCenter">
                <p>イベントがありません。</p>
            </div>
            
            <?php
            } else {
                // イベントがある時
            ?>
            <div class="container">
                <ul>
                    <?php 
                foreach($event_data_row as $event_data){
                    $e_data_id = $event_data['data_id'];
                    $user_data_check = $con->query("SELECT data_id FROM fav WHERE data_id LIKE '$e_data_id' and user_id LIKE '$user_id'");

                    if ($user_data_check->rowCount() < 1) {
                        // Favしていないデータ
                    ?>
                    <li>
                        <div class="li-title">
                            <?php echo date('Y/m/d',strtotime($event_data['data_day'])); ?><br>
                            <small><?php echo $event_data['data_name']; ?></small>
                            <p>参加費　¥ <?php echo $event_data['data_entryfee']; ?></p>
                        </div>
                        <div class="li-link"><a href="../php/userEditData.php?mode=add&user=<?php echo $user_id; ?>&data=<?php echo $event_data['data_id']; ?>">メモ</a></div>
                    </li>

                    <?php
                    } else {
                        // Favしたデータ
                    ?>
                    <li class="selected">
                        <div class="li-title">
                            <?php echo date('Y/m/d',strtotime($event_data['data_day'])); ?><br>
                            <small><?php echo $event_data['data_name']; ?></small>
                            <p>参加費　¥ <?php echo $event_data['data_entryfee']; ?></p>
                        </div>
                        <div class="li-link"><a href="../php/userEditData.php?mode=add&user=<?php echo $user_id; ?>&data=<?php echo $event_data['data_id']; ?>">メモ</a></div>
                    </li>

                    <?php
                    }
                }

                    ?>
                </ul>
            </div>
            
            <?php
            }
            ?>
            
        </section>
        
        

        <footer class="fixed _hide-tablet _hide-desktop _hide-widescreen">
            <div class="tab" style="display: flex;">
                <button style="flex: 1;" class="tablinks" onclick="opentab('tab1')">メモしたイベント</button>
                <button style="flex: 1;" class="tablinks" onclick="opentab('tab2')">全てのイベント</button>
            </div>
        </footer>

        <div id="snackDelSuccess" class="snackbar _danger _box _shadow">削除しました</div>
        <div id="snackDelError" class="snackbar _box _shadow">削除できませんでした</div>
        <div id="snackAddSuccess" class="snackbar _success _box _shadow">追加しました</div>
        <div id="snackAddError" class="snackbar _box _shadow">追加できませんでした</div>
        <div id="snackAddErrorDiff" class="snackbar _box _shadow">すでに登録されています</div>

        <script>
            // 強制リロード
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

        <script src="https://cdnjs.cloudflare.com/ajax/libs/pulltorefreshjs/0.1.20/index.umd.min.js"></script>
        <script>
            PullToRefresh.init({
                mainElement: 'body',
                onRefresh: function(){ document.location.href='https://irohaori.work/pj/memosche/user/'; }
            });
        </script>
        
        <script src="../js/beauter.js"></script>
        
        <?php
        $console = $_GET['c'];
        if ($console == 1) {
            echo "<script>showsnackbar('snackDelSuccess');opentab('tab1');</script>";
        } elseif ($console == 2) {
            echo "<script>showsnackbar('snackDelError');opentab('tab1');</script>";
        } elseif ($console == 10) {
            echo "<script>showsnackbar('snackAddSuccess');opentab('tab2');</script>";
        } elseif ($console == 20) {
            echo "<script>showsnackbar('snackAddError');opentab('tab2');</script>";
        } elseif ($console == 30) {
            echo "<script>showsnackbar('snackAddErrorDiff');opentab('tab2');</script>";
        } else {
            echo "<script>opentab('tab1');</script>";
        }
        ?>
    </body>
</html>