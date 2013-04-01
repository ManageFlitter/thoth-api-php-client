<?php
/**
 * A thothd connection.
 *
 * @author Charl Matthee
 * @package ThothApiClient
 */
class ThothApiClient_Connection
{
	const DEFAULT_CONNECT_TIMEOUT = 5;

  private $_readerSocket;
  private $_writerSocket;
  private $_hosts;
  private $_readers;
  private $_writers;
  private $_connectTimeout;
  private $_compression;

	/**
   * Bootstrap a ThothApiClient_Connection.
	 * @param array  $hosts
	 * @param float  $connectTimeout
	 * @param mixed  $compression
	 */
	public function __construct($hosts, $connectTimeout=NULL, $compression)
	{
		if (is_null($connectTimeout) || !is_numeric($connectTimeout))
			$connectTimeout = self::DEFAULT_CONNECT_TIMEOUT;

		$this->_hosts = $hosts;
    $this->_readers = $hosts['readers'];
    $this->_writers = $hosts['writers'];
		$this->_connectTimeout = $connectTimeout;
		$this->_compression = $compression;
	}

	/**
	 * Sets a manually created socket, used for unit testing.
	 *
	 * @param ThothApiClient_Socket $socket
	 * @chainable
	 */
	public function setSocket(ThothApiClient_Socket $socket)
	{
		$this->_reader_socket = $this->_writer_socket = $socket;
		return $this;
	}

	/**
   * Delegate the command execution to the underlying command classes.
	 * @param object $command ThothApiClient_Command
	 * @return string JSON response from executing the command
	 * @throws ThothApiClient_Exception_ClientException
	 */
	public function dispatchCommand($command)
	{
		$socket = $this->_getSocket($command->getAction());

    return $command->send($socket);
	}

	/**
	 * Get the connection timeout.
	 * @return float
	 */
	public function getConnectTimeout()
	{
		return $this->_connectTimeout;
	}

	/**
	 * Get the connection host.
	 * @return string
	 */
	public function getHost()
	{
		return $this->_hostname;
	}

	/**
	 * Get the connection port.
	 * @return int
	 */
	public function getPort()
	{
		return $this->_port;
	}

  /**
   * Get the host list of readers
   * @return int
   */
  public function getReaders()
  {
    return $this->_readers;
  }

  /**
   * Get the host list of writers
   * @return int
   */
  public function getWriters()
  {
    return $this->_writers;
  }

	/**
	 * Socket reference for the connection to thothd.
   * @param  string  $action
	 * @return ThothApiClient_Socket
	 * @throws ThothApiClient_Exception_ConnectionException
	 */
	private function _getSocket($action)
	{
    $socket = $action == 'GET' ? '_readerSocket' : '_writerSocket';
    $hosts = $action == 'GET' ? $this->_readers : $this->_writers;

		if (!isset($this->$socket))
		{
      try {
        $this->$socket = new ThothApiClient_Socket_StreamSocketClient(
          $hosts[array_rand($hosts)],  // choose a random host to distribute load
          $this->_connectTimeout,
          $this->_compression
        );
      } catch (ThothApiClient_Exception_ConnectionException $e) {
        trigger_error("ThothApiClient_Exception_ConnectionException: $e", E_USER_ERROR);
      }
		}

		return $this->$socket;
	}

  /**
   * Checks connection to thothd.
   * @return true|false
   */
  // public function isServiceListening()
  // {
  //     try
  //     {
  //         $this->_getSocket();
  //     catch (ThothApiClient_Exception_ConnectionException $e)
  //     }
  //     {
  //         return false;
  //     }
  //     return true;
  // }
}
?>
