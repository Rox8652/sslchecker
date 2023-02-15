<?php
//The application uses invalid SSL certificates that an adversary might exploit
$g = stream_context_create (array("ssl" => array("capture_peer_cert" => true)));
$r = stream_socket_client("ssl://".$_SERVER['HTTP_HOST'].":443", $errno, $errstr, 30,
    STREAM_CLIENT_CONNECT, $g);
$cont = stream_context_get_params($r);
  if (!empty($cont['options']['ssl']['peer_certificate'])) {
    echo "The website has a valid SSL certificate.\n<br>";
  } 
  else {
    echo "The website does not have a valid SSL certificate.\n";
  }


//The application uses self signed SSL certificate
$cert = file_get_contents($_SERVER['HTTP_HOST']); // Replace with the URL of the website you want to check
$certData = openssl_x509_parse($cert);
$issuer = $certData['issuer'];

if (strpos($issuer['CN'], 'self-signed') !== false) {
    echo "The SSL certificate is self-signed.";
} else {
    echo "The SSL certificate is issued by a Certificate Authority.<br>";
}

//expireing Date of ssl
$expirationDate = date('Y-m-d H:i:s', $certData['validTo_time_t']);
echo "The SSL certificate expires on: " . $expirationDate;

fclose($r);

//Application uses SSL Cookie without secure flag set
if (array_key_exists('mycookie', $_COOKIE)) {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        // The cookie was sent over a secure connection
        if (array_key_exists('mycookie', $_COOKIE) && $_COOKIE['mycookie'] === 'myvalue') {
            // The cookie is valid and was not tampered with
            echo "Valid SSL cookie found.";
        } else {
            // The cookie is invalid or was tampered with
            echo "Invalid SSL cookie found.";
        }
    } else {
        // The cookie was sent over an insecure connection
        echo "Insecure SSL cookie found.";
    }
} else {
    // The cookie was not set
    echo "No SSL cookie found.";
}


//Obsolete SSL/TLS protocol detection.

$transports = stream_get_transports();

if (in_array('ssl', $transports) && in_array('tls', $transports)) {
    echo "Current SSL/TLS protocols are supported.";
} else {
    echo "Obsolete SSL/TLS protocols may be supported.";
}



?>
