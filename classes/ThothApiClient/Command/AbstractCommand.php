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
	 * Creates a response string for the given data
	 * @param array
	 * @return string JSON object encoded to a string
	 */
	protected function _createResponse($type, $job)
	{
    $job["id"] = $this->_uniqueId();

		return strtoupper($type) . ' ' . json_encode($job) . "\r\n";
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
  protected function _ipToHex($quad = '')
  {
    $hex = "";
    if ($quad == "") $quad = gethostbyname(gethostname());
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
  protected function _ipFromHex($hex = '')
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
