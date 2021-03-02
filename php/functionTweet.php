<?php
$consumerKey = "eOuC1Zi0teqasIWWZ0nZc1PCN";
$consumerSecret = "w0SindfFJkVODnGohjFt3QIqDdVcpUQUVqPzelCzaK97kNocCG";
$accessToken = "1205807429880934400-stANld1XscBwLXzW2M1Mty9ksyjnsb";
$accessTokenSecret = "DW2aGhVdZQCiVdCFNgxGDUnDoiBvH9r7C7yXorYeJTp1j";
$twitter = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

function iwTweet($tweetcontent){
    $result = $twitter->post(
        "statuses/update",
        array("status" => $tweetcontent)
    );

    /*
if($twitter->getLastHttpCode() == 200) {
    // ツイート成功
    print "tweeted\n";
} else {
    // ツイート失敗
    print "tweet failed\n";
}*/
}
?>