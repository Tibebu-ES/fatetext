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

$art_arr = mod_get_hall_art();
$cat_arr = mod_get_hall_categories();
?>

<h2>Hall of Fame</h2>
<p><img src="images/water.png"></p>

<?php
$content = '';
foreach ($cat_arr as $cat) {
  $art_url = gen_url('art', $cat);
  $art_link = gen_link($art_url, $cat);
  $content .= gen_h(3, 'Art Category: ' . $art_link);
  $css_class = 'page_heading';
  $div_str = '';

  foreach ($art_arr as $ar) {
    if ($ar['category'] == $cat) {
      $rowstr = '';
      $rowstr .= gen_link($ar['arturl'], $ar['arturl'], 'header', false);
      $date_url = gen_url('date', $ar['datestr']);
      $rowstr .= PADDING_STR . gen_link($date_url, $ar['datestr']);
      $rowstr .= gen_p(gen_i('Summary: ') . $ar['sumstr']);

      $div_str .= gen_p($rowstr, $css_class);
      $css_class = '';
    }
  }

  $content .= gen_div($div_str, 'innerc');
}

echo gen_div($content, 'innerc');
?>

<p class="footer_links">
<a target="_blank" href="http://suzybot.com">SUZBOT</a> <b>Suzybot</b><br>
<a target="_blank" href="http://fashiontext.com">fAtE</a> <b>FashionText</b><br>
<a target="_blank" href="http://sharkinjury.com">$1</a> <b>SharkInjury</b><br>
<a target="_blank" href="http://clichecourse.com"><b>::</b></a> <b>ClicheCourse</b>
</p>
