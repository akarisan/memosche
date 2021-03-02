<?php
require('configTwitter.php');
$tweet_content = $_POST['content'];
$result = $twitter->post("statuses/update",["status" => $tweet_content]);

header("Location: https://irohaori.work/pj/memosche/admin/");
exit();
?>