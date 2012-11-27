<?php
/**
 * A mockable wrapper around PHP "socket".
 * Only the subset of socket actions required by ThothApiClient are provided.
 *
 * @author Charl Matthee
 * @package ThothApiClient
 */
interface ThothApiClient_Socket
{
	/**
	 * Writes a line of data to the socket.
	 * @param string $data
	 * @return void
	 */
	public function write($data);

	/**
	 * Reads a line from the socket.
	 * @return string
	 */
	public function read();
}
?>
