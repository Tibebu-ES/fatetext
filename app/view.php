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

function app_get_tos_page($local_page_msg) {
  $rv = gen_h(2, app_get_page_title() . ' ' . gen_i('Terms of Service'));
  $rv .= '<div class="content">';

  $home_url = gen_url($splash, 'silentlogout');
  $home_link = gen_link($home_url, 'Go back to the login page');
  $rv .= gen_p($home_link, 'page_heading');
  $c0 = 'The following langauge was derived from the MIT License';
  $cc = 'Copyright (c) 2021 Todd Perry';
  $c1 = 'Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:';
  $c2 = 'The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.';
  $c3 = 'THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.';
  $tosmsg = 'Please agree to the following terms before proceeding.';
  $rv .= gen_p(gen_i($tosmsg), 'lastline');
  if ($local_page_msg != '') {
    $rv .= gen_p($local_page_msg, 'page_msg');
  }
  $rv .= '<div class="content">';
  $rv .= gen_tos_form();
  $rv .= gen_p(gen_u($c0) . ':');
  $rv .= gen_p($cc);
  $rv .= gen_p($c1);
  $rv .= gen_p($c2);
  $rv .= gen_p($c3);
  $rv .= '</div></div>';
  $rv .= gen_copyright_notice();
  return $rv;
}

function app_get_header_extra($inpage, $add_el = true) {
  $css_class = 'header';
  $rv = PADDING_STR . SPACER_STR . PADDING_STR;
  
  switch ($inpage) {
   case 'home':
   case 'data':
    $fatesplash = web_get_user_flag(web_get_user(), FATE_SPLASH_FLAG);
    $link_str = 'splash';
    //do the opposite for 'data' and 'home'
    if ($fatesplash == ($inpage == 'data')) {
      $link_str = strtoupper($link_str);
    }

    $link_url = gen_url($inpage, TOGGLE_SPLASH_CMD);
    $rv .= gen_link($link_url, $link_str, $css_class);
    break;

   case 'search':
    $link_url = gen_url($inpage, TOGGLE_TEXT_CMD);
    if (web_get_user_flag(web_get_user(), TEXT_AREA_FLAG)) {
      $rv .= gen_link($link_url, '--AREA', $css_class);
    } else {
      $rv .= gen_link($link_url, 'text++', $css_class);            
    }
    break;

   case 'settings':
    $link_url = gen_url('home', LOGOUT_CMD);
    $rv .= gen_link($link_url, 'LOGOUT', $css_class);
    break;

   default:
    $link_text = FAME_IDENT;
    $link_url = FAME_URL;
    $rv .= gen_link($link_url, $link_text, $css_class, false);
    break;
  }

  if ($add_el) $rv .= "\n";
  return $rv;
}

function app_get_header_links($inpage, $links_arr, $add_el = true) {
  $rv = PADDING_STR;

  $first = true;
  foreach ($links_arr as $link_str) {

    if ($first) {
      $first = false;
    } else {
      $rv .= PADDING_STR . '|' . PADDING_STR;
    }

    $lower_link = strtolower($link_str);
    if ($lower_link == $inpage) {
      $rv .= gen_b($link_str);
    } else {
      $link_url = gen_url($lower_link);
      $rv .= gen_link($link_url, $link_str, 'header');
    }
  }

  if ($add_el) $rv .= "\n";
  return $rv;
}

function app_get_smart_spacer($inpage, $add_el = true) {
  $rv = PADDING_STR;
  if ($inpage == 'hall' ||
      (!web_logged_in() &&
       $inpage == 'home')) {
    $rv .= gen_b(SPACER_STR);
  } else {
    $rv .= gen_link(gen_url('hall'), gen_b(SPACER_STR));
  }

  if ($add_el) $rv .= "\n";
  return $rv;
}

function app_get_page_title($inpage = '') {
  $rv = $GLOBALS['APPTITLE'];
  if ($inpage != '') {
    $rv .= SPACER_STR . $inpage;
  }
  return $rv;
}

function gen_gem_quest_form($gemdata, $add_el = true) {
  $rv = '';
  $elem_arr = array();
  $elem_arr []= gen_text_area('steptxt', '', 3, STEP_COLS, '', $add_el);
  $elem_arr []= gen_p(gen_input('submit', 'cmd', 'Ask Question', $add_el));
  $elem_arr []= gen_input('hidden', 'gemid', $gemdata['gemid'], $add_el);
  $rv = gen_form($elem_arr, gen_url('search'));
  return $rv;
}

function gen_gem_answer_form($gemdata, $stepvalue, $lastsaved, $add_el = true) {
  $rv = '';
  $elem_arr = array();
  $elem_arr []= gen_text_area('steptxt', $stepvalue, 5, STEP_COLS, '', $add_el);
  $recrow = gen_input('submit', 'cmd', 'Record Answer', $add_el);
  if ($lastsaved != 0) {
    $recrow .= PADDING_STR . ' (' . gen_i('last saved at ');
    $recrow .= fd($lastsaved) . ')';
  }
  $elem_arr []= gen_p($recrow);
  $elem_arr []= gen_input('hidden', 'gemid', $gemdata['gemid'], $add_el);
  $rv = gen_form($elem_arr, gen_url('search'));
  return $rv;
}

function gen_gem_guess_form($gemdata, $add_el = true) {
  $rv = '';
  $elem_arr = array();
  $elem_arr []= gen_input('submit', 'cmd', 'Guess', $add_el);
  $elem_arr []= gen_txt_input('steptxt', '', GUESS_COLS, '', $add_el);
  $elem_arr []= gen_input('hidden', 'gemid', $gemdata['gemid'], $add_el);
  $rv = gen_form($elem_arr, gen_url('search'));
  return $rv;
}

