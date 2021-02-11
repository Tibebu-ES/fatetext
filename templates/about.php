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

<p><i>"decentralized open source community online"</i></p>

<p>FATEtext (aka. FATE) is an open source (<a href="https://en.wikipedia.org/wiki/MIT_License">MIT License</a>) reference implementation for <a href="http://faqreport.com">a novel approach to creating convergence online</a> that does not necessarily rely on the 1990s era liability shield for US Internet companies known as, "Section 230."</p>

<p>This latest version of FaTe (v0.9) became available online, starting Feb. 9, 2021 at <a href="http://fametext.com">fametext.com</a>.</p>

<p><a href="https://github.com/tperry256/fametext">v0.9's source code</a> is available upon request as a ZIP file (22 MB), and the first iteration was originally released by <a href="http://tperry256.com">Todd Perry</a> on January 13, 2021 as a <a href="https://github.com/tperry256/fatetext">separate GIT repository on Github</a>.</p>

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
