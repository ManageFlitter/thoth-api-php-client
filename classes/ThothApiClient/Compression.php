<?php
/**
 * Compression class.
 * @author Charl Matthee
 * @package ThothApiClient
 */
class ThothApiClient_Compression
{
  /**
   * Compress a string using GZIP.
   * @param string $string The string to compress
   * @param mixed  $level  The compression level to apply
   * @return string GZIP compressed string
   */
  static public function compress($string, $level=9)
  {
    if ($string == '') return $string;
    return gzcompress($string, $level);
  }

  /**
   * Uncompress a string using GZIP.
   * @param string $string The string to uncompress
   * @return string GZIP uncompressed string
   */
  static public function uncompress($string)
  {
    if ($string == '') return $string;
    return gzuncompress($string);
  }
}
?>
