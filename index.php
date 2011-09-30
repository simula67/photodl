<?php 
/*
Copyright (C) 2011, Joji Antony
*/
require_once(dirname(__FILE__) . "/php-sdk/src/facebook.php");
require_once(dirname(__FILE__) ."/config/config.php");

$facebook = new Facebook($config);
$user_id = $facebook->getUser();
if( $user_id ) {
  try {
    $friends = $facebook->api("/" . $user_id . "/friends",'GET');
    $num_friends = count($friends['data']);
    echo "Found : " . $num_friends . " friends... <br />";
    for($i=0;$i<$num_friends;$i++) {
      echo "Name : " . $friends['data'][$i]["name"] . " , id : " . $friends['data'][$i]["id"] . "<br />";
      echo "Downloading...<br />";
      $url = "https://graph.facebook.com/" . $friends['data'][$i]["id"] . "/picture?type=large";
      $content = file_get_contents($url);
      $handle  = fopen($friends['data'][$i]["name"] . ".jpg","w") or die("Cannot open file");
      fwrite($handle,$content);
      fclose($handle);
    }
    
  }
  catch(FacebookApiException $e) {
    echo "Error: ". $e->getMessage();
  }
}
else {
  $loginUrl = $facebook->getLoginUrl();
  echo("<script> top.location.href='" . $loginUrl . "'</script>");
}

?>
