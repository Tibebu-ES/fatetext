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

include('app/gem.php');
include('app/user.php');
include('app/model.php');
include('app/view.php');
include('app/control.php');

define('DEFAULT_CATEGORY', 'FATE');
define('NUM_CHAT_ROWS', 10);

define('COPYRIGHT_HOLDER', 'TSUZY');
define('COPYRIGHT_URL', 'http://tsuzy.com');

define('CHAT_OPEN_FLAG', 1);
define('TEXT_AREA_FLAG', 2);
define('FATE_SPLASH_FLAG', 4);
define('AGREE_TOS_FLAG', 8);
define('AUTHORTEXT_FLAG', 16);
define('INVERTEDCS_FLAG', 32);
define('HIDETOOLTIP_FLAG', 64);

define('TOGGLE_SPLASH_CMD', 'tosplash');
define('TOGGLE_CHAT_CMD', 'tochat');
define('TOGGLE_TEXT_CMD', 'totext');
define('TOGGLE_OPTION_CMD', 'tooption');
define('TOGGLE_INVERTEDCS_CMD', 'toinvcs');
define('TOGGLE_TOOLTIP_CMD', 'tottip');

define('LOGOUT_CMD', 'logout');
define('CHANGE_PASSWORD_CMD', 'changepass');
define('CUSTOMIZE_UI_CMD', 'customizeui');
define('ARCHIVE_GEMS_CMD', 'archivegems');

define('FAME_IDENT', 'TheSuzy');
define('FAME_URL', 'http://thesuzy.com');
define('SEARCH_PLACEHOLDER', '&lt;Search for Words or Gems&gt;');
define('CUSTOM_PLACEHOLDER', '&lt;Custom Search Category&gt;');
define('SPACER_STR', '::');

define('CUSTOM_COLS', 25);
define('SEARCH_COLS', 35);
define('SEARCH_AREA_COLS', 47);
define('LOGIN_COLS', 18);
define('GUESS_COLS', 25);
define('QUESTION_ROWS', 3);
define('ANSWER_COLS', 50);
define('ANSWER_ROWS', 5);

define('TEMPLATE_CONTENT', '__content');
define('TEMPLATE_PAGE', 'page');
define('TEMPLATE_CMD', 'cmd');
define('TEMPLATE_MSG', '__msg');

define('LLRUN', 1);
define('LLWORK', 2);
define('LLDEBUG', 4);

define('PADDING_STR', '&nbsp;&nbsp;');
define('DB_BULK_BLOCK_SIZE', 200);
