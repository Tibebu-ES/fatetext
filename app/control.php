<?php
define('TOGGLE_SPLASH_CMD', 'tosplash');
define('TOGGLE_CHAT_CMD', 'tochat');
define('TOGGLE_TEXT_CMD', 'totext');
define('LOGOUT_CMD', 'logout');

function app_main_loop(&$data) {
  if (isset($_REQUEST['page'])) {
    check_string_param('page', $data, $_REQUEST);
    if (isset($data['page'])) {
      $GLOBALS['DATA_PAGE'] = $data['page'];
    }
  }

  switch ($data['page']) {
   case 'art':
    check_string_param('datestr', $data, $_REQUEST);
    break;
  }
  
  if (isset($_REQUEST['cmd'])) {
    check_string_param('cmd', $data, $_REQUEST);
    $data['cmd'] = strtolower($data['cmd']);
  }

  net_init_session(true);
  fl('INIT COMPLETED with session;');
}

function app_do_cmd(&$data) {
  $data[TEMPLATE_PAGE_MSG] = '';

  switch ($data['cmd']) {
   case 'test':
    $test_str = 'testing page: ' . $data['page'];
    util_show_content($test_str, $data);
    break;

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

   case 'search':
    check_string_param('stxt', $data, $_REQUEST);
    if (isset($_REQUEST['category'])) {
      $textarea = web_get_user_flag(web_get_user(), TEXT_AREA_FLAG);
      //TODO test that this fails silently and can be seen in the logs
      util_assert($textarea, 'search category specified with a closed textarea');
      check_string_param('category', $data, $_REQUEST);
    }
    break;

   case TOGGLE_TEXT_CMD:
    web_toggle_user_flag(web_get_user(), TEXT_AREA_FLAG);
    break;

   case TOGGLE_CHAT_CMD:
    web_toggle_user_flag(web_get_user(), CHAT_OPEN_FLAG);
    break;

   case TOGGLE_SPLASH_CMD:
    web_toggle_user_flag(web_get_user(), FATE_SPLASH_FLAG);
    break;

   default:
    $safecmd = htmlentities($data['cmd']);
    $data[TEMPLATE_PAGE_MSG] = 'Unknown cmd = "' . $safecmd . '"';
    break;
  }
}
