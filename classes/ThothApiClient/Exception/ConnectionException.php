<?php

/**
 * An exception relating to the client connection to thothd.
 * @author Charl Matthee
 * @package ThothApiClient
 * @licence http://www.opensource.org/licenses/mit-license.php
 */
class ThothApiClient_Exception_ConnectionException extends Exception
{
	/**
	 * @param int $errno The connection error code
	 * @param string $errstr The connection error message
	 */
	public function __construct($errno, $errstr)
	{
		parent::__construct(sprintf('Socket error %d: %s', $errno, $errstr));
	}
}
?>
