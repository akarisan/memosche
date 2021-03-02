<?php
if (!$_COOKIE['user']) {
    session_start();
    if (!$_SESSION['user']) {
        header("Location: https://irohaori.work/pj/memosche/");
        exit();
    } else {
        $user_id = $_SESSION['user'];
    }
} else {
    $user_id = $_COOKIE['user'];
}

// メンテナンス
$flags = false;
if ($flags == true) {
    if ($user_id == '48347264'
        or $user_id == '38064943') {
        echo "ok";
    } else {
        header("Location: https://irohaori.work/pj/memosche/user/maintenance.html");
        exit();
    }
}
?>