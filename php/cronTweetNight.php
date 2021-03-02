<?php
require_once('configDatabase.php');
require('configTwitter.php');

date_default_timezone_set('Asia/Tokyo');
$data_date = date("Y-m-d");

$cron_data = $con->query("SELECT data_id,data_name,data_entrysite FROM data WHERE data_limit = '$data_date'");
foreach($cron_data as $cron_data_row){
    $data_id = $cron_data_row['data_id'];
    $cron_n = $cron_data_row['data_name'];
    $cron_s = $cron_data_row['data_entrysite'];

    // アカウント取得
    $cron_a_data = $con->query("SELECT account FROM twitter WHERE data_id = '$data_id'");
    foreach($cron_a_data as $cron_a_data_row){
        $data_id = $cron_a_data_row['data_id'];
        $cron_a = $cron_a_data_row['account'];

        $tweet_content = <<<eod
        {$cron_a} さん！
        もう少しで{$cron_n}が申込締切だよ！！
        まだ申し込みしていないならお早めに…だからねっ
        {$cron_s}
        eod;
        $result = $twitter->post("statuses/update",["status" => $tweet_content]);
    }
    
    // アカウント取得
    $cron_e_data = $con->query("SELECT account FROM email WHERE data_id = '{$data_id}'");
    mb_language('Japanese');
    mb_internal_encoding('UTF-8');
    $title = "【まもなく締切】{$cron_n}";
    $headers = "From: memosche@irohaori.work";
    foreach($cron_e_data as $cron_e_data_row){
        $e_data_id = $cron_e_data_row['data_id'];
        $e_cron_a = $cron_e_data_row['account'];

        $email_content = <<<eod
        こんばんは！
        Memosche for Emailからのお知らせです。

        まもなく{$cron_n}の申込締切です！
        まだエントリーしていなければお早めにっ！！
        {$cron_s}
        eod;

        $to = $e_cron_a;

        mb_send_mail($to,$title,$email_content,$headers);
    }
}
?>