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

$curuser = web_get_user();
$safe_text = '';
$safe_custom = '';
$incat = '';
$stxt = '';

if (!isset($data['category'])) {
  if (isset($_SESSION['temp']['category'])) {
    $data['category'] = $_SESSION['temp']['category'];
  } else {
    $data['category'] = DEFAULT_CATEGORY;
  }
}

if (!isset($data['stxt'])) {
  if (isset($_SESSION['temp']['stxt'])) {
    $data['stxt'] = $_SESSION['temp']['stxt'];
  } else {
    $data['stxt'] = '';
  }
}

if (!isset($data['customtxt'])) {
  if (isset($_SESSION['temp']['customtxt'])) {
    $data['customtxt'] = $_SESSION['temp']['customtxt'];
  } else {
    $data['customtxt'] = '';
  }
}

$incat = $data['category'];

if (isset($data['stxt'])) {
  $stxt = $data['stxt'];
  $safe_text = htmlentities($data['stxt']);
}

if (isset($data['customtxt'])) {
  $custom_txt = $data['customtxt'];
  $safe_custom = htmlentities($data['customtxt']);
}

$textarea = web_get_user_flag($curuser, TEXT_AREA_FLAG);
echo gen_search_form($safe_text, $safe_custom, $textarea,
                     $incat, true, false);

