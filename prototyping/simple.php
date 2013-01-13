<?php

ini_set('include_path', ini_get('include_path') . ':../');
require_once('thoth_api_client_init.php');


$client = new ThothApiClient('127.0.0.1');
$t = <<<EOF
[{"metadata":{"result_type":"recent","iso_language_code":"en"},"created_at":"Mon Nov 12 11:08:19 +0000 2012","id":267946921958715392,"id_str":"267946921958715392","text":"Cardinal Pell flip-flops over Royal Commision into clerical sexual abuse: LAST Saturday, Australia’s most senior... http://t.co/vOjWVnm0","source":"<a href=\"http://twitterfeed.com\" rel=\"nofollow\">twitterfeed</a>","truncated":false,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":31303483,"id_str":"31303483","name":"Matt","screen_name":"ManonaSoapbox","location":"Glenelg North, South Australia","description":"An open minded free thinker and living in hopes that this mindset will catch-on.","url":"http://manonasoapbox.wordpress.com/","entities":{"url":{"urls":[{"url":"http://manonasoapbox.wordpress.com/","expanded_url":null,"indices":[0,35]}]},"description":{"urls":[]}},"protected":false,"followers_count":771,"friends_count":699,"listed_count":68,"created_at":"Wed Apr 15 01:58:23 +0000 2009","favourites_count":12,"utc_offset":43200,"time_zone":"Wellington","geo_enabled":false,"verified":false,"statuses_count":5540,"lang":"en","contributors_enabled":false,"is_translator":false,"profile_background_color":"9AE4E8","profile_background_image_url":"http://a0.twimg.com/profile_background_images/115875589/twilk_background_4c230aec45ddf.jpg","profile_background_image_url_https":"https://si0.twimg.com/profile_background_images/115875589/twilk_background_4c230aec45ddf.jpg","profile_background_tile":true,"profile_image_url":"http://a0.twimg.com/profile_images/526810376/twitterProfilePhoto_normal.jpg","profile_image_url_https":"https://si0.twimg.com/profile_images/526810376/twitterProfilePhoto_normal.jpg","profile_link_color":"0000FF","profile_sidebar_border_color":"87BC44","profile_sidebar_fill_color":"E0FF92","profile_text_color":"000000","profile_use_background_image":true,"show_all_inline_media":false,"default_profile":false,"default_profile_image":false,"following":null,"follow_request_sent":null,"notifications":null},"geo":null,"coordinates":null,"place":null,"contributors":null,"retweet_count":0,"entities":{"hashtags":[],"urls":[{"url":"http://t.co/vOjWVnm0","expanded_url":"http://bit.ly/ZtGk6z","display_url":"bit.ly/ZtGk6z","indices":[116,136]}],"user_mentions":[]},"favorited":false,"retweeted":false,"possibly_sensitive":false}]
EOF;
$t = ltrim(rtrim($t));

// Data write/save example
echo "PUT: " . $client->put(array('term' => 'commision', 'tweets' => $t)) . "\n";

// Collect metrics that return data series aggregated to a specific interval
// echo "GET: " . $client->get(array('term' =>'commision', 'ds' => array("urls", "authors"), 'interval' => 'hourly')) . "\n";

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

