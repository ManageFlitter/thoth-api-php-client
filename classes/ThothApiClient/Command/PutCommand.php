<?php
/**
 * A PUT command.
 * @author Charl Matthee
 * @package ThothApiClient
 */
class ThothApiClient_Command_PutCommand extends ThothApiClient_Command_AbstractCommand
{
  /**
	 * Bootstrap a new PUT command.
	 * @param string $term The search term
	 * @param string $tweets Array of 1 or more Twitter.attr serialized JSON objects
	 */
	public function __construct($params=array())
	{
		$this->_term = $params['term'];
		$this->_tweets = $params['tweets'];
    $this->_action = 'PUT';
  }

  /**
   * Get the action
   * @see ThothApiClient_Command::getAction()
   */
  public function getAction()
  {
    return $this->_action;
  }

  /**
   * Send a PUT command to thotd.
	 * @see ThothApiClient_Command::send()
	 */
	public function send($socket)
	{
    $id = $this->_uniqueId();
    $job = array(
      "id" => $id,
      "term" => $this->_term,
      "tweets" => json_decode($this->_tweets)
    );
    error_log("JSON Last Error:".print_r(json_last_error(), TRUE));
    $reply = $this->_sendAndProcess($socket, $this->_createJob($this->_action, $job));

    return $this->_createReply($id, $reply);
	}
}
?>