if ($incat == 'CUSTOM') {

  echo gen_p('The custom category was: ' . $safe_custom);

} else if ($stxt == '') {

  $guessdata = null;
  $lastgemid = mod_get_user_lastgem($curuser);

  if ($lastgemid == null) {
    echo gen_p('Chose a category and then click on the
                <br>search button in order to
                <br>generate a gem!');
  } else {
    $gemdata = mod_load_gem($lastgemid);
    $tempstr = gen_b('GEM #' . gen_i($gemdata['gemid']));
    $tempstr .= PADDING_STR . gen_b('(') . 'Created ' . gen_b('at ');
    $tempstr .= gen_i(fd($gemdata['datecreated']));
    if ($gemdata['stepint'] > 0) {
      $guessdata = mod_load_step($gemdata['gemid'], 1);
      $duration = $guessdata['datecreated'] - $gemdata['datecreated'];
      $tempstr .= PADDING_STR . gen_b('\'in ') . $duration . ' secs';
    }
    echo gen_p(gen_u($tempstr . gen_b(')') . PADDING_STR));

    if ($gemdata['stepint'] == 0) {

      $tempstr = 'Guess the blanked out word in the following sentence:';
      $tempstr = gen_p(gen_b('Step 1: ') . $tempstr);
      $tempstr .= gen_div($gemdata['chester'], 'gem_text');
      $tempstr .= gen_p(gen_gem_guess_form($gemdata));
      echo gen_div($tempstr, 'gem_step');

    } else {

      util_assert(isset($guessdata));
      $tempstr = gen_b('Step 1: ') . gen_i('your guess') . ': ';
      //TODO store this as a flag
      if (strtolower($guessdata['stepstr']) == strtolower($gemdata['tokstr'])) {
        $coin_url = gen_url('coin');
        $tempstr .= gen_link($coin_url, 'correctly') . '! (ANSWER: ';
        $tempstr .= gen_b($gemdata['tokstr']) . ')';
      } else {
        $tempstr .= gen_b(gen_u($guessdata['stepstr']));
        $tempstr .= ' (ANSWER: ' . gen_b($gemdata['tokstr']) . ')';
      }

      $ttip_flag = web_get_user_flag(web_get_user(), HIDETOOLTIP_FLAG);
      if ($gemdata['stepint'] == 1) {
        $sent_str = $gemdata['chester'];
        if (!$ttip_flag) {
          $sent_str = gen_link(gen_url('data'), $gemdata['chester'], 'plain');
        }
        $tempstr = gen_p($tempstr) . gen_div($sent_str, 'gem_text');
        if ($ttip_flag) {
          $link_str = 'Click here for more information about this three step process';
          $help_str = '(' . gen_link(gen_url('library'), $link_str) . ')';
          $tempstr .= gen_div(gen_p($help_str), 'gem_step');
        }
      } else {
        //TODO show search result view
        $dataurl = gen_url('data', 'chest');
        $dataurl .= gen_url_param('chestid', $gemdata['chestid']);
        $dataurl .= gen_url_param('tokstr', $gemdata['tokstr']);
        $sent_str = $gemdata['chester'];
        if (!$ttip_flag) {
          $sent_str = gen_link($dataurl, $gemdata['chester'], 'plain');
        }
        $tempstr = gen_p($tempstr) . gen_div($sent_str, 'gem_text');
        if ($ttip_flag) {
          $link_str = 'Click here to view this sentence and its keyword in context';
          $help_str = '---&gt;' . PADDING_STR . gen_link($dataurl, $link_str);
          $tempstr .= gen_div(gen_p($help_str), 'gem_step');
        }
      }
      echo gen_div($tempstr, 'gem_step');

      if ($gemdata['stepint'] == 1) {

        //TODO load these automatically from the DB
        $auth_arr = array('Todd Perry' => 'Todd Perry',
                          'Conri Stonewall' => 'Conri Stonewall',
                          'BIBLICAL' => 'BIBLICAL');
        $text_arr = array(4 => 'The Bible',
                          1 => 'Suzy\'s Memoir',
                          2 => 'TheSuzy.com Show',
                          3 => 'TheSuzy Memoirs');

        $tempstr = 'Now, ask a question about that same sentence:';
        $tempstr = gen_p(gen_b('Step 2: ') . $tempstr);

        $toggleurl = gen_url('search', TOGGLE_OPTION_CMD);
        $atf = web_get_user_flag(web_get_user(), AUTHORTEXT_FLAG);
        if ($atf) {
          $optstr = '(' . gen_u(gen_link($toggleurl, 'OPTIONAL', 'header'));
          $optstr .= ') ' . gen_i('Guess the AUTHOR') . ': ';
          $optstr .= gen_select_input('authguess', $auth_arr) . '<br>';
          $linestr = '&nbsp; ' . gen_i('and the TEXT') . ': ';
          $linestr .= gen_select_input('textguess', $text_arr);
          $tempstr .= gen_p($optstr . gen_span($linestr, 'nextline'));
          //NOTE: the optional text and auth guess are in $tempstr
          $tempstr = gen_gem_quest_form($gemdata, $tempstr,
                                        $data['one_line_chk']);
        } else {
          $togglestr = 'O&nbsp;<br>P&nbsp;<br>T&nbsp;<br>';
          $leftcol = gen_div(gen_link($toggleurl, $togglestr, 'plain'),
                             'gem_step');
          $rightcol = gen_gem_quest_form($gemdata, '', $data['one_line_chk']);
          $tempstr .= gen_two_cols($leftcol, $rightcol);
        }
        echo gen_div($tempstr, 'gem_step');

      } else {

        $questdata = mod_load_step($gemdata['gemid'], 2);
        $tempstr = 'You asked the following question ' . gen_b('at ');
        $tempstr .= gen_i(fd($questdata['datecreated'])) . gen_b(':');
        $tempstr = gen_p(gen_b('Step 2: ') . $tempstr);

        $correct_book_id = mod_get_gem_book($gemdata['gemid']);
        $correct_text = mod_get_book_title($correct_book_id);
        $text_coin = 0;
        if ($correct_book_id == $gemdata['bookguess']) {
          $text_coin = 1;
        }

        $correct_auth = mod_get_gem_auth($gemdata['gemid']);
        $auth_coin = 0;
        if ($correct_auth == $gemdata['authstr']) {
          $auth_coin = 1;
        }

        $optstr = PADDING_STR . gen_i('AUTHOR guess') . ': ';
        if ($auth_coin) {
          $optstr .= gen_link(gen_url('coin'), 'correct!');        
        } else {
          $optstr .= $gemdata['authstr'];
        }
        $optstr .= ' (ANSWER: ' . $correct_auth . ')<br>';

        $linestr = PADDING_STR . gen_i('TEXT guess') . ': ';
        if ($text_coin) {
          $linestr .= gen_link(gen_url('coin'), 'correct!');        
        } else {
          $linestr .= $gemdata['bookstr'];
        }
        $linestr .= ' (ANSWER: ' . $correct_text . ')';        

        $tempstr .= gen_p($optstr . gen_span($linestr, 'nextline'));
        $tempstr .= gen_div($questdata['stepstr'], 'quest_text');
        echo gen_div($tempstr, 'gem_step');

        $stepvalue = '';
        $lastsaved = 0;
        if ($gemdata['stepint'] > 2) {
          $ansdata = mod_load_step($gemdata['gemid'], 3);
          $stepvalue = $ansdata['stepstr'];
          $lastsaved = $ansdata['datecreated'];
        }

        $tempstr = 'Please record the best answer to your question';
        $tempstr = gen_p(gen_b('Step 3: ') . $tempstr . gen_b(':'));
        $tempstr .= gen_gem_answer_form($gemdata, $stepvalue, $lastsaved);
        echo gen_div($tempstr, 'gem_step');

      }
    }
  }

} else { //$stxt != ''

  $stoks = explode(' ', $stxt);
  $chestidarr = array();
  foreach ($stoks as $tok) {
    $allalpha = '';
    $toklen = strlen($tok);
    for ($j = 0; $j < $toklen; $j++) {
      if (ctype_alpha($tok[$j])) {
        $allalpha .= $tok[$j];
      }
    }

    //TODO select the book based on category
    $sql = 'SELECT chestidstr, bookid FROM toks WHERE tokstr = %s';
    $rs_arr = queryf_all($sql, $allalpha);
    foreach ($rs_arr as $rs) {
      $tempidarr = explode(' ', $rs['chestidstr']);
      foreach ($tempidarr as $chestid) {
        if (!isset($chestidarr[$chestid])) {
          $chestidarr[$chestid] = 1;
        } else {
          $chestidarr[$chestid]++;
        }
      }
    }
  }

  $chest_i = 0;
  foreach ($chestidarr as $chestid => $hit_count) {
    $chestdata = mod_load_chest($chestid);
    $outstr = '';
    $toks = explode(' ', $chestdata['datastr']);
    foreach ($toks as $tok) {
      $allalpha = '';
      $toklen = strlen($tok);
      for ($j = 0; $j < $toklen; $j++) {
        if (ctype_alpha($tok[$j])) {
          $allalpha .= $tok[$j];
        }
      }
      $searchurl = gen_url('search', 'Search');
      $searchurl .= gen_url_param('stxt', $allalpha);
      $linkstr = gen_link($searchurl, $tok, 'plain');
      foreach ($stoks as $stok) {
        if (strtolower($allalpha) == strtolower($stok)) {
          $linkstr = gen_u(gen_b($linkstr));
          break;
        }
      }
      $outstr .=  $linkstr . ' ' . "\n";
    }
    $chest_i++;
    $result_str = ' from ' . mod_get_book_title($chestdata['bookid']);
    $result_str .= ' (hits = ' . $hit_count;
    echo gen_p(gen_b('Result #' . $chest_i) . ':' . $result_str . ')');
    echo gen_p($outstr);

    if ($chest_i >= 100) {
      break;
    }
  }

  mod_log_search($stxt);
} // end if $stxt != ''

?>
