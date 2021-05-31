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
    echo gen_p("FULL TEXT DATA :");
   //view full text
    //get all chestda that belongs to the book which is the one $data['chestid'] belongs.
    $bookId = mod_get_book($data['chestid']);
    $chests_id = mod_load_all_chest_in_a_book($bookId);
    $all_outstr = '';
    foreach ( $chests_id as  $chest_id){
    $chestdata = mod_load_chest($chest_id);
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
    if ($chest_id == $data['chestid']) {
      //echo gen_p('-------');
        $all_outstr .= gen_p('-------');
    }
        //echo gen_p($outstr);
        $all_outstr .= gen_p($outstr);
    if ($chest_id == $data['chestid']) {
        //echo gen_p('-------');
        $all_outstr .= gen_p('-------');
    }

  }
    //print full text data in a div
    echo gen_div( $all_outstr, 'full_text_viewer');




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
