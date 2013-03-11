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
   * @param string $ts An timestamp to search in
   * @param string $interval The interval data should be aggregated on
   * @param integer $offset The offset to return matches from
   * @param integer $length The number of items to retrieve from offset
   */
  public function __construct($params=array())
  {
    $this->_term = $params['term'];
    $this->_ds = $params['ds'];
    $this->_ts = array_key_exists('ts', $params) ? $params['ts'] : NULL;
    $this->_filter = array_key_exists('filter', $params) ? $params['filter'] : NULL;
    $this->_interval = array_key_exists('interval', $params) ? $params['interval'] : NULL;
    $this->_offset = array_key_exists('offset', $params) ? $params['offset'] : NULL;
    $this->_length = array_key_exists('length', $params) ? $params['length'] : NULL;
    $this->_action = 'GET';
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
   * Send a GET command to thotd.
   * @see ThothApiClient_Command::send()
   */
  public function send($socket)
  {
    $id = $this->_uniqueId();
    $job = array(
      'id' => $id,
      'term' => $this->_term,
      'ds' => $this->_ds
    );
    if (!is_null($this->_ts)) $job['ts'] = $this->_ts;
    if (!is_null($this->_filter)) $job['filter'] = $this->_filter;
    if (!is_null($this->_interval)) $job['interval'] = $this->_interval;
    if (!is_null($this->_offset)) $job['offset'] = $this->_offset;
    if (!is_null($this->_length)) $job['length'] = $this->_length;

    $reply = $this->_sendAndProcess($socket, $this->_createJob($this->_action, $job));

    return $this->_createReply($id, $reply);
  }
}
?>
