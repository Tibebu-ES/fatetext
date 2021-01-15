<?php /* MIT License

Copyright (c) 2021 Todd Perry

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE. */

define('TOGGLE_SPLASH_CMD', 'tosplash');
define('TOGGLE_CHAT_CMD', 'tochat');
define('TOGGLE_TEXT_CMD', 'totext');
define('LOGOUT_CMD', 'logout');

function appcon_main_loop(&$data) {
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

function appcon_tos_action(&$data) {
  if (!isset($data['toscheck'])) {
    util_except('invalid paramaters for tos action');
  }
  $atf = web_get_user_flag(web_get_user(), AGREE_TOS_FLAG);
  if ($atf && $GLOBALS['APPPREFIX'] != 'fate') {
    util_except('trying to approve tos more than once');
  }

  if ($data['toscheck']) {
    web_toggle_user_flag(web_get_user(), AGREE_TOS_FLAG);
  } else {
    $data[TEMPLATE_PAGE_MSG] = 'You must agree to the terms before proceeding.';
  }
}

function appcon_do_cmd(&$data) {
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

   case 'nologout':
   case 'logout':
    net_logout_user($data, ($data['cmd'] == 'logout'));
    break;

   case 'proceed':
     if (isset($_REQUEST['toscheck'])) {
       $data['toscheck'] = true;
     } else {
       $data['toscheck'] = false;
     }
     appcon_tos_action($data);
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
