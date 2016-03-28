<?php

$url = parse_url($_GET["url"]);

if($url['host'] != 'ia.media-imdb.com') {
  header('HTTP/1.0 406 Not Acceptable');
  exit();
}

header('Content-Type: image/jpeg');
readfile($_GET["url"]);
