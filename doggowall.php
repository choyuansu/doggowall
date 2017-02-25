<?php
require_once __DIR__ . '/vendor/autoload.php';

$fb = new Facebook\Facebook([
  'app_id' => '1047692362026779',
  'app_secret' => 'eb0e154d837662a44da50375c1ae72ee',
  'default_graph_version' => 'v2.8',
]);

$accessToken = new Facebook\Authentication\AccessToken('1047692362026779|RvYNUDAID-ScCko_p2GVr-aypiw');
$fb->setDefaultAccessToken($accessToken);

$time_pre = microtime(true);

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

$picture_posts = array();
foreach($feed['data'] as $post) {
  // get type of post object
  try {
    $response = $fb->get('/' . $post['id'] . '?fields=type');
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

  // record post with max reactions
  if(strcmp($post['type'], 'photo') == 0) {
    $picture_posts[] = $post;
  }
}

// get reaction count of each picture post
$reaction_count = array();
foreach($picture_posts as $post) {
  // get reactions of post object
  try {
    $response = $fb->get('/' . $post['id'] . '/reactions?summary=total_count');
    $reaction_count[$post['id']] = $response->getDecodedBody();
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }
}

// get top 10 posts
$top10 = array();
for($i = 0; $i < 10; $i += 1) {
  $max = 0;
  foreach($reaction_count as $key => $post) {
    if($max < $post['summary']['total_count']) {
      $max = $post['summary']['total_count'];
      $max_key = $key;
      $max_post = $post;
    }
  }
  $top10[$max_key] = $max_post;
  unset($reaction_count[$max_key]);
}

$pics = array();
foreach($top10 as $key => $post) {
  try {
    echo $key . '<br>';
    $response = $fb->get('/' . $key . '?fields=full_picture');
    $pics[] = $response->getDecodedBody();
  } catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
  } catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
  }
}

//$url = $post['full_picture'];
//$dir = './doggo.jpg';
//file_put_contents($dir, file_get_contents($url));

$time_post = microtime(true);
$exec_time = $time_post - $time_pre;

echo 'Time spent:' . $exec_time . '<br>';

foreach($pics as $pic) {
  echo '<img src="' . $pic['full_picture'] . '"><br>';
}
?>

