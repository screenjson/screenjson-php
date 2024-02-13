<?php 

require_once ('vendor/autoload.php');

$screenplay = new ScreenJSON\Screenplay;

echo json_encode ($screenplay, JSON_PRETTY_PRINT);