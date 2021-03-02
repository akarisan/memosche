<?php 
require_once('configDatabase.php');

$user_data_row = $con->query("SELECT user_data FROM user WHERE user_id = '$user_id'");

foreach($user_data_row as $user_data_query_row){
    $user_data = $user_data_query_row['user_data'];
    if (empty($user_data)) {
        // 空ならFalse
        $select_state = 2;
    } else {
        // 中身あればTrue
        $select_state = 1;
        $user_data_arr = explode(',',$user_data);
    }
}
?>