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

$titlestr = 'Hall of ' . strtoupper(APP_PREFIX);

$art_arr = mod_get_hall_art();
?>

<h2><?php echo $titlestr; ?></h2>
<div class="innerc">

<?php
$first2020 = true;
$css_class = 'page_heading';
foreach ($art_arr as $ar) {
  if (ctype_alpha($ar['datestr'][0]) && $first2020) {
    echo '</div><h3><i>Articles from 2020</i></h3>';
    echo '<div class="content">';
    $first2020 = false;
    $css_class = 'page_heading';
  }
  $rowstr = gen_b($ar['artid']);
  $dateurl = 'index.php?page=art&datestr=' . $ar['datestr'];
  $rowstr .= '. [' . gen_link($dateurl, $ar['datestr']) . '] ';
  $rowstr .= gen_link($ar['arturl'], $ar['arturl'], 'header');
  echo gen_p($rowstr, $css_class);
  $css_class = '';
  echo gen_p(gen_b('Summary: ') . $ar['sumstr']);
}
?>

</div>

<p class="footer_links">
<a href="http://suzybot.com">SUZBOT</a> <b>Suzybot</b><br>
<a href="http://fashiontext.com">fAtE</a> <b>FashionText</b><br>
<a href="http://sharkinjury.com">$1</a> <b>SharkInjury</b><br>
<a href="http://clichecourse.com"><b>::</b></a> <b>ClicheCourse</b>
</p>
