<?php
/**
 * Thoth API Client init script.
 * Sets up include paths based on the directory this file is in.
 * Registers an SPL class autoload function.
 *
 * @author Charl Matthee
 * @package ThothApiClient
 */

$thothApiClientClassRoot = dirname(__FILE__) . '/classes';
require_once($thothApiClientClassRoot . '/ThothApiClient/ClassLoader.php');

ThothApiClient_ClassLoader::register($thothApiClientClassRoot);
?>
