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

if (web_is_admin()) {
  echo gen_p(gen_h(2, gen_link(gen_url('admin'), 'AdminHQ', 'header')));
} else {
  $tempstr = 'Account' . ' | ';
  $tempstr .= gen_u('Archive') . ' | ';
  $tempstr .= gen_u('TheDocs');
  echo gen_p(gen_h(2, $tempstr));
}

if ($data['cmd'] == CHANGE_PASSWORD_CMD) {

  echo gen_p(gen_link(gen_url('settings'), 'Back to Account'));
  $elem_arr = array(); $add_el = true;
  $tempstr = gen_txt_input('oldpasstxt', '', LOGIN_COLS, '', $add_el);
  $elem_arr []= $tempstr . ' (Old Password)<br>';
  $tempstr = gen_txt_input('newpasstxt', '', LOGIN_COLS, '', $add_el);
  $elem_arr []= gen_span($tempstr . ' (New Password)<br>', 'nextline');
  $elem_arr []= gen_p(gen_input('submit', 'cmd', 'Change Password', $add_el));
  echo gen_form($elem_arr, gen_url('settings'));

} else if ($data['cmd'] == CUSTOMIZE_UI_CMD) {

  echo gen_p(gen_link(gen_url('settings'), 'Back to Account'));
  $elem_arr = array(); $add_el = true;
  $tempstr = gen_txt_input('numcolstxt', '', 5, '', $add_el);
  $elem_arr []= $tempstr . ' cols' . PADDING_STR;
  $tempstr = gen_txt_input('numrowstxt', '', 5, '', $add_el);
  $elem_arr []= $tempstr . ' rows<br>';
  $elem_arr []= gen_p(gen_input('submit', 'cmd', 'Make Changes', $add_el));
  echo gen_form($elem_arr, gen_url('settings'));

} else if ($data['cmd'] == ARCHIVE_GEMS_CMD) {

  echo gen_p(gen_link(gen_url('settings'), 'Back to Account'));
  echo 'TODO';

} else {

  $infostr = '(' . gen_i(fd(mod_get_int('lastchange'))) . ')<br>';
  $tempstr = gen_link(gen_url('settings', CHANGE_PASSWORD_CMD),
  	                  'Change Password') . PADDING_STR . $infostr;
  $infostr = '(' . mod_get_int('datarows');
  $infostr .= ', ' . mod_get_int('datacols') . ')<br>';
  $tempstr .= gen_link(gen_url('settings', CUSTOMIZE_UI_CMD),
  	                   'Customize the UI') . PADDING_STR . $infostr;
  $infostr = '(' . mod_get_gem_count() . ')';
  $tempstr .= gen_link(gen_url('settings', ARCHIVE_GEMS_CMD),
                       'Archive Gems') . PADDING_STR . $infostr;

  echo gen_p($tempstr);
}

$apptitle = $GLOBALS['APPTITLE'];
$flagarr = array(TOGGLE_CHAT_CMD => 'Expand the Chat with ' . $apptitle,
                 TOGGLE_TEXT_CMD => 'Accomodate multi-line searches',
                 TOGGLE_SPLASH_CMD => 'Use the Data tab as splash page',
                 TOGGLE_OPTION_CMD => 'Record book and author guesses',
                 TOGGLE_INVERTEDCS_CMD => 'Invert the color scheme');

echo gen_p(gen_h(3, 'Click each LINK to toggle each FLAG:'));
$flagstr = '';
foreach ($flagarr as $cmdstr => $link_text) {
  if ($flagstr != '') {
  	$flagstr .= '<br>';
  }
  if (web_get_flag(mod_flag_from_toggle($cmdstr))) {
  	$flagstr .= '[' . gen_b('ON') . ']&nbsp; ';
  } else {
  	$flagstr .= '[OFF] ';
  }
  $flagstr .= gen_link(gen_url('settings', $cmdstr), $link_text);
}

echo gen_p($flagstr);

echo gen_p(gen_h(3, 'Export My Gems (DUTY FREE!)'));

$tempstr = gen_link(gen_url('export'),
                     'Get My Export (JSON format)');
$gemco = gen_img('images/gemco.jpg', 'Icon of the California Coast');
echo gen_p($gemco . PADDING_STR . $tempstr);
