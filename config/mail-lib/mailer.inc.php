<?php

function mailer($fn, $f, $t, $server, $u, $p, $subject='', $body='', $html=false) {
// manage errors

if ($fn=="" || $f=="" || $t=="" || $server=="" || $u=="" || $p=="") return false;

if ($html==false) $ct = 'text/plain';
else $ct = 'text/html';

error_reporting(E_ALL); // php errors
define('DISPLAY_XPM4_ERRORS', true); // display XPM4 errors

// path to 'SMTP.php' file from XPM4 package
require_once 'SMTP.php';

if (!$c = fsockopen($server, 587, $errno, $errstr, 10)) return false;  // return $errstr; // ********* (6) mail server name or domain name

// ******** Stop editing ********//

// standard mail message RFC2822
$m = 'From: '.$fn.' <'.$f.">\r\n".
     'To: '.$t."\r\n".
     'Subject: '.$subject."\r\n".
     'Content-Type: '.$ct."\r\n\r\n".$body;

// expect response code '220'
if (!SMTP::recv($c, 220)) return false; //die(print_r($_RESULT));
// EHLO/HELO
if (!SMTP::ehlo($c, 'localhost'))
  if (!SMTP::helo($c, 'localhost')) return false; // die(print_r($_RESULT));
// AUTH LOGIN/PLAIN
if (!SMTP::auth($c, $u, $p, 'login'))
  if (!SMTP::auth($c, $u, $p, 'plain')) return false; //die(print_r($_RESULT));
// MAIL FROM
if (!SMTP::from($c, $f)) return false; //die(print_r($_RESULT));
// RCPT TO
if (!SMTP::to($c, $t)) return false; //die(print_r($_RESULT));
// DATA
if (!SMTP::data($c, $m)) return false; //die(print_r($_RESULT));
// RSET, optional if you need to send another mail using this connection '$c'
// SMTP::rset($c) or die(print_r($_RESULT));
// QUIT
SMTP::quit($c);
// close connection
@fclose($c);
// ********* End Stop editing ***********//
return true;
}

?>