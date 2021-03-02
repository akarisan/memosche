<?php
require_once('../php/configDatabase.php');
$datas = $con->query("SELECT * FROM accept WHERE data_accept = 'wait'");
if ($datas->rowCount() < 1) {
    $state = false;
} else {
    $state = true;
}

$events = $con->query("SELECT * FROM data ORDER BY data_limit ASC");
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">
        <title>Admin|MemoSche</title>
        <link rel="stylesheet" href="../css/beauter.css">
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        <div class="jumbo _indigo">
            <h1>MemoSche</h1>
            <p>Admin</p>
        </div>
        <div class="tab">
            <button class="tablinks" onclick="opentab('tab1')">承認待ちリスト</button>
            <button class="tablinks" onclick="opentab('tab2')">ツイート</button>
            <button class="tablinks" onclick="opentab('tab3')">イベント編集</button>
        </div>
        
        <section id="tab1" class="tabcontent">
            <table class="_width100">
               
                <?php
                if ($state == true) {
                		$i = 1;
                    foreach ($datas as $data) {
                ?>
                
                <tr>
                    <td>
                        <form action="../php/adminAddData.php" method="post">
                            <div class="row">
                                <div class="col m6">
                                    <label>イベント名</label>
                                    <input name="name" type="text" value="<?php echo $data['data_name']; ?>" class="_width100" required>
                                </div>
                                <div class="col m6">
                                    <label>エントリーサイト</label>
                                    <input name="entrysite" type="url" value="<?php echo $data['data_entrysite']; ?>" class="_width100" required>
                                </div>
                                <div class="col m3">
                                    <label>開催日</label>
                                    <input name="day" type="text" value="<?php echo $data['data_day']; ?>" class="_width100" required>
                                </div>
                                <div class="col m3">
                                    <label>締切日</label>
                                    <input name="limit" type="text" value="<?php echo $data['data_limit']; ?>" class="_width100" required>
                                </div>
                                <div class="col m6">
                                    <label>参加費</label>
                                    <input name="entryfee" type="text" value="<?php echo $data['data_entryfee']; ?>" class="_width100" required>
                                </div>
                                <div class="col m6">
                                    <div class="form-radio">
                                        <input name="mode" id="mode1<?php echo $i; ?>" type="radio" value="accept">
                                        <label for="mode1<?php echo $i; ?>" class="radio-label">承認</label>
                                    </div>
                                    <div class="form-radio">
                                        <input name="mode" id="mode2<?php echo $i; ?>" type="radio" value="un-accept">
                                        <label for="mode2<?php echo $i; ?>" class="radio-label">却下</label>
                                    </div>
                                </div>
                                <div class="col m6">
                                    <input name="accept_id" type="number" value="<?php echo $data['unique_id']; ?>" hidden>
                                    <input name="tweet_id" type="text" value="<?php echo $data['data_tweet']; ?>" hidden>
                                    <input name="tweet_name" type="text" value="<?php echo $data['data_tweet_name']; ?>" hidden>
                                    <input type="submit" value="登録" class="button _primary _large">
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                
                <?php
                		$i++;
                    }   //endforeach
                } else {
                ?>
                <tr><td>承認待ちイベントはありません。</td></tr>
                <?php
                }
                ?>
                
            </table>
        </section>
        
        <section id="tab2" class="tabcontent">
            <form action="../php/adminTweet.php" method="post">
                <textarea name="content" class="_width100" style="height: 10em;"></textarea>
                <input type="submit" value="ツイート" class="button _primary _large">
            </form>
        </section>
        
        <section id="tab3" class="tabcontent">
            <div class="card-wrap">
            <?php
            foreach($events as $event){
                $data_id = $event['data_id'];
            ?>
                <div class="card">
                    <div class="card-event">
                        <div class="card-title">
                            <?php echo date('Y/m/d',strtotime($event['data_day'])); ?><br>
                            <small><?php echo $event['data_name']; ?></small>
                            <p><?php echo $event['data_limit']; ?></p>
                        </div>
                    </div>
                    <div class="card-link">
                        <a href="javascript:void(0);" onclick="var ok=confirm('本当に削除しますか？');if (ok) location.href='../php/adminEditData.php?mode=del&data=<?php echo $data_id; ?>'; return false;" class="card-a _delete">×</a>
                    </div>
                </div>
            <?php
            }
            ?>
            </div>
        </section>

        <div id="snackError" class="snackbar _box _shadow">エラー</div>
        <div id="snackDelSuccess" class="snackbar _danger _box _shadow">削除しました</div>
        
        <script src="../js/beauter.js"></script>
        <script>
            opentab('tab1');
        </script>
        
        <?php
        $console = $_GET['c'];
        if ($console == 1) {
            echo "<script>showsnackbar('snackError');opentab('tab3');</script>";
        } elseif ($console == 2) {
            echo "<script>showsnackbar('snackDelSuccess');opentab('tab3');</script>";
        } else {
            echo "<script>opentab('tab3');</script>";
        }
        ?>
    </body>
</html>