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
class ThothApiClient_GetCommandTest extends UnitTestCase
{
  /**
   * The clinet should be able to send a GET job
   */
  public function testSend()
  {
    $term = "foo";
    $ds = array("urls");
    $command = new ThothApiClient_Command_GetCommand($term, $ds);
    $reply = $command->send($this->_mockSocket());

    $this->assertNotNull(json_decode($reply));
    $decoded1 = json_decode($reply);
    $this->assertTrue($decoded1->id);
    $this->assertIsA($decoded1->id, 'String');
    $this->assertTrue($decoded1->reply);
    $this->assertIsA($decoded1->reply, 'stdClass');

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
    $socket->returns('read', $this->_mockResponse() . "\r\n");
    return $socket;
	}

  private function _mockResponse()
  {
    $reply = <<<EOF
{"id": "7f000101-50b5edea-8744-25f6bed4", "reply":{"urls":[[3,"2012111202",["http://bit.ly/sosorr","http://bit.ly/sosqji","http://tinyurl.com/akotxqw"]],[1,"2012111203",["http://tmblr.co/zdpwtwx6jfmv"]],[1,"2012111204",["http://bit.ly/vvqzwm"]],[1,"2012111205",["http://bit.ly/ts3o8g"]],[1,"2012111206",["http://www.change.org/en-au/petitions/australian-government-it-s-time-for-a-royal-commission-into-child-abuse-by-the-clergy-including-the-catholic-church?utm_source=action_alert&utm_medium=email&utm_campaign=13249&alert_id=ykzijgclip_uiqfpwvveo"]],[1,"2012111207",["http://equalwrites.wordpress.com/2009/11/10/why-gendered-stereotypes-actually-help-female-pedophiles/"]],[1,"2012111208",["http://www.meridianglobalservices.com/european-commision-issues-reasoned-opinion/"]],[5,"2012111209",["http://rt.com/news/syria-uk-military-intervention-468/","http://bit.ly/tt1wfu","http://bit.ly/vw4a2a","http://instagr.am/p/r7nd5pdv_e/","http://cnm.com.mv/beta/news/8348"]],[22,"2012111210",["http://dlvr.it/2t1tzl","http://bit.ly/sgghnn","http://goo.gl/fb/7z7bp","http://dlvr.it/2t1zwm","http://statweestics.com/184961/","http://bit.ly/jiej3z","http://goo.gl/fb/zwvpn","http://zulmalis.stiforpmovie.com","http://bit.ly/o2c5pi","http://sulia.com/my_thoughts/ac434d67-45cf-4e18-ae2b-9a93ec68e72f/?source=twitter","http://bit.ly/rnbktv","http://bitly.com/req5gt","http://bit.ly/xwgja6","http://flic.kr/p/bxsyew","http://dlvr.it/2nfbhy","http://awe.sm/i9nzx","http://ybl.co.za/?p=2919","http://news.yahoo.com/turnout-shaping-lower-2008-060934963--election.html","http://instagr.am/p/r7rewlmhjn/","http://bit.ly/ttimlv","http://goo.gl/cwdma","http://dlvr.it/2t1txm"]],[27,"2012111211",["http://goo.gl/fb/tzuzz","http://dlvr.it/2t28jx","http://bit.ly/sdn50y","http://buff.ly/xw7ngf","http://bit.ly/sgghnn","http://bit.ly/ze5zkl","http://goo.gl/p9rko","http://adf.ly/eae9y","http://freethinker.co.uk/?p=26891","http://fsp.gs/poxlws","http://bit.ly/q7egom","http://bit.ly/ztgk6z","http://dlvr.it/2t29bm","http://bit.ly/tupg3a","http://m.espn.go.com/nfl/story?storyid=8621065&i=twt&w=1cblx","http://speedgel.com","http://bit.ly/rnbktv","http://bit.ly/xwgja6","http://bitly.com/req5gt","http://bit.ly/ztkewb","http://bit.ly/r9ouha","http://www.businessinsider.com/jay-cutler-out-with-a-concussion-after-this-dirty-hit-2012-11?0=sportspage","http://dlvr.it/2nfbhy","http://bit.ly/tt8bgs","http://adf.ly/evbgo","http://goo.gl/fb/k1amj","http://sports.yahoo.com/news/eagles-qb-vick-leaves-eye-223303990--nfl.html"]]]}}
EOF;
    $reply = ltrim(rtrim($reply));;

    return $reply;
  }
}
?>
