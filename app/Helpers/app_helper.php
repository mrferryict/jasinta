<?php

function getUserIpAddress()
{
   if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      // IP address dari shared internet
      $ip = $_SERVER['HTTP_CLIENT_IP'];
   } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      // IP address dari proxy
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
   } else {
      // IP address langsung dari remote address
      $ip = $_SERVER['REMOTE_ADDR'];
   }
   return $ip;
}
