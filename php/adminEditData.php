<?php
require_once('configDatabase.php');

$mode = $_GET['mode'];
$data_id = $_GET['data'];

if (!$mode or !$data_id) {
    header("Location: https://irohaori.work/pj/memosche/admin/?c=1");
    exit();
}

if ($mode == 'edit') {
    
} elseif ($mode == 'del') {
    // イベントを削除
    $con->query("DELETE FROM data WHERE data_id = '$data_id'");
    $con->query("DELETE FROM fav WHERE data_id = '$data_id'");
    header("Location: https://irohaori.work/pj/memosche/admin/?c=2");
    exit();
}


?>