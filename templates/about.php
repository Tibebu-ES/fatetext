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
SOFTWARE. */ ?>

<h2>About FameText</h2>

<p><i>Decentralized open source community</i> (Version <b>0.93</b>)</p>

<p><a href="http://fametext.com">FameText</a> is an open source (<a href="https://en.wikipedia.org/wiki/MIT_License">MIT License</a>) reference implementation for <a href="http://faqreport.com">a novel approach to creating convergence</a>.</p>

<p>FaTe's source code (v0.93) was originally shared by <a href="http://tperry256.com">Todd Perry</a> on January 13, 2021 as a <a href="https://github.com/tperry256/fatetext">GIT repository on Github</a>.</p>

<p>Please find the following videos for more info:</p>

<p><b>Taking fametext.com for a test drive</b></p>

<p><u>TODO</u></p>

<p><b>Creating an account at FameText.com</b></p>

<p><u>TODO</u></p>

<p><b>Hosting an instance of the FaTe code</b></p>

<p><u>TODO</u></p>

<p><b>Developing more and more new features</b></p>

<p><u>TODO</u></p>

<?php
$link_str = 'Back to Gems';
if (web_logged_in()) {
  $link_url = gen_url('search');
} else {
  $link_url = gen_url('news');
}
echo gen_p(gen_link($link_url, $link_str));
