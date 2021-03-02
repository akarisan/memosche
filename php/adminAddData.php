<?php
//管理者用
//イベントデータ追加
require_once('configDatabase.php');
require('configTwitter.php');

$data_id = mt_rand(1000000,9999999);

session_start();
$_SESSION = $_POST;
$data_day = $_SESSION['day'];
$data_name = htmlspecialchars($_SESSION['name']);
$data_limit = $_SESSION['limit'];
$data_entryfee = htmlspecialchars($_SESSION['entryfee']);
$data_entrysite = htmlspecialchars($_SESSION['entrysite']);
$data_mode = htmlspecialchars($_SESSION['mode']);
$data_u_id = $_SESSION['accept_id'];
$data_t_id = intval($_SESSION['tweet_id']);
$data_t_sname = $_SESSION['tweet_name'];

if ($data_mode == 'accept') {
    try {
        $stmt = $con->prepare("INSERT INTO data (data_id,data_day,data_name,data_limit,data_entryfee,data_entrysite) VALUES (:data_id,:data_day,:data_name,:data_limit,:data_entryfee,:data_entrysite)");
        $stmt->bindValue(':data_id', $data_id, PDO::PARAM_INT);
        $stmt->bindValue(':data_day', $data_day, PDO::PARAM_STR);
        $stmt->bindValue(':data_name', $data_name, PDO::PARAM_STR);
        $stmt->bindValue(':data_limit', $data_limit, PDO::PARAM_STR);
        $stmt->bindValue(':data_entryfee', $data_entryfee, PDO::PARAM_STR);
        $stmt->bindValue(':data_entrysite', $data_entrysite, PDO::PARAM_STR);
        $stmt->execute();
        
        $con->query("UPDATE accept SET data_accept = 'accept' WHERE unique_id = '{$data_u_id}'");
        
        $reply_contents = "@{$data_t_sname} イベントが承認されました！";
        $post_e = $twitter->post("statuses/update", ["status" => $reply_contents,"in_reply_to_status_id" => $data_t_id]);
        
    } catch(Exception $e) {
        
    }
} elseif ($data_mode == 'un-accept') {
    try {
        $con->query("UPDATE accept SET data_accept = 'un-accept' WHERE unique_id = '{$data_u_id}'");

        $reply_contents = "@{$data_t_sname} イベントが却下されました…";
        $post_e = $twitter->post("statuses/update", ["status" => $reply_contents,"in_reply_to_status_id" => $data_t_id]);

    } catch(Exception $e) {

    }
} else {
    
}
header("Location: https://irohaori.work/pj/memosche/admin/");
exit();

?>