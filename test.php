<?php
require_once __DIR__ . '/vendor/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '1047692362026779',
  'app_secret' => 'eb0e154d837662a44da50375c1ae72ee',
  'default_graph_version' => 'v2.8',
]);

$accessToken = new Facebook\Authentication\AccessToken('1047692362026779|RvYNUDAID-ScCko_p2GVr-aypiw');
$fb->setDefaultAccessToken($accessToken);

// get a post
try {
  $response = $fb->get('/10487409466_10155064313729467/attachments');
  $post = $response->getDecodedBody();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

print_r($post);

?>
