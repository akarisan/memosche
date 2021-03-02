<?php
require_once('configDatabase.php');
require('configTwitter.php');
$screen_name = "dev_teer";  #スクリーンネーム

// リプライを10件取得
$res = $twitter->get('statuses/mentions_timeline',['count' => 10]);

//$res = (array)json_decode(json_encode($res,true));
//var_dump($res);

for ($i = 0; $i < count($res); $i++) {

    if (strpos($res[$i]->text,'めもすけ登録') !== false) {

        $reply_id = $res[$i]->id;
        $reply_screen_name = $res[$i]->user->screen_name;

        $datas = $con->query("SELECT unique_id FROM replyed WHERE tweet_id = '$reply_id'");

        if ($datas->rowCount() < 1) {
            // 新規登録
            $tweet = $res[$i]->text;
            $tweet = htmlspecialchars_decode($tweet);
            $tweet = str_replace(array("\r\n", "\r", "\n"), ':memo:', $tweet);

            $res_data_arr = explode(':memo:',$tweet);
            echo $res_data_arr[0];
            echo "<br>";
            echo $res_data_arr[1];
            echo "<br>";
            echo $res_data_arr[2];
            echo "<br>";
            echo $res_data_arr[3];
            echo "<br>";
            echo $res_data_arr[4];
            echo "<br>";
            var_dump($reply_id);

            $mode_check = $res_data_arr[1];     #めもすけ登録
            $data_day = $res_data_arr[2];       #開催日
            $data_limit = $res_data_arr[3];     #締切日
            $data_name = $res_data_arr[4];      #イベント名
            $data_entryfee = $res_data_arr[5];  #参加費


            if ($mode_check !== "めもすけ登録" or !isset($data_day) or !isset($data_limit) or !isset($data_name) or !isset($data_entryfee)) {
                $reply_contents = "@{$reply_screen_name} ごめんなさい！特定の形式じゃないとダメなの…";
                $reply_contents .= "\n2行目から下記のように書いてね！";
                $reply_contents .= "\nめもすけ登録";
                $reply_contents .= "\n開催日";
                $reply_contents .= "\n締切日";
                $reply_contents .= "\nイベント名";
                $reply_contents .= "\n参加費";

                $post_e = $twitter->post("statuses/update", array("status" => $reply_contents,"in_reply_to_status_id" => $reply_id));

                $r_stmt = $con->prepare("INSERT INTO replyed (tweet_id) VALUES (:tweet_id)");
                $r_stmt->bindValue(':tweet_id', $reply_id, PDO::PARAM_STR);
                $r_stmt->execute();

            } else {
                $stmt = $con->prepare("INSERT INTO accept (data_accept,data_day,data_name,data_limit,data_entryfee,data_tweet,data_tweet_name) VALUES (:data_accept,:data_day,:data_name,:data_limit,:data_entryfee,:data_tweet,:data_tweet_name)");
                $stmt->bindValue(':data_accept', 'wait', PDO::PARAM_STR);
                $stmt->bindValue(':data_day', $data_day, PDO::PARAM_STR);
                $stmt->bindValue(':data_name', $data_name, PDO::PARAM_STR);
                $stmt->bindValue(':data_limit', $data_limit, PDO::PARAM_STR);
                $stmt->bindValue(':data_entryfee', $data_entryfee, PDO::PARAM_STR);
                $stmt->bindValue(':data_tweet', $reply_id, PDO::PARAM_STR);
                $stmt->bindValue(':data_tweet_name', $reply_screen_name, PDO::PARAM_STR);
                $stmt->execute();

                $reply_contents = "@{$reply_screen_name} イベントを受付しました！";
                $reply_contents .= "\n承認までしばらくお待ちくださいねっ";
                $reply_contents .= "\n下記URLから申請内容の確認・変更が出来るよ～";
                $reply_contents .= "\n https://irohaori.work/pj/memosche/user/edit/?i={$reply_id}";

                $post_e = $twitter->post("statuses/update", array("status" => $reply_contents,"in_reply_to_status_id" => $reply_id));
                //$fav_e = $twitter->post("favorites/create", ["id" => $reply_id]);

                $r_stmt = $con->prepare("INSERT INTO replyed (tweet_id) VALUES (:tweet_id)");
                $r_stmt->bindValue(':tweet_id', $reply_id, PDO::PARAM_INT);
                $r_stmt->execute();
            }
        } else {
            // すでに登録されている場合は何もしない
        }
    }
}
    
?>