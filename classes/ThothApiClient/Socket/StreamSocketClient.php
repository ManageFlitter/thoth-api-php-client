<?php
/**
 * A ThothApiClient_Socket implementation around a fsockopen() stream.
 *
 * @author Charl Matthee
 * @package ThothApiClient
 */
class ThothApiClient_Socket_StreamSocketClient implements ThothApiClient_Socket
{
  private $_socket;

	/**
   * Bootstrap a new ThothApiClient_Socket_StreamSocketClient.
	 * @param string $host
	 * @param int $connectTimeout, default 5 seconds
	 * @param int $compression, defaults FALSE
	 */
	public function __construct($host, $connectTimeout=5, $compression=FALSE)
  {
    $host = explode(':', $host);
    $this->_host = $host[0];
    $this->_port = $host[1];
    $this->_connectTimeout = $connectTimeout;
    $this->_compression = $compression;
    $this->_resource = "tcp://$this->_host:$this->_port" . '/' . rand (1, 10);  // slash and random number are required to force new socket creation
    $this->_errorNumber = NULL;
    $this->_errorMessage = NULL;

    $this->_socket = $this->_buildConnection();
		if (!$this->_socket)
		{
      throw new ThothApiClient_Exception_ConnectionException(
        $this->_errorNumber, $this->_errorMessage . " (connecting to $this->_resource)");
		}
	}

  /**
   * Build connection.
   */
  private function _buildConnection() {
    return stream_socket_client(
      $this->_resource,
      $this->_errorNumber,
      $this->_errorMessage,
      $this->_connectTimeout,
      STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT
    );
  }

  /**
   * Clean up afert ourselves.
   */
  function __destruct() {
    if($this->_socket) {
      if (PHP_VERSION_ID >= 50406 && !feof($this->_socket)) {
        $this->write('quit');
        $this->read();
        fclose($this->_socket);
      }
    }
    $this->_socket = null;
  }

  /**
   * Write a line to the socket.
	 * @see ThothApiClient_Socket::write()
	 */
  public function write($data)
  {
    $data = base64_encode(gzdeflate($data, 6)) . "\n";
    $bytes_written = 0;
    $bytes_total = strlen($data);
    $closed = FALSE;

    while (!$closed && $bytes_written < $bytes_total) {
      $written = fwrite($this->_socket, $data);

      if (!$written || $written == 0) {
        $closed = TRUE;
        break;
      } else {
        $bytes_written += $written;
      }
    }

    if ($closed) {
      fclose($this->_socket);
      sleep(rand(1, 3));
      throw new ThothApiClient_Exception_ConnectionException(0, 'write() failed to send data');
    }

    return $bytes_written;
  }

  /**
   * Read a line from the socket.
	 * @see ThothApiClient_Socket::write()
	 */
	public function read()
	{
    $reply = $buffer = '';

    while ($buffer = fgets($this->_socket)) {
      if ($buffer == FALSE and substr($buffer, -1, 1) != "\n") {
        throw new ThothApiClient_Exception_ConnectionException(0, 'read() returned false');
      }
      $reply .= $buffer;
      if (substr($reply, -1, 1) == "\n") break;
    }

    return ($reply == "") ? $reply : gzinflate(base64_decode($reply, 6));
	}

  /**
   * Helper to determine if we have compression turned on or off.
   * @return bool
   */
  public function usingCompression()
  {
    return $this->_compression;
  }

}
?>
