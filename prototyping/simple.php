<?php

ini_set('include_path', ini_get('include_path') . ':../');
require_once('thoth_api_client_init.php');


$t = <<<EOT
[{"metadata":{"result_type":"recent","iso_language_code":"en"},"created_at":"Thu Feb 21 01:58:21 +0000 2013","id":304409695412703232,"id_str":"304409695412703232","text":"@PelleB @michaniskin we gotta book another miami clojure to see who comes out of the woodwork! i'll be down there periodically","source":"<a href=\"http:\/\/itunes.apple.com\/us\/app\/twitter\/id409789998?mt=12\" rel=\"nofollow\">Twitter for Mac<\/a>","truncated":false,"in_reply_to_status_id":304401835408646144,"in_reply_to_status_id_str":"304401835408646144","in_reply_to_user_id":810776,"in_reply_to_user_id_str":"810776","in_reply_to_screen_name":"PelleB","user":{"id":40270600,"id_str":"40270600","name":"Alan","screen_name":"alandipert","location":"Durham, NC","description":"clojure computor @freshdiet, tinkerer @splat_space","url":"http:\/\/alan.dipert.org","entities":{"url":{"urls":[{"url":"http:\/\/alan.dipert.org","expanded_url":null,"indices":[0,22]}]},"description":{"urls":[]}},"protected":false,"followers_count":715,"friends_count":334,"listed_count":67,"created_at":"Fri May 15 16:05:49 +0000 2009","favourites_count":10,"utc_offset":-18000,"time_zone":"Eastern Time (US & Canada)","geo_enabled":false,"verified":false,"statuses_count":1809,"lang":"en","contributors_enabled":false,"is_translator":false,"profile_background_color":"9AE4E8","profile_background_image_url":"http:\/\/a0.twimg.com\/profile_background_images\/609566316\/n1aa75o51d8bo2nkirbn.jpeg","profile_background_image_url_https":"https:\/\/si0.twimg.com\/profile_background_images\/609566316\/n1aa75o51d8bo2nkirbn.jpeg","profile_background_tile":true,"profile_image_url":"http:\/\/a0.twimg.com\/profile_images\/2539722263\/odq742bazyye7i60s5s6_normal.jpeg","profile_image_url_https":"https:\/\/si0.twimg.com\/profile_images\/2539722263\/odq742bazyye7i60s5s6_normal.jpeg","profile_banner_url":"https:\/\/si0.twimg.com\/profile_banners\/40270600\/1358384798","profile_link_color":"B40002","profile_sidebar_border_color":"77507C","profile_sidebar_fill_color":"E6F6DF","profile_text_color":"3156A0","profile_use_background_image":true,"default_profile":false,"default_profile_image":false,"following":null,"follow_request_sent":null,"notifications":null},"geo":null,"coordinates":null,"place":null,"contributors":null,"retweet_count":0,"entities":{"hashtags":[],"urls":[],"user_mentions":[{"screen_name":"PelleB","name":"Pelle Braendgaard","id":810776,"id_str":"810776","indices":[0,7]},{"screen_name":"michaniskin","name":"michaniskin","id":15859468,"id_str":"15859468","indices":[8,20]}]},"favorited":false,"retweeted":false}]
EOT;
$t = utf8_encode(ltrim(rtrim($t)));
// echo "TWEET: " . $t . "\n";

// Create a connection
$client = new ThothApiClient(array('readers' => array('127.0.0.1:8888'), 'writers' => array('127.0.0.1:8888', '127.0.0.1:8888')), 5, true);

// Data write/save example

// Collect metrics that return data series aggregated to a specific interval
echo "GET: " . $client->get(array('term' =>'google', 'ds' => array("summary"), 'interval' => 'hourly', 'relation' => 'platinumpro')) . "\n";
echo "PUT: " . $client->put(array('term' => 'commision', 'tweets' => $t, 'relation' => 'platinumpro')) . "\n";

// Collect metrics that can be collected in batches/paginated
// echo "GET: " . $client->get(array('term' =>'commision', 'ds' => array('tweets', 'users', 'urls'), 'interval' => 'daily', 'offset' => 50, 'length' => 2)) . "\n";

// Collect all metrics that can be collected in batches/paginated
// echo "GET: " . $client->get(array('term' =>'commision', 'ds' => array('tweets', 'users'))) . "\n";

// Collect all tweets related to a specific term + data source + timestamp
// echo "GET: " . $client->get(array('term' =>'commision', 'ds' => array('authors'), 'ts' => '2012122716')) . "\n";

// Collect all tweets related to a specific term + data source + timestamp
// scoped by the supplied filer
// echo "GET: " . $client->get(array('term' =>'#clojure', 'ds' => array('languages'), 'ts' => '2013010423', 'filter' => 'entities.hashtags fi "text" eq "clojars"')) . "\n";
// echo "GET: " . $client->get(array('term' =>'#clojure', 'ds' => array('languages'), 'ts' => '2013010423', 'filter' => 'iso_language_code eq "en"')) . "\n";

// Collect stats on a specific term
// echo "GET: " . $client->get(array('term' =>'#clojure', 'ds' => array('stats'), 'interval' => 'daily')) . "\n";

// Use compression (off by default)
//
// $client = new ThothApiClient(array('readers' => array('127.0.0.1'), 'writers' => array('127.0.0.1')));
//echo "GET: " . $client->get(array('term' =>'#clojure', 'ds' => array('stats'), 'interval' => 'daily')) . "\n";
//echo "GET: " . $client->get(array('term' =>'#clojure', 'ds' => array("summary"), 'interval' => 'hourly')) . "\n";

?>
