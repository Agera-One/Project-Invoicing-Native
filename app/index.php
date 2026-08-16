<?php

if (!session_id()) session_start();

require_once '../app/config/default.php';
require_once '../app/core/Autoload.php';

Session::start();

$app = new App();
$app->boot()->run();