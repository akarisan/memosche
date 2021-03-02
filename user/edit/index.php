<?php
$reply_id = $_GET['i'];

require_once('../../php/configDatabase.php');
$datas= $con->query("SELECT * FROM accept WHERE data_tweet LIKE '{$reply_id}' and data_accept LIKE 'wait'");
if ($datas->rowCount() < 1) {
    $state = false;
} else {
    $state = true;
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
        <link rel="stylesheet" href="../../css/beauter.css">
        <link rel="stylesheet" href="../../css/style.css">
    </head>
    <body>

        <header class="jumbo _indigo">
            <h1>MemoSche</h1>
            <p>気になるイベントをまとめよう</p>
        </header>
        
        <div class="container">
            <?php
            if ($state == true) {
                foreach ($datas as $data) {
            ?>
           
            <form action="../../php/userEditAcceptData.php" method="post">
                <fieldset>
                    <legend>イベント編集</legend>
                    <div class="row">
                        <div class="col m12">
                            <label class="">イベント名</label>
                            <input name="name" type="text" class="_width100" value="<?php echo $data['data_name']; ?>" required>
                        </div>
                        <div class="col m4">
                            <label>開催日</label>
                            <input name="day" type="date" class="_width100" value="<?php echo $data['data_day']; ?>" required>
                        </div>
                        <div class="col m4">
                            <label>締切日</label>
                            <input name="limit" type="date" class="_width100" value="<?php echo $data['data_limit']; ?>" required>
                        </div>
                        <div class="col m4">
                            <label>参加費</label>
                            <input name="entryfee" type="text" class="_width100" value="<?php echo $data['data_entryfee']; ?>" required>
                        </div>
                        <div class="col m12">
                            <label>エントリーサイトURL</label>
                            <input name="entrysite" type="url" class="_width100" value="<?php echo $data['data_entrysite']; ?>" required>
                        </div>
                        <div class="col m3">
                            <input name="reply_id" type="number" class="_width100" value="<?php echo $reply_id; ?>" hidden>
                            <input type="submit" value="変更" class="_primary _large _width100">
                        </div>
                    </div>
                </fieldset>
            </form>
            <?php
                }   //endforeach
            } else {
            ?>
            <h3>ごめんなさい…<br>イベントが存在しないか、すでに処理されています。</h3>
            <?php
            }   //endif
            ?>
        </div>
        

        <footer class="jumbo _indigo">
            <p>&copy;2019-2020 MemoSche by <a href="https://irohaori.work" target="_blank">irohaori work</a></p>
        </footer>

        <div id="snackSuccess" class="snackbar _success  _box _shadow">変更しました</div>
        
        <script src="../../js/beauter.js"></script>
        
        <?php
        $console = $_GET['c'];
        if ($console == 1) {
            echo "<script>showsnackbar('snackSuccess');</script>";
        }
        ?>
    </body>
</html>