function gen_tos_form($add_el = true) {
  $rv = '';
  $elem_arr = array();
  $elem_arr []= gen_input('submit', 'cmd', 'Proceed', $add_el);
  $elem_arr []= gen_input('checkbox', 'toscheck', '1', $add_el);
  $elem_arr []= gen_span('I agree to the following terms:');
  $splash = 'home';
  if (web_get_user_flag(web_get_user(), FATE_SPLASH_FLAG)) {
    $splash = 'data';
  }
  $rv = gen_form($elem_arr, gen_url($splash));
  return $rv;
}

function gen_search_form($safetext = '', $istextarea = false, $selcat = '', $add_el = true) {
  $rv = '';
  $elem_arr = array();
  $option_arr = array('fate', 'data', 'docs', 'CLEAR',
                      'suzyThe', 'theBard',
                      'bibleOS', 'ancienT');
  if ($istextarea) {
    $inuser = web_get_user();
    $inrows = mod_get_user_int($inuser, USER_SEARCH_ROWS);
    $incols = mod_get_user_int($inuser, USER_SEARCH_COLS);
    $elem_arr []= '<div class="row">';
    $elem_arr []= '<div class="column">';
    $elem_arr []= '<div class="textcontrols">';
    $elem_arr []= '<div class="simpleborder">';
    $elem_arr []= gen_input('submit', TEMPLATE_CMD, 'Search', $add_el);
    $elem_arr []= '</div>';
    $elem_arr []= gen_select_input('category', $option_arr, $selcat, $add_el);
    $elem_arr []= '</div></div><div class="column">';    
    $elem_arr []= gen_text_area('stxt', $safetext, 4, SEARCH_COLS,
                                SEARCH_PLACEHOLDER, $add_el);
    $elem_arr []= '</div></div>';
  } else {
    $elem_arr []= gen_input('submit', TEMPLATE_CMD, 'Search', $add_el);
    $elem_arr []= gen_select_input('category', $option_arr, $selcat, $add_el);
    $insize = SEARCH_COLS;
    $elem_arr []= gen_txt_input('stxt', $safetext, $insize,
                            SEARCH_PLACEHOLDER, $add_el);
  }
  $rv = gen_form($elem_arr, gen_url('search'));
  return $rv;
}

function gen_login_form($add_el = true) {
  $rv = '';
  $insize = LOGIN_COLS;

  $elem_arr = array();
  $elem_arr []= gen_input('submit', TEMPLATE_CMD, 'Login', $add_el);

  $usertxt = gen_txt_input('username', '', $insize,
                           'Username', $add_el);
  $usertxt .= '<br>';

  $passtxt = gen_txt_input('password', '', $insize,
                           'Password', $add_el);
  $passtxt = gen_span($passtxt, 'nextline');
  
  $elem_arr []= $usertxt;
  $elem_arr []= $passtxt;

  $rv = gen_form($elem_arr, gen_url('home'));
  return $rv;
}

function gen_chat_with_fate($inpage, $is_open) {
  $rv = '';
  if ($is_open) {
    $chat_arr = array();
    $gemarr = mod_get_user_gems(web_get_user(), NUM_CHAT_ROWS);
    if (count($gemarr) == 0) {
      $chat_arr []= gen_i('No gems.');
    } else {
      foreach ($gemarr as $gem) {
        $dateurl = gen_url('search', 'loadgem');
        $dateurl .= gen_url_param('gemid', $gem['gemid']);
        $datestr = fd($gem['datecreated']);
        $chatstr = gen_link($dateurl, $datestr, 'header') . '<br>';
        if ($gem['stepint'] == 0) {
          $chatstr .= $gem['wordcount'] . ' words (';
          $chatstr .= $gem['charcount'] . ' letters)<br>';
          $chatstr .= gen_i('guess: ') . gen_b('_______!');
        } else {
          $chatstr .= gen_i('blank: ') . gen_b($gem['tokstr']) . '<br>';
          $guessdata = mod_load_step($gem['gemid'], 1);
          $chatstr .= gen_i('guess: ') . gen_u($guessdata['stepstr']);
        }
        $chat_arr []= $chatstr;
      } //end foreach gems
    } //end if no gems
    $chat_win = gen_chat_win($chat_arr);
    $title_html = gen_chat_title($inpage, TOGGLE_CHAT_CMD, '-');
    $title_bar = gen_title_bar($title_html);
    $rv = gen_title_box($title_bar, $chat_win);
  } else {
    $title_html = gen_chat_title($inpage, TOGGLE_CHAT_CMD, '+');
    $rv = gen_title_bar($title_html, true);
  }
  return $rv;
}

function gen_chat_title($inpage, $action_cmd, $action_char) {
  $chat_url = gen_url($inpage, $action_cmd);
  $rv = '<div class="row"><div class="column">';
  $action_str = 'Chat with ' . app_get_page_title();
  $rv .= gen_link($chat_url, $action_str, 'plain');
  $rv .= '</div><div class="right_column">';
  $rv .= gen_link($chat_url, '&nbsp;&nbsp;&nbsp;&nbsp;[', 'chars');
  $rv .= gen_link($chat_url, $action_char) . ']';
  $rv .= '</div></div>';
  return $rv;
}

function gen_chat_win($chat_arr, $add_el = true) {
  $rv = '';
  foreach ($chat_arr as $msg) {
    $rv .= $msg . '<br><br>';
  }
  if ($add_el) $rv .= "\n";
  return $rv;
}
