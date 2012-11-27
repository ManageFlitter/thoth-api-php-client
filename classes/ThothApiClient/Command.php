<?php
/**
 * A command to be sent to thothd and response processing logic.
 * @author Charl Matthee
 * @package ThothApiClient
 */
interface ThothApiClient_Command
{
	/**
	 * Send a job to thothd.
	 * @return string
	 */
	public function send($socket);
}
?>
