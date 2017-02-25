<?php
require_once __DIR__ . '/vendor/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '1047692362026779',
  'app_secret' => 'eb0e154d837662a44da50375c1ae72ee',
  'default_graph_version' => 'v2.8',
]);

$accessToken = new Facebook\Authentication\AccessToken('1047692362026779|RvYNUDAID-ScCko_p2GVr-aypiw');
$fb->setDefaultAccessToken($accessToken);

// get an array of the most recent 100 posts
try {
  $response = $fb->get('/10487409466/feed?limit=100');
  $feed = $response->getDecodedBody();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$max = 0;
$max_post;
foreach($feed['data'] as $post) {
  // get reactions of post object
  try {
    $response = $fb->get('/' . $post['id'] . '/reactions?summary=total_count');
    $reactions = $response->getDecodedBody();
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }

  // record post with max reactions
  if($max < $reactions['summary']['total_count']) {
    $max = $reactions['summary']['total_count'];
    $max_post = $post;
  }
}



//echo $feed['data']['0']['id'];
//$plainOldArray = $response->getDecodedBody();
?>
