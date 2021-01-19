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

function con_tos_action(&$data) {
  if (!isset($data['toscheck'])) {
    util_except('invalid paramaters for tos action');
  }

  if (!$data['toscheck']) {
    $data[TEMPLATE_MSG] = 'You must agree to the terms before proceeding.';
    return;
  }

  $_SESSION['AGREETOS'] = true;
  $atf = web_get_user_flag(web_get_user(), AGREE_TOS_FLAG);
  if (!$atf ) {
    web_toggle_user_flag(web_get_user(), AGREE_TOS_FLAG);
  }
}

function con_do_cmd(&$data) {
  util_assert(isset($data[TEMPLATE_DOID]),
              'doid not set in con_do_cmd()');
  $doid = $data[TEMPLATE_DOID];

  if ($doid != '') {
    switch ($doid) {
     case 'Login':
      check_string_param('username', $data, $_REQUEST);
      check_string_param('password', $data, $_REQUEST);
      web_login_user($data);
      break;

     case 'silentlogout':
     case LOGOUT_CMD:
      net_logout_user($data, ($doid == 'logout'));
      break;

     case 'proceed':
       if (isset($_REQUEST['toscheck'])) {
         $data['toscheck'] = true;
       } else {
         $data['toscheck'] = false;
       }
       con_tos_action($data);
       break;

     case 'endsession':
      net_end_session();
      break;

     case 'updateuser':
      if (!web_logged_in()) {
        util_except('tried to update_user without a user_id');
      }
      web_update_user_lastdate(web_get_user());
      break;

     case 'search':
      check_string_param('stxt', $data, $_REQUEST);
      if (isset($_REQUEST['category'])) {
        $textarea = web_get_user_flag(web_get_user(), TEXT_AREA_FLAG);
        //TODO test that this fails silently and can be seen in the logs
        util_assert($textarea, 'search category'
                    . 'specified with a closed textarea');
        check_string_param('category', $data, $_REQUEST);
      }
      break;

     case TOGGLE_SPLASH_CMD:
     case TOGGLE_CHAT_CMD:
     case TOGGLE_TEXT_CMD:
      web_toggle_user_flag(web_get_user(), mod_flag_from_toggle());
      if (isset($_SESSION['doid'])) {
        //merge all the $data for display,
        //. except the previous 'doid'
        foreach ($_SESSION['doid'] as $namestr => $valuestr) {
          if ($namestr != 'doid') {
            $data[$namestr] = $valuestr;
          }
        }
      }
      break;

     default:
      $safedoid = htmlentities($doid);
      $data[TEMPLATE_MSG] = 'Unknown cmd = "' . $safedoid . '"';
      break;
    }
  } //end if $doid != ''

  //preserve the state of this page for
  //. the benefit of the next page load
  $_SESSION['doid'] = $data;
}
