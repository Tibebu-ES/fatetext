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

$link_str = 'Back to Search';
$search_str = gen_link(gen_url('search'), $link_str);
$username = web_get_user_name(web_get_user());
$user_link = gen_link(gen_url('home'), $username);
$numcoins = 2; //TODO
$numcoin_str = 'User "' . $user_link . '" has ';
$numcoin_str .= gen_b($numcoins) . ' storycoins.'
?>

<h2>StoryCoin( <?php echo $user_link; ?> )</h2>
<div class="innerc">
<?php
echo gen_p($search_str, 'page_heading');
echo gen_p($numcoin_str, 'lastline');
?>
</div>
