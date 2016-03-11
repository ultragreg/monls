<?php
include_once('../simple_html_dom.php');
$auth = base64_encode('capdecomme:X17e7qes31--');

$aContext = array(
    'http' => array(
       'proxy' => 'fw-t-net.mipih.fr:3128', // This needs to be the server and the port of the NTLM Authentication Proxy Server. 
        'request_fulluri' => true,
        'header' => "Proxy-Authorization: Basic $auth",
    ),
);

$context = stream_context_create($aContext); 
 
$html= file_get_html('http://www.php.net', false, $context); 

echo $html->plaintext;
?>