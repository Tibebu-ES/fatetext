<?php
include('serverconfig.php');
include($GLOBALS['FATEPATH'] . '/fate.php');
include($GLOBALS['FATEPATH'] . '/webfate.php');

$netverbose = !$GLOBALS['ISPROD'];
$GLOBALS['DATA_PAGE'] = 'fate';
$GLOBALS['DATA_TEST'] = true;

$data = array('page_msg' => '');
if (isset($_REQUEST['page'])) {
  check_string_param('page', $data, $_REQUEST);
  if (isset($data['page'])) {
    $GLOBALS['DATA_PAGE'] = $data['page'];
  }
}
if (isset($_REQUEST['cmd'])) {
  check_string_param('cmd', $data, $_REQUEST);
}

net_init_session($netverbose);
if (isset($_SESSION['CHAT_ON'])) {
  $GLOBALS['CHAT_ON'] = $_SESSION['CHAT_ON'];
} else {
  $GLOBALS['CHAT_ON'] = false;
}
fl('INIT COMPLETED with session;');

try {

  if (isset($data['cmd'])) {
    switch ($data['cmd']) {
     case 'login':
      check_string_param('username', $data, $_REQUEST);
      check_string_param('password', $data, $_REQUEST);
      web_login_user($data);
      break;

     case 'logout':
      net_logout_user($data);
      break;

     case 'endsession':
      net_end_session();
      break;

     case 'updateuser':
      if (!isset($GLOBALS['USER_ID'])) {
        util_except('tried to update_user without a user_id');
      }
      web_update_user($GLOBALS['USER_ID']);
      break;

     case CHAT_OFF:
      $GLOBALS['CHAT_ON'] = $_SESSION['CHAT_ON'] = false;
      break;

     case CHAT_ON:
      $GLOBALS['CHAT_ON'] = $_SESSION['CHAT_ON'] = true;
      break;
    }
  }

  net_log_user_and_session_info($netverbose);

  //load template based on DATA_PAGE
  $data['_dpt'] = util_data_page_template_path();
  if (file_exists($data['_dpt'])) {
    echo util_page($data);
  } else {
    include('404.php');
    //util_except('missing template file for: ' . $data['_dpt']);
  }

} catch (Exception $ex) {
  util_log('Uncaught exception', $ex->getMessage());
  include('error.php');
}

db_make_log_entry();
print_log(); ?>
</body></html>
