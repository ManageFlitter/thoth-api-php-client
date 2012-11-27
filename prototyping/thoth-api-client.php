<?php

$host = gethostbyname("localhost");
$port = 8888;
$resource = "tcp://$host:$port";

$client = stream_socket_client($resource, $errno, $errorMessage, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT);

if ($client === false) {
  throw new UnexpectedValueException("Failed to connect: $errorMessage ($errno)");
}

fwrite($client, "GET {\"id\":\"1\",\"term\":\"commision\",\"ds\":[\"users\"]}\r\n");
// echo stream_get_contents($client);
// while (!feof($client)) { echo fgets($client, 2048); }
while ($buffer = fgets($client)) {
    echo $buffer;
    fwrite($client, "exit\r\n");
}
if (!feof($client)) {
    echo "Error: unexpected fgets() fail\n";
}
echo "Foo!\n";
fclose($client);

?>

