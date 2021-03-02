<?php
require_once('configDatabase.php');
require('configTwitter.php');

/*
date_default_timezone_set('Asia/Tokyo');
$data_date = date("Y-m-d");

$cron_data = $con->query("SELECT data_id,data_name,data_entrysite FROM data WHERE data_limit = '{$data_date}'");
*/

$cron_data = $con->query("SELECT data_id,data_name,data_entrysite FROM data WHERE data_id = '2'");

foreach($cron_data as $cron_data_row){
    $data_id = $cron_data_row['data_id'];
    $cron_name = $cron_data_row['data_name'];
    $cron_site = $cron_data_row['data_entrysite'];

    // アカウント取得
    $cron_a_data = $con->query("SELECT user_id FROM fav WHERE data_id = '{$data_id}'");
    foreach($cron_a_data as $cron_a_data_row){
        $cron_user_id = $cron_a_data_row['user_id'];

        // アカウント名を取得
        $cron_account_data = $con->query("SELECT user_account,user_info FROM user WHERE user_id = '{$cron_user_id}'");
        foreach($cron_account_data as $cron_account_datas){
            $cron_account = $cron_account_datas['user_account'];
            $cron_info = $cron_account_datas['user_info'];

            echo $cron_account,$cron_info;
        }

        # Email-Config
        mb_language('Japanese');
        mb_internal_encoding('UTF-8');
        $title = "【今日締切】{$cron_name}";
        $header = "Content-Type: text/plain \r\n";
        $header .= "Return-Path: memosche@irohaori.work \r\n";
        $header .= "From: memosche@irohaori.work \r\n";
        $header .= "Sender: MemoSche \r\n";
        $header .= "Reply-To: memosche@irohaori.work \r\n";
        $header .= "Organization: MemoSche \r\n";

        // アカウント情報をチェック
        if ($cron_info == 'twitter') {
            $tweet_content = <<<eod
            {$cron_account} さん！
            今日は{$cron_name}の申込締切日ですよ！！
            エントリーし忘れないでくださいねっ
            {$cron_site}
            eod;
            $result = $twitter->post("statuses/update",["status" => $tweet_content]);
        } elseif ($cron_info == 'email') {
            $email_content = <<<eod
            おはようございます！
            Memosche for Emailからのお知らせです。

            今日は{$cron_name}の申込締切日です！
            エントリーし忘れないでくださいね。
            {$cron_site}
            eod;

            $to = $cron_account;

            mb_send_mail($to,$title,$email_content,$header);
        }
    }
}
?>