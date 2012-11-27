<?php
/**
 * A GET command.
 * @author Charl Matthee
 * @package ThothApiClient
 */
class ThothApiClient_Command_GetCommand extends ThothApiClient_Command_AbstractCommand
{
  /**
   * Bootstrap a new GET command.
   * @param string $term The search term
   * @param array $ds An array of data sources to search
   * @param string $interval The interval data should be aggregated on
   */
  public function __construct($term, $ds, $interval = NULL)
  {
    $this->_term = $term;
    $this->_ds = $ds;
    $this->_interval = $interval;
  }

  /**
   * Send a GET command to thotd.
   * @see ThothApiClient_Command::send()
   */
  public function send($socket)
  {
    $job = array(
      "id" => NULL,
      "term" => $this->_term,
      "ds" => $this->_ds
    );
    if ($this->_interval) $job["interval"] = $this->_interval;
    $reply = $socket->write($this->_createResponse('GET', $job));

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

