<?php
/**
 * A command to be sent to thothd and response processing logic.
 * @author Charl Matthee
 * @package ThothApiClient
 */
interface ThothApiClient_Command
{
  /**
   * Get the action
   * @return string
   */
  public function getAction();

	/**
	 * Send a job to thothd.
	 * @return string
	 */
	public function send($socket);
}
?>
