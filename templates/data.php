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

if (isset($data['chestid'])) {

  $minid = max(1, $data['chestid'] - 3);
  $maxid = min(mod_max_chestid(), $data['chestid'] + 3);

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
  	  $searchurl = gen_url('search', 'Search');
      $searchurl .= gen_url_param('stxt', $allalpha);
      $linkstr = gen_link($searchurl, $tok, 'plain');
      if (strtolower($allalpha) == strtolower($data['tokstr'])) {
      	$linkstr = gen_u(gen_b($linkstr));
      }
      $outstr .=  $linkstr . ' ' . "\n";
    }
    echo gen_p($outstr);
  }

} else { ?>

<pre>
<b>::</b> Profile History <b>*<u>Library</u>*</b> TheDocs

<b>:: ::</b> TheBard <b>*<u>SuzyThe</b>*</u> BibleOS AncienT
</pre>

<p><a href="https://toddperry.medium.com/a-new-book-beginning-637719e9ba65">
https://toddperry.medium.com/a-new-book-beginning-637719e9ba65</a></p>

<p><a href="https://toddperry.medium.com/20-years-to-paradise-38caac2d9cb3">
https://toddperry.medium.com/20-years-to-paradise-38caac2d9cb3</a></p>

<p><a href="https://toddperry.medium.com/this-working-mans-fake-c6b96189ba2d">
https://toddperry.medium.com/this-working-mans-fake-c6b96189ba2d</a></p>

<p><a href="https://toddperry.medium.com/sharkinjury-1-01-ca7142a7c16c">
https://toddperry.medium.com/sharkinjury-1-01-ca7142a7c16c</a></p>

<?php } ?>