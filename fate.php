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

$GLOBALS['START_TIME'] = microtime(true);

include('lib/db.php');
include('lib/web.php');
include('lib/net.php');
include('lib/mvc.php');
include('lib/util.php');
include('lib/html.php');
include('lib/debug.php');

include('app/user.php');
include('app/model.php');
include('app/view.php');
include('app/control.php');

define('SYSUSER', 1);
define('VOIDOID', 1);

define('FATE_TYPE_VOID', 1);
define('FATE_TYPE_USER', 2);
define('FATE_TYPE_BOOK', 3);
define('FATE_TYPE_TOK', 4);
define('FATE_TYPE_GEM', 5);
define('FATE_TYPE_STEP', 6);
define('FATE_TYPE_DIFF', 7);
define('FATE_TYPE_CONCEPT', 8);
define('FATE_TYPE_COMMENT', 9);
define('FATE_TYPE_SEARCH', 10);
define('FATE_TYPE_RESULT', 11);

define('OSTITLE', 'fatetext');
define('COPYRIGHT_HOLDER', 'TSUZY');
define('COPYRIGHT_URL', 'http://tsuzy.com');

define('USER_SEARCH_ROWS', 'searchrows');
define('USER_SEARCH_COLS', 'searchcols');

define('CHAT_OPEN_FLAG', 1); //'chatopen');
define('TEXT_AREA_FLAG', 2); //'textarea');
define('FATE_SPLASH_FLAG', 4); //'fatesplash');
define('AGREE_TOS_FLAG', 8); //'agreetos');

define('TOGGLE_SPLASH_CMD', 'tosplash');
define('TOGGLE_CHAT_CMD', 'tochat');
define('TOGGLE_TEXT_CMD', 'totext');
define('LOGOUT_CMD', 'logout');

define('FAME_IDENT', 'TheSuzy');
define('FAME_URL', 'http://thesuzy.com');
define('SEARCH_PLACEHOLDER', 'Empty Search = The Oracular');
define('SPACER_STR', '::');
define('SEARCH_COLS', 30);
define('LOGIN_COLS', 18);

define('TEMPLATE_CONTENT', '__content');
define('TEMPLATE_PAGE', 'page');
define('TEMPLATE_CMD', 'cmd');
define('TEMPLATE_MSG', '__msg');

define('LLRUN', 1);
define('LLWORK', 2);
define('LLDEBUG', 4);

define('PADDING_STR', '&nbsp;&nbsp;');
define('DB_BULK_BLOCK_SIZE', 200);
