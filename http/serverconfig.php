<?php
$GLOBALS['CONFIGURL'] = "../config/fametext.php";

if (!file_exists($GLOBALS['CONFIGURL'])) {
  echo '[SYSTEM ERROR] Missing config file: ' . $GLOBALS['CONFIG_URL'];
  exit(0);
}

require_once($GLOBALS['CONFIGURL']);
