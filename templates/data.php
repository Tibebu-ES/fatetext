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

echo gen_search_form();

//$sub_header_str .= gen_i('Profile');
$sub_header_str = gen_link(gen_url('profile'), 'Profile', 'header');
//$temp_str = gen_i('Library');
$temp_str = gen_link(gen_url('library'), 'Library', 'header');
$sub_header_str .= ' | ' . $temp_str . ' | ';
$sub_header_str .= gen_i('Abstract');
//$sub_header_str .= gen_link(gen_url('data'), 'Abstract', 'header');
echo gen_p(gen_h(2, $sub_header_str));

if (isset($data['chestid'])) {

  $next_prev_str = '';
  $chest_id = $data['chestid'];
  $data_url = gen_url('data', 'chest');
  $data_url .= gen_url_param('chestid', $chest_id - NUM_CON_ROWS);
  $data_url .= gen_url_param('tokstr', $data['tokstr']);
  //TODO bounds checking
  if ($chest_id - (2 * NUM_CHAT_ROWS) > 0) {
    $next_prev_str .= gen_link($data_url, '&lt;prev') . ' :: ';
  }
  $data_url = gen_url('data', 'chest');
  $data_url .= gen_url_param('chestid', $chest_id + NUM_CON_ROWS);
  $data_url .= gen_url_param('tokstr', $data['tokstr']);
  $next_prev_str .= gen_link($data_url, 'next&gt;');
  echo gen_p($next_prev_str);

  $minid = max(1, $data['chestid'] - NUM_CON_ROWS);
  $maxid = min(mod_max_chestid(), $data['chestid'] + NUM_CON_ROWS);

  for ($i = $minid; $i < $maxid; $i++) {
    $chestdata = mod_load_chest($i);
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
  	  $searchurl = gen_url('search', 'Create');
      $searchurl .= gen_url_param('stxt', $allalpha);
      $linkstr = gen_link($searchurl, $tok, 'plain');
      if (strtolower($allalpha) == strtolower($data['tokstr'])) {
      	$linkstr = gen_u(gen_b($linkstr));
      }
      $outstr .=  $linkstr . ' ' . "\n";
    }
    if ($i == $data['chestid']) {
      echo gen_p('-------');
    }
    echo gen_p($outstr);
    if ($i == $data['chestid']) {
      echo gen_p('-------');
    }

  }

} else {

  $urlstr = 'http://suzybot.com';
  $tempstr = gen_link($urlstr, $urlstr, 'header', false, true) . '<br>';
  $urlstr = 'http://fashiontext.com';
  $tempstr .= gen_link($urlstr, $urlstr, 'header', false, true) . '<br>';
  $urlstr = 'http://sharkinjury.com';
  $tempstr .= gen_link($urlstr, $urlstr, 'header', false, true) . '<br>';
  $urlstr = 'http://clichecourse.com';
  $tempstr .= gen_link($urlstr, $urlstr, 'header', false, true) . '<br>';
  echo gen_p($tempstr);

  $ldpt = $GLOBALS['FATEPATH'] . '/templates/';
  echo util_show_template($ldpt . 'news.php', $data);
  echo util_show_template($ldpt . 'about.php', $data);

}
