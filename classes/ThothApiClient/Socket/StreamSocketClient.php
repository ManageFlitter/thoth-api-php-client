<?php
/**
 * A ThothApiClient_Socket implementation around a fsockopen() stream.
 *
 * @author Charl Matthee
 * @package ThothApiClient
 */
class ThothApiClient_Socket_StreamSocketClient implements ThothApiClient_Socket
{
	/**
	 * The default timeout for a blocking read on the socket
	 */
	const SOCKET_TIMEOUT = 5;

	/**
	 * Number of retries for attempted writes which return zero length.
	 */
	const WRITE_RETRIES = 3;

  private $_socket;

	/**
   * Bootstrap a new ThothApiClient_Socket_StreamSocketClient.
	 * @param string $host
	 * @param int $port
	 * @param int $connectTimeout
	 */
	public function __construct($host, $port, $connectTimeout)
  {
    $this->_host = $host;
    $this->_port = $port;
    $this->_connectTimeout = $connectTimeout;
    $this->_resource = "tcp://$host:$port";
    $this->_errorNumber = NULL;
    $this->_errorMessage = NULL;

    $this->_socket = stream_socket_client(
      $this->_resource,
      $this->_errorNumber,
      $this->_errorMessage,
      $this->_connectTimeout,
      STREAM_CLIENT_CONNECT
    );
		if (!$this->_socket)
		{
      throw new ThothApiClient_Exception_ConnectionException(
        $this->_errorNumber, $this->_errorMessage . " (connecting to $host:$port)");
		}
	}

  /**
   * Clean up afetr ourselves.
   */
  function __destruct() {
    if($this->_socket) {
      fwrite($this->_socket, "quit\n");
      fgets($this->_socket);
      fclose($this->_socket);
      $this->_socket = null;
    }
  }

  /**
   * Write a line to the socket.
	 * @see ThothApiClient_Socket::write()
	 */
  public function write($data)
  {
    $reply = fwrite($this->_socket, $data);

    if ($reply == FALSE)
    {
      throw new ThothApiClient_Exception_SocketException('write() failed to send data');
    }

    return $reply;
  }

  /**
   * Read a line from the socket.
	 * @see ThothApiClient_Socket::write()
	 */
	public function read()
	{
    $reply = '';
		while ($buffer = fgets($this->_socket)) {
      if ($buffer == FALSE and substr($buffer, -1, 1) != "\n") {
        throw new ThothApiClient_Exception_SocketException('read() returned false');
      }
      $reply .= $buffer;
      if (substr($reply, -1, 1) == "\n") break;
    }

		return $reply;
	}
}
?>
