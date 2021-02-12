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

//$sub_header_str = gen_i('Profile');
$sub_header_str = gen_link(gen_url('profile'), 'Profile', 'header');
$temp_str = gen_i('Library');
//$temp_str = gen_link(gen_url('library'), 'Library', 'header');
$sub_header_str .= ' | ' . $temp_str . ' | ';
//$sub_header_str .= gen_i('Abstract');
$sub_header_str .= gen_link(gen_url('data'), 'Abstract', 'header');
echo gen_p(gen_h(2, $sub_header_str));

$heading_html = 'Is under construction.';

$gemco = gen_img('images/mini.jpg', 'Icon of the California Coast', 16);
echo gen_h(3, $gemco . PADDING_STR . 'Library');

$con_str = gen_p($heading_html, 'page_heading');

$link_str = 'Back to Gems';
$con_str .= gen_p(gen_link(gen_url('search'), $link_str));
echo gen_div($con_str, 'content');
