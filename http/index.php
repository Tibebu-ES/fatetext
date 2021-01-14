<?php
include('serverconfig.php');
include($GLOBALS['FATEPATH'] . '/fate.php');

web_set_page(APP_IDENT);
$data = web_init_data();
app_main_loop($data);

try {

  if (isset($data['cmd'])) {
    app_do_cmd($data);
  }

  net_log_user_and_session_info();
  echo util_show_page($data);

} catch (Exception $ex) {
  util_log('Uncaught exception', $ex->getMessage());
  include('error.php');
}

db_make_log_entry();
print_log(); ?>
</body></html>
