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

function mod_get_hall_art() {
  $sql = 'SELECT * FROM hallart ORDER BY artid DESC';
  $rs = queryf_all($sql);
  if (count($rs) == 0) {
    $rs = array(array('artid' => '0', 'datestr' => '01_01_21',
                      'arturl' => 'http://thesuzy.com',
                      'sumstr' => 'TheSuzy Trilogy by Todd Perry'));
  }
  return $rs;
}

function mod_generate_gem($userid, $stxt, $category) {
  $sql = 'SELECT MAX(tokid) as maxid FROM toks';
  $rs = queryf_one($sql);
  $maxid = $rs['maxid'];

  $randtokid = rand(0, $maxid - 1);
  $sql = 'SELECT chestidstr FROM toks WHERE tokid = %d';
  $rs = queryf_one($sql, $randtokid);
  $chestidstr = $rs['chestidstr'];

  $chestidarr = explode(',', $chestidstr);
  $chestcount = count($chestidarr);
  $randindex = rand(0, $chestcount - 1);
  $randchestid = $chestidarr[$randindex];

  $sql = 'SELECT datastr FROM chests WHERE chestid = %d';
  $rs = queryf_one($sql, $randchestid);
  $datastr = $rs['datastr'];

  $wordcount = count(explode(' ', $datastr));
  $charcount = strlen($datastr) - $wordcount;

  $nowtime = time();
  $sql = 'INSERT INTO gems (userid, chestid, tokid, stepint,';
  $sql .= ' datecreated, wordcount, charcount, lastloaded)';
  $sql .= ' VALUES (%d, %d, %d, %d, %d, %d, %d, %d)';
  queryf($sql, $userid, $randchestid, $randtokid, 0,
         $nowtime, $wordcount, $charcount, $nowtime);
  return last_insert_id();
}

function mod_update_gem_step($gemid, $newstep) {
  $sql = 'UPDATE gems SET stepint = %d';
  $sql .= ' WHERE gemid = %d';
  queryf($sql, $newstep, $gemid);
}

function mod_load_step($gemid, $whichint) {
  $sql = 'SELECT stepstr, datecreated FROM steps';
  $sql .= ' WHERE gemid = %d';
  $sql .= ' AND whichint = %d';
  $rs = queryf_one($sql, $gemid, $whichint);
  return $rs;
}

function mod_record_step($gemid, $guesstxt, $whichint) {
  $nowtime = time();
  $sql = 'DELETE FROM steps WHERE gemid = %d AND whichint = %d';
  queryf($sql, $gemid, $whichint);

  $sql = 'INSERT INTO steps (gemid, stepstr, whichint, datecreated)';
  $sql .= ' VALUES (%d, %s, %d, %d)';
  queryf($sql, $gemid, $guesstxt, $whichint, $nowtime);
}

function mod_load_gem($gemid) {
  $sql = 'SELECT * FROM gems WHERE gemid = %d';
  $rv = queryf_one($sql, $gemid);

  $sql = 'SELECT tokstr FROM toks WHERE tokid = %d';
  $rs = queryf_one($sql, $rv['tokid']);
  $rv['tokstr'] = $rs['tokstr'];

  $sql = 'SELECT datastr FROM chests WHERE chestid = %d';
  $rs = queryf_one($sql, $rv['chestid']);
  $rv['datastr'] = $rs['datastr'];

  $rv['chester'] = preg_replace('/' . $rv['tokstr'] . '/i',
                                '_______', $rv['datastr']);
  return $rv;
}

function mod_get_user_gems($userid, $maxgems = 5) {
  $sql = 'SELECT gems.tokid, datecreated, tokstr, gemid,';
  $sql .= ' wordcount, charcount, stepint FROM gems, toks';
  $sql .= ' WHERE userid = %d AND toks.tokid = gems.tokid';
  $sql .= ' ORDER BY lastloaded DESC LIMIT %d';
  $rs = queryf_all($sql, $userid, $maxgems);
  return $rs;
}

function mod_update_gem_lastloaded($gemid) {
  $nowtime = time();
  $sql = 'UPDATE gems SET lastloaded = %d';
  $sql .= ' WHERE gemid = %d';
  queryf($sql, $nowtime, $gemid);
}

function mod_log_search($logtxt) {
  $logtxt = '[' . fd(time()) . ']' . $logtxt . "\n";
  file_put_contents($GLOBALS['SLOGFILE'], $logtxt, FILE_APPEND);
}
