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

  private $_socket;
  private $_hostname;
  private $_port;
  private $_connectTimeout;

	/**
   * Bootstrap a ThothApiClient_Connection.
	 * @param string $hostname
	 * @param int $port
	 * @param float $connectTimeout
	 */
	public function __construct($hostname, $port, $connectTimeout = NULL)
	{
		if (is_null($connectTimeout) || !is_numeric($connectTimeout))
			$connectTimeout = self::DEFAULT_CONNECT_TIMEOUT;

		$this->_hostname = $hostname;
		$this->_port = $port;
		$this->_connectTimeout = $connectTimeout;
	}

	/**
	 * Sets a manually created socket, used for unit testing.
	 *
	 * @param ThothApiClient_Socket $socket
	 * @chainable
	 */
	public function setSocket(ThothApiClient_Socket $socket)
	{
		$this->_socket = $socket;
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
		$socket = $this->_getSocket();

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
	 * Socket reference for the connection to thothd.
	 * @return ThothApiClient_Socket
	 * @throws ThothApiClient_Exception_ConnectionException
	 */
	private function _getSocket()
	{
		if (!isset($this->_socket))
		{
      $this->_socket = new ThothApiClient_Socket_StreamSocketClient(
				$this->_hostname,
				$this->_port,
				$this->_connectTimeout
			);
		}

		return $this->_socket;
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
