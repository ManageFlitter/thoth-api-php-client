<?php
/**
 * ThothApiClient is a PHP client for the Thoth Twitter stats backend.
 * The class is a simple facade for the various underlying components.
 *
 * @see https://github.com/MelonMedia/thoth-api-php-client
 * @see https://github.com/MelonMedia/thoth
 *
 * @author Charl Mattthee
 * @package ThothApiClient
 */
class ThothApiClient
{
	const DEFAULT_HOST = '127.0.0.1';
	const DEFAULT_PORT = 8888;

	private $_connection;

  /**
   * Bootstraps a new instance of ThothApiClient.
	 * @param string $host
	 * @param int $port
	 * @param float $connectTimeout
	 */
	public function __construct($host = self::DEFAULT_HOST, $port = self::DEFAULT_PORT, $connectTimeout = NULL)
	{
		$this->setConnection(new ThothApiClient_Connection($host, $port, $connectTimeout));
	}

	/**
   * Sets connection.
	 * @param ThothApiClient_Connection
	 * @chainable
	 */
	public function setConnection($connection)
	{
		$this->_connection = $connection;
		return $this;
	}

    /**
     * Gets connection.
     * @return ThothApiClient_Connection
     */
    public function getConnection()
    {
        return $this->_connection;
    }

	/**
	 * Submits a PUT job to save data to the various analytics backends.
   * @param string $term
   * @param string $tweets array of 1 or more Twitter.attr serialized JSON objects
	 * @return string OK | ERR...
	 */
	public function put($params)
	{
		return $this->_dispatch(new ThothApiClient_Command_PutCommand($params));
	}

  /**
   * Submits a GET job to query data from the various analytics backends.
   * @param string $term
   * @param array $ds array of 1 or more data sources to query
   * @return string JSON object
   */
  public function get($params)
  {
    return $this->_dispatch(new ThothApiClient_Command_GetCommand($params));
  }

	/**
	 * Dispatches the specified command to the connection object.
	 *
	 * If a ConnectionException occurs, the connection is reset, and the command is
	 * re-attempted once.
	 *
	 * @param ThothApiClient_Command $command
	 * @return ThothApiClient_Response
	 */
	private function _dispatch($command)
	{
		try
		{
			$response = $this->_connection->dispatchCommand($command);
		}
		catch (ThothApiClient_Exception_ConnectionException $e)
		{
			$this->_reconnect();
			$response = $this->_connection->dispatchCommand($command);
		}

		return $response;
	}

	/**
	 * Recreates a connection object based on the existing connection settings.
	 */
	private function _reconnect()
	{
		$new_connection = new ThothApiClient_Connection(
			$this->_connection->getHost(),
			$this->_connection->getPort()
		);

		$this->setConnection($new_connection);
	}
}
?>

