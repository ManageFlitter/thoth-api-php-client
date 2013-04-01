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
	const DEFAULT_COMPRESSION = FALSE;

	private $_connection;

  /**
   * Bootstraps a new instance of ThothApiClient.
	 * @param string $host
	 * @param int    $port
	 * @param float  $connectTimeout
   * @param bool   $compression
	 */
	public function __construct($hosts=array('readers' => array('127.0.0.1:8888'), 'writers' => array('127.0.0.1:8888')), $connectTimeout=NULL, $compression=FALSE)
	{
    $this->_hosts = $hosts;
    $this->_connectTimeout = $connectTimeout;
    $this->_compression = $compression;
		$this->setConnection(new ThothApiClient_Connection($this->_hosts, $this->_connectTimeout, $this->_compression));
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
    $this->setConnection(new ThothApiClient_Connection($this->_hosts, $this->_connectTimeout, $this->_compression));
	}
}
?>

