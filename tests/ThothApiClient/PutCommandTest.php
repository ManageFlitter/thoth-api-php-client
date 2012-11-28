<?php
require_once('tests/simpletest/autorun.php');
require_once('tests/Socket.php');

Mock::generate('Socket');

/**
 * Tests for ThothApiClient_Command implementations.
 *
 * @author Paul Annesley
 * @package ThothApiClient
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
class ThothApiClient_PutCommandTest extends UnitTestCase
{
  /**
   * The clinet should be able to send a PUT job
   */
  public function testSend()
  {
    $term = "foo";
    $tweets = $this->_mockTweets();
    $command = new ThothApiClient_Command_PutCommand($term, $tweets);
    $reply = $command->send($this->_mockSocket());

    $this->assertNotNull(json_decode($reply));
    $decoded1 = json_decode($reply);
    $this->assertTrue($decoded1->id);
    $this->assertIsA($decoded1->id, 'String');
    $this->assertTrue($decoded1->reply);
    $this->assertIsA($decoded1->reply, 'String');

    $reply = $command->send($this->_mockSocket());
    $decoded2 = json_decode($reply);
    $this->assertNotEqual($decoded1->id, $decoded2->id);
  }

	/**
   * Mocks
	 */
	private function _mockSocket()
	{
    $socket = new MockSocket();
    $socket->returns('write', 1);
    $socket->returns('read', "OK\r\n");
    return $socket;
	}

  private function _mockTweets()
  {
    $tweets = <<<EOF
    [{"metadata":{"result_type":"recent","iso_language_code":"en"},"created_at":"Mon Nov 12 11:08:19 +0000 2012","id":012345678901234567,"id_str":"012345678901234567","text":"Foo wants some bar... http://t.co/vOjWVnm0","source":"<a href=\"http://twitterfeed.com\" rel=\"nofollow\">twitterfeed</a>","truncated":false,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":01234567,"id_str":"01234567","name":"Foo","screen_name":"Foo_on_Bar","location":"Glenelg North, South Australia","description":"Foo! You know whow.","url":"http://foo.example.com/","entities":{"url":{"urls":[{"url":"http://foo.example.com/","expanded_url":null,"indices":[0,35]}]},"description":{"urls":[]}},"protected":false,"followers_count":771,"friends_count":699,"listed_count":68,"created_at":"Wed Apr 15 01:58:23 +0000 2009","favourites_count":12,"utc_offset":43200,"time_zone":"Wellington","geo_enabled":false,"verified":false,"statuses_count":5540,"lang":"en","contributors_enabled":false,"is_translator":false,"profile_background_color":"9AE4E8","profile_background_image_url":"http://a0.twimg.com/profile_background_images/115875589/twilk_background_4c230aec45ddd.jpg","profile_background_image_url_https":"https://si0.twimg.com/profile_background_images/115875589/twilk_background_4c230aec45ddd.jpg","profile_background_tile":true,"profile_image_url":"http://a0.twimg.com/profile_images/526810326/twitterProfilePhoto_normal.jpg","profile_image_url_https":"https://si0.twimg.com/profile_images/526810326/twitterProfilePhoto_normal.jpg","profile_link_color":"0000FF","profile_sidebar_border_color":"87BC44","profile_sidebar_fill_color":"E0FF92","profile_text_color":"000000","profile_use_background_image":true,"show_all_inline_media":false,"default_profile":false,"default_profile_image":false,"following":null,"follow_request_sent":null,"notifications":null},"geo":null,"coordinates":null,"place":null,"contributors":null,"retweet_count":0,"entities":{"hashtags":[],"urls":[{"url":"http://t.co/vOjWVnm0","expanded_url":"http://bit.ly/ZtGk6z","display_url":"bit.ly/ZtGk6z","indices":[116,136]}],"user_mentions":[]},"favorited":false,"retweeted":false,"possibly_sensitive":false}]
EOF;
    $tweets = ltrim(rtrim($tweets));;

    return $tweets;
  }
}
?>
