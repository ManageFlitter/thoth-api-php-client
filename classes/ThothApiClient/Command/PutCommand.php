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
	public function __construct($term, $tweets)
	{
		$this->_term = $term;
		$this->_tweets = $tweets;
  }

  /**
   * Send a PUT command to thotd.
	 * @see ThothApiClient_Command::send()
	 */
	public function send($socket)
	{
    $job = array(
      "id" => NULL,
      "term" => $this->_term,
      "tweets" => json_decode($this->_tweets)
    );
    $reply = $socket->write($this->_createResponse('PUT', $job));

    if ($reply <= 0)
      throw new ThothApiClient_Exception_ConnectionException("Read nothing from the server");

    $reply = rtrim($socket->read());

    // TODO: may want to do some checking of $reply here and throw and exception
    if (substr($reply, 0, 3) == 'ERR')
      throw new ThothApiClient_Exception_ProtocolException($reply);

    return $reply;
	}
}
?>
