<?php
require_once('./vendor/autoload.php');
require_once('initialize.php');

$doggos_result = find_all_in_doggos();

while($doggo = db_fetch_assoc($doggos_result)) {
  if(doggo_exists_in_all_doggos($doggo['id'])) {
    update_doggo($doggo);
  } else {
    insert_into_all_doggos($doggo);
  }
}

truncate_doggos_table();

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
    $response = $fb->get('/' . $post['id'] . '?fields=type,full_picture');
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
    $picture_posts[$post['id']] = $post;
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

// get id, src, and reaction_count of top 10 posts
$doggos = array();
for($i = 0; $i < 10; $i += 1) {
  $max = 0;
  $doggo = array();
  foreach($reaction_count as $key => $post) {
    if($max < $post['summary']['total_count']) {
      $max = $post['summary']['total_count'];
      $max_key = $key;
    }
  }
  $doggo['id'] = $max_key;
  $doggo['src'] = $picture_posts[$max_key]['full_picture'];
  $doggo['reaction_count'] = $reaction_count[$max_key]['summary']['total_count'];
  $doggos[] = $doggo;
  unset($reaction_count[$max_key]);
}

foreach($doggos as $doggo) {
  insert_into_doggos($doggo);
}

$time_post = microtime(true);
$exec_time = $time_post - $time_pre;

echo 'Time spent:' . $exec_time . '<br>';

?>
