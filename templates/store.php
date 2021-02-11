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

//$sub_header_str .= gen_i('Coins');
$sub_header_str = gen_link(gen_url('coin'), 'Coins', 'header');
$temp_str = gen_i('Store');
//$temp_str = gen_link(gen_url('store'), 'Store', 'header');
$sub_header_str .= ' | ' . $temp_str . ' | ';
//$sub_header_str .= gen_i('Space');
$sub_header_str .= gen_link(gen_url('home'), 'Space', 'header');
echo gen_p(gen_h(2, $sub_header_str));

echo gen_h(3, 'The Shops at ' . $GLOBALS['APPTITLE']);

$con_str = gen_p('Available in June!', 'page_heading');

$link_str = 'Back to Gems';
$con_str .= gen_p(gen_link(gen_url('search'), $link_str));
echo gen_div($con_str, 'content');
