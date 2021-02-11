<h2>FAQ: Introducing FameText</h2>

<p><u>June 1, 2021</u>, <i>by Todd Perry</i></p>

<p>At 12noon, <a href="http://tsuzy.com">TSUZY</a> expects to publish a video about the development of an open source software project that was originally described in the printed, summer 2020 version of <a href="http://thesuzy.com">TheSuzy.com</a> Show and <a href="http://datatextoracle.com">_______</a> in 2015 online:</p>

<p>Mirror: <a href="https://faqreport.com">faqreport.com</a></p>

<?php
$link_str = 'Back to Gems';
if (web_logged_in()) {
  $link_url = gen_url('search');
} else {
  $link_url = gen_url('news');
}
echo gen_p(gen_link($link_url, $link_str));
