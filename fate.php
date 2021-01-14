<?php
/* © 2021 TSUZY LLC ALL RIGHTS RESERVED */

include('lib/db.php');
include('lib/web.php');
include('lib/net.php');
include('lib/mvc.php');
include('lib/util.php');
include('lib/html.php');
include('lib/debug.php');

include('app/model.php');
include('app/view.php');
include('app/control.php');

$GLOBALS['START_TIME'] = microtime(true);
$GLOBALS['COPYRIGHT_HOLDER'] = 'TSUZY';
$GLOBALS['COPYRIGHT_URL'] = 'http://tsuzy.com';
