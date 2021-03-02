<?php
//ユーザー用
//気になるイベントを追加
//気になるイベントから削除

require_once('configDatabase.php');

$mode = $_GET['mode'];
$user_id = $_GET['user'];
$data_id = $_GET['data'];

if (!$mode or !$user_id or !$data_id) {
    if($mode == "add"){
        header("Location: https://irohaori.work/pj/memosche/user/?c=20");
        exit();
    } elseif($mode == "del"){
        header("Location: https://irohaori.work/pj/memosche/user/?c=2");
        exit();
    }
}

try{
    /*
    $user_data_query = $con->query("SELECT user_info FROM user WHERE user_id = '$user_id'");
    foreach($user_data_query as $user_data_query_row){
        $user_info = $user_data_query_row['user_info'];
        // $user_account = $user_data_query_row['user_account'];
        // $user_data = $user_data_query_row['user_data'];
    }
    */

    if ($mode == "add") {
        
        // すでに登録されていないかのチェック
        $check = $con->query("SELECT data_id FROM fav WHERE user_id LIKE '{$user_id}' and data_id LIKE '{$data_id}'");
        if ($check->rowCount() < 1) {
            $c = 10;
        } else {
            // すでに登録済みの場合
            $c = 30;
        }
        
        /*
        if (empty($user_data)) {
            // 1つ目
            $user_data_arr = array($data_id);
            $c = 10;
        } else {
            // 2つ目
            # カンマ区切りを配列に変換
            $user_data_arr = explode(',',$user_data);
        
            if (in_array($data_id,$user_data_arr)) {
                // すでに登録済みの場合
                $c = 30;
            } else {
                //　新規登録の場合
                # 配列にデータ追加
                $user_data_arr[] = $data_id;
                $c = 10;
            }
        }
        # 配列をカンマ区切りに変換
        $user_data = implode(',',$user_data_arr);
        */
        
        # fav-DBに登録
        if ($c != 30) {
            $t_stmt = $con->prepare("INSERT INTO fav (user_id,data_id) VALUES (:user_id,:data_id)");
            $t_stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $t_stmt->bindValue(':data_id', $data_id, PDO::PARAM_STR);
            $t_stmt->execute();
        }
        
        /*
        if ($user_info == 'twitter' and $c != 30) {
            # twitteDBにツイートを登録
            $t_stmt = $con->prepare("INSERT INTO twitter (user_id,data_id,account) VALUES (:user_id,:data_id,:account)");
            $t_stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $t_stmt->bindValue(':data_id', $data_id, PDO::PARAM_STR);
            $t_stmt->bindValue(':account', $user_account, PDO::PARAM_STR);
            $t_stmt->execute();
        } else if ($user_info == 'email' and $c != 30) {
            # emailDBに内容を登録
            $t_stmt = $con->prepare("INSERT INTO email (user_id,data_id,account) VALUES (:user_id,:data_id,:account)");
            $t_stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
            $t_stmt->bindValue(':data_id', $data_id, PDO::PARAM_STR);
            $t_stmt->bindValue(':account', $user_account, PDO::PARAM_STR);
            $t_stmt->execute();
        } else {
            
        }
        */

    } elseif ($mode == "del"){
        /*
        # カンマ区切りを配列に変換
        $user_data_arr = explode(',',$user_data);
        
        # 削除したいデータを配列に変換
        $data_del_arr = explode(',',$data_id);
        
        # 配列から指定データ削除
        $user_data_arr = array_diff($user_data_arr,$data_del_arr);
        
        # 配列の番号を詰める
        // $user_data_arr = array_values($user_data_arr);
        
        # 配列をカンマ区切りに変換
        $user_data = implode(',',$user_data_arr);
        
        if ($user_info == 'twitter') {
            # twitterDBから削除
            $con->query("DELETE FROM twitter WHERE data_id LIKE '$data_id' and user_id LIKE '$user_id'");
        } else if ($user_info == 'email') {
            # emailDBから削除
            $con->query("DELETE FROM email WHERE data_id LIKE '$data_id' and user_id LIKE '$user_id'");
        } else {
            
        }
        */
        
        # fav-DBから削除
        $con->query("DELETE FROM fav WHERE data_id LIKE '$data_id' and user_id LIKE '$user_id'");
        
    }else{
        exit();
    }

    /*
    # クエリを実行
    $con->query("UPDATE user SET user_data = '{$user_data}' WHERE user_id = '{$user_id}'");
    */
} catch(Exeption $ex ){
    if ($mode == "add") {
        header("Location: https://irohaori.work/pj/memosche/user/?c=20");
        exit();
    } elseif ($mode == "del"){
        header("Location: https://irohaori.work/pj/memosche/user/?c=2");
        exit();
    }
}


if ($mode == "add") {
    header("Location: https://irohaori.work/pj/memosche/user/?c={$c}");
    exit();
} elseif($mode == "del"){
    header("Location: https://irohaori.work/pj/memosche/user/?c=1");
    exit();
}
?>
