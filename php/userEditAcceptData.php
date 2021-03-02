<?php
require_once('configDatabase.php');
session_start();
$_SESSION = $_POST;
$day = $_SESSION['day'];
$limit = $_SESSION['limit'];
$name = htmlspecialchars($_SESSION['name']);
$entryfee = htmlspecialchars($_SESSION['entryfee']);
$entrysite = htmlspecialchars($_SESSION['entrysite']);
$reply_id = strval($_SESSION['reply_id']);

$stmt = $con->prepare("UPDATE accept SET data_day = :day, data_limit = :limit, data_name = :name, data_entryfee = :entryfee, data_entrysite = :entrysite WHERE data_tweet = '{$reply_id}'");

$stmt->bindValue(':day', $day, PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_STR);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':entryfee', $entryfee, PDO::PARAM_STR);
$stmt->bindValue(':entrysite', $entrysite, PDO::PARAM_STR);
$stmt->execute();

header("Location: https://irohaori.work/pj/memosche/user/edit/?i={$reply_id}&c=1");
exit();
?>