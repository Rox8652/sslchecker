<?php
$g = stream_context_create (array("ssl" => array("capture_peer_cert" => true)));
$r = stream_socket_client("ssl://cricbuzz.com:443", $errno, $errstr, 30,
    STREAM_CLIENT_CONNECT, $g);
$cont = stream_context_get_params($r);
if (!empty($cont['options']['ssl']['peer_certificate'])) {
  echo "The website has a valid SSL certificate.\n";
} else {
  echo "The website does not have a valid SSL certificate.\n";
}
fclose($r);
?>