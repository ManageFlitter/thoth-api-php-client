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
	 * @param int $connectTimeout
	 */
	public function __construct($host, $connectTimeout, $compression)
  {
    $host = explode(':', $host);
    $this->_host = $host[0];
    $this->_port = $host[1];
    $this->_connectTimeout = $connectTimeout;
    $this->_compression = $compression;
    $this->_resource = "tcp://$this->_host:$this->_port";
    $this->_errorNumber = NULL;
    $this->_errorMessage = NULL;

    $this->_socket = stream_socket_client(
      $this->_resource,
      $this->_errorNumber,
      $this->_errorMessage,
      $this->_connectTimeout,
      STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT
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
      $logout = "quit\n";
      if ($this->usingCompression()) $logout = ThothApiClient_Compression::compress($logout);
      fwrite($this->_socket, $logout);

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
    stream_set_blocking($this->_socket, 0);
    while ( true )
    {
      $read   = array($this->_socket);
      $write  = NULL;
      $except = array($this->_socket);

      //Wait for up to 5 second to get something from the server
      if (false === ($num_changed_streams = stream_select($read, $write, $except, 5)))
      {
        // It timed out!
        print( "Socket internal error\n" );
        break;
      }

      if(empty($read)) //It must be an excecption instead...
      {
        // We got a socket error
        print("Socket error\n");
        break;
      }

      //print( var_export( array( $read, $write, $except), true )."\n" );
      //print( "Num changed streams: $num_changed_streams\n" );

      if ( $num_changed_streams == 0 )
      {
        // nothing changed int he stream, we hit a timeout!
        print( "Socket timeout\n" );
        break;
      }

      //We're ready to read.
      $chunk = fread($this->_socket, 1024);
      if( $chunk === FALSE )
      {
        print("fread failed\n");
        break;
      }
      //print( "Chnk: ".var_export( $chunk, true )."\n" );

      $reply.= $chunk;
      if ( (strlen($chunk)>0) && (ord($chunk[strlen($chunk)-1])==0) ) break;
    }

    echo "REPLY: $reply\n";

		return $reply;
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
