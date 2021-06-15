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

$GLOBALS['DBHOST'] = 'mysql.questiontask.com';
$GLOBALS['DBUSER'] = 'fate_db_user';
$GLOBALS['DBPASS'] = 'fatetext_123';
$GLOBALS['DBNAME'] = 'questiontask_fatetext_db';
$GLOBALS['LOGTABLE'] = 'log1';


$GLOBALS['LOGLEVEL'] = 0;
$GLOBALS['DBVERBOSE'] = true;
$GLOBALS['SHOWERRMSG'] = true;
$GLOBALS['SHOWEXTRACE'] = false;

$GLOBALS['COPYRIGHT_HOLDER'] = 'Christian Pecaut';
$GLOBALS['COPYRIGHT_URL'] = 'https://twitter.com/christianpecaut';

$GLOBALS['APPTITLE'] = 'FateText';
$GLOBALS['APPIDENT'] = 'FaTe';
$GLOBALS['FAMEIDENT'] = 'TheSuzy';
$GLOBALS['FAMEURL'] = 'http://thesuzy.com';

$GLOBALS['AGREEONLOGIN'] = true;

$GLOBALS['SLOGFILE'] = $GLOBALS['SITE_URL'].'/fatelogs/searchlog.txt';
$GLOBALS['LOGFILE'] = $GLOBALS['SITE_URL'].'/fatelogs/errorlog.txt';


$GLOBALS['FATEPATH'] = $GLOBALS['SITE_URL'];

$GLOBALS['FILESDIR'] = $GLOBALS['SITE_URL'];

$GLOBALS['SESSIONSAVEPATH'] = $GLOBALS['SITE_URL'];

$GLOBALS['NOCOOKIES'] = true;
$GLOBALS['ISPROD'] = false;

$GLOBALS['PARAMEX'] = true;
$GLOBALS['ASSERTEX'] = true;
$GLOBALS['CATCHEX'] = false;
