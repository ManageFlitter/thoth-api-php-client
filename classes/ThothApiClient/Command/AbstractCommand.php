<?php
/**
 * Common functionality for ThothApiClient_Command implementations.
 * @author Charl Matthee
 * @package ThothApiClient
 */
abstract class ThothApiClient_Command_AbstractCommand
	implements ThothApiClient_Command
{
  /**
   * Send a job to thothd.
	 * @see ThothApiClient_Command::send()
	 */
	public function send($socket)
	{
	}

	/**
	 * Creates a serialised job string for the given data
   * @param string $type GET | PUT
   * #param array $job Job to be serialised
	 * @return string JSON object serialised to a string
	 */
	protected function _createJob($type, $job)
	{
		return strtoupper($type) . ' ' . json_encode($job);
	}

  /**
   * Creates a serialised response string that contains the unique job ID
   * @param string $id The uniwue job ID
   * @param string $reply The reply from thothd
   * @return string JSON object serialised to a string
   */
  protected function _createReply($id, $reply)
  {
    $first = substr($reply, 0, 1);
    if ($first != '"' && $first != "'" && $first != '[' && $first != '{') $reply = '"' . $reply . '"';
    return '{"id": "' . $id . '", "reply":' . $reply . '}';
  }

  /**
   * Send off a job and process the response
   * @param string $job The JSON serialised job to submit
   * @return string $reply JSON reply object serialised to a string
   */
  protected function _sendAndProcess($socket, $job)
  {
    if ($socket->usingCompression()) $job = ThothApiClient_Compression::compress($job);
    $reply = $socket->write($job);

    if ($reply <= 0)
      throw new ThothApiClient_Exception_ConnectionException("Read nothing from the server");

    $reply = '';
    if ($socket->usingCompression()) {
      $reply = ThothApiClient_Compression::uncompress($socket->read());
    } else {
      $reply = $socket->read();
    }
    $reply = rtrim($reply);

    if (substr($reply, 0, 3) == 'ERR')
      throw new ThothApiClient_Exception_ProtocolException($reply);

    return $reply;
  }

  /**
   * Creates a unique job ID that encodes the server addresses sending
   * it to assist with tracing.
   * @return string Unique ID
   */
  protected function _uniqueId()
  {
    $t = explode(" ", microtime());

    return sprintf(
      '%08s-%08s-%04s-%04x%04x',
      $this->_ipToHex(),
      substr("00000000" . dechex($t[1]), -8),
      substr("0000" . dechex(round($t[0] * 65536)), -4),
      mt_rand(0, 0xffff),
      mt_rand(0, 0xffff)
    );
  }

  /**
   * Convert a dotted quad string IP to its hex representation.
   * @param string IP address
   * @return string Hex representaton of dotted quad IP address
   */
  protected function _ipToHex($quad='')
  {
    $hex = "";
    if ($quad == "") $quad = gethostbyname(php_uname('n'));
    $quads = explode('.', $quad);
    for ($i = 0; $i <= count($quads) - 1; $i++) {
      $hex .= substr("0" . dechex($quads[$i]), -2);
    }

    return $hex;
  }

  /**
   * Decodes a unique ID into its constituent parts.
   * @param string Unique ID
   * @return array Decoded unique ID components
   */
  protected function _decodeUniqueId($uid)
  {
    $decoded = Array();
    $u = explode("-", $uuid);
    if (is_array($u) && count($u) == 4) {
      $decoded = Array(
        'ip' => $this->_ipFromHex($u[0]),
        'unixtime' => hexdec($u[1]),
        'micro' => (hexdec($u[2]) / 65536)
      );
    }

    return $decoded;
  }

  /**
   * Convert a Hex IP address to its dotted quad representation.
   * @param string Hex IP address
   * @return string Dotted quad IP address
   */
  protected function _ipFromHex($hex='')
  {
    $quad = "";
    if(strlen($hex) == 8) {
      $quad .= hexdec(substr($hex, 0, 2)) . ".";
      $quad .= hexdec(substr($hex, 2, 2)) . ".";
      $quad .= hexdec(substr($hex, 4, 2)) . ".";
      $quad .= hexdec(substr($hex, 6, 2));
    }

    return $quad;
  }
}
?>
