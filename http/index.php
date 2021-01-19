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

include('serverconfig.php');
include($GLOBALS['FATEPATH'] . '/fate.php');

//set $data[TEMPLATE_PAGE] = 'home' by default
fl(); $data = web_init_data('home');
util_log('debug', 'web_init_data() done', LLDEBUG);

try {

  con_do_cmd($data);

  $ds = 'con_do_cmd(' . print_r($data, true) . ')';
  util_log('debug', 'finished: ' . $ds, LLDEBUG);

  net_log_user_and_session_info();

  echo util_show_page($data);

  $ds = 'con_show_page(' . print_r($data, true) . ')';
  util_log('debug', 'finished: ' . $ds, LLDEBUG);

} catch (Exception $ex) {

  util_log('Uncaught exception', $ex->getMessage());
  include('error.php');

} //end try

db_make_log_entry($data);
fl(); print_log();
?>
