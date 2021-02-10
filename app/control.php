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
  util_assert(isset($data[TEMPLATE_CMD]),
              'doid not set in con_do_cmd()');
  $cmd = $data[TEMPLATE_CMD];

  if ($cmd != '') {
    switch ($cmd) {
     case 'chest':
      check_int_param('chestid', $data, $_REQUEST);
      check_string_param('tokstr', $data, $_REQUEST);
      break;

     case 'Login':
      check_string_param('username', $data, $_REQUEST);
      check_string_param('password', $data, $_REQUEST);
      web_login_user($data);
      if (web_logged_in()) {
        if (web_get_user_flag(web_get_user(), FATE_SPLASH_FLAG)) {
          $data['page'] = 'data';
        }
      }
      break;

     case 'silentlogout':
     case LOGOUT_CMD:
      web_logout_user($data, ($cmd == 'logout'));
      break;

     case 'Make Changes':
      check_string_param('defrowstxt', $data, $_REQUEST);
      check_string_param('numrowstxt', $data, $_REQUEST);
      $default_rows = intval($data['defrowstxt']);
      $current_rows = intval($data['numrowstxt']);
      if ($default_rows <= 1) {
        $data[TEMPLATE_MSG] = 'Num rows should be greater than 1';
      } else if ($default_rows >= 100) {
        $data[TEMPLATE_MSG] = 'Num rows should be less than 100';
      } else if ($current_rows <= 1) {
        $data[TEMPLATE_MSG] = 'Num rows should be greater than 1';
      } else if ($current_rows >= 100) {
        $data[TEMPLATE_MSG] = 'Num rows should be less than 100';
      } else {
        $data[TEMPLATE_MSG] = 'Default answer cols and rows updated.';
        user_set_current_rows(web_get_user(), $current_rows);
        user_set_default_rows(web_get_user(), $default_rows);
      }
      break;

     case 'Change Password':
      check_string_param('oldpasstxt', $data, $_REQUEST);
      check_string_param('newpasstxt', $data, $_REQUEST);
      web_change_password($data);
      break;

     case 'Guess':
     case 'Ask Question':
     case 'Record Answer':
      check_int_param('gemid', $data, $_REQUEST);
      check_string_param('steptxt', $data, $_REQUEST);

      $curuser = web_get_user();
      $curgem = $data['gemid'];
      util_assert($curgem = mod_get_user_lastgem($curuser));

      $gemdata = mod_load_gem($curgem);
      $whichint = 1;
      if ($cmd == 'Guess') {
        if ($data['steptxt'] == '') {

          if (!isset($data['category'])) {
            if (isset($_SESSION['temp']['category'])) {
              $category = $_SESSION['temp']['category'];
            } else {
              $category = DEFAULT_CATEGORY;
            }
          }

          $newgemid = mod_generate_gem($curuser, $stxt, $category);
          mod_update_user_lastgem($curuser, $newgemid);

        } else {

          if ($gemdata['stepint'] >= 1) {
            $data[TEMPLATE_MSG] = 'Please move onto to the next step.';
            break;
          }
          if (strtolower($data['steptxt']) == $gemdata['tokstr']) {
            $data[TEMPLATE_MSG] = 'You got a storycoin!';
            mod_increment_user_coins($curuser);          
          }
          if (isset($_REQUEST['one_line_chk'])) {
            $data['one_line_chk'] = true;
          } else {
            $data['one_line_chk'] = false;
          }

        }

      } else if ($cmd == 'Ask Question') {

        $whichint = 2;
        if ($gemdata['stepint'] >= 2) {
          $data[TEMPLATE_MSG] = 'The question is immutable.';
          break;
        }

        if ($data['steptxt'] == '') {
          $data[TEMPLATE_MSG] = 'Please give a non-empty response.';
          break;
        }

        if (isset($_REQUEST['authguess']) &&
            isset($_REQUEST['textguess'])) {
          check_string_param('authguess', $data, $_REQUEST);
          check_string_param('textguess', $data, $_REQUEST);
          mod_update_gem_auth_and_text($curgem, $data['authguess'],
                                       $data['textguess']);

          $correct_book_id = mod_get_gem_book($curgem);
          $text_coin = 0;
          if ($correct_book_id == $data['textguess']) {
            $text_coin = 1;
          }

          $correct_auth = mod_get_gem_auth($curgem);
          $auth_coin = 0;
          if ($correct_auth == $data['authguess']) {
            $auth_coin = 1;
          }

          if ($auth_coin == 1 && $text_coin == 1) {
            $data[TEMPLATE_MSG] = 'You got 2 storycoins!';
            mod_increment_user_coins($curuser, 2);
          } else if ($auth_coin == 1 || $text_coin == 1) {
            $data[TEMPLATE_MSG] = 'You got a storycoin!';
            mod_increment_user_coins($curuser);          
          }
        }

      } else if ($cmd == 'Record Answer') {
        $whichint = 3;
      }

      mod_record_step($curgem, $data['steptxt'], $whichint);
      mod_update_gem_step($curgem, $whichint);
      break;

     case 'Proceed':
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
      mod_update_user_lastdate(web_get_user());
      break;

     case 'loadgem':
      check_int_param('gemid', $data, $_REQUEST);
      $data['stxt'] = '';
      $curuser = web_get_user();
      mod_update_user_lastgem($curuser, $data['gemid']);
      mod_update_gem_lastloaded($data['gemid']);
      break;

     case 'Search':
      $category = '';
      $curuser = web_get_user();
      check_string_param('stxt', $data, $_REQUEST, '');
      check_string_param('category', $data, $_REQUEST, DEFAULT_CATEGORY);
      check_string_param('customtxt', $data, $_REQUEST, '');

      $category = $data['category'];
      if ($category == 'CLEAR' || $category == 'CUSTOM') {
        mod_update_user_lastgem($curuser, 0);
      } else {
        $stxt = $data['stxt'];
        if ($stxt == '') {
          $newgemid = mod_generate_gem($curuser, $stxt, $category);
          mod_update_user_lastgem($curuser, $newgemid);
        } else if (strlen($stxt) < 5) {
          $data[TEMPLATE_MSG] = 'Search terms must be longer than 4 chars.';
        }
      }
      break;

     case TOGGLE_SPLASH_CMD:
     case TOGGLE_CHAT_CMD:
     case TOGGLE_TEXT_CMD:
     case TOGGLE_OPTION_CMD:
     case TOGGLE_INVERTEDCS_CMD:
      web_toggle_user_flag(web_get_user(), mod_flag_from_toggle($cmd));
      break;

     case '=>':
     case '<=':
     case '\\/':
     case '/\\';
      $data[TEMPLATE_MSG] = 'TODO: ' . $cmd . ' the textarea';
      break;

     default:
      //do nothing if $cmd is not recognized
      break;
    }
  } //end if $cmd != ''

  //preserve the state of this page for
  //. the benefit of the next page load
  $temp_temp = $_SESSION['temp'];
  $_SESSION['temp'] = $data;
  foreach ($temp_temp as $sticky_key => $sticky_value) {
    if (!isset($_SESSION['temp'][$sticky_key])) {
      $_SESSION['temp'][$sticky_key] = $sticky_value;
    }
  }

}
