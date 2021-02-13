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

function mod_get_hall_categories() {
  $sql = 'SELECT category FROM hallart GROUP BY category';
  $rs = queryf_all($sql);
  $rv = array();
  if (count($rs) == 0) {
    $rv []= 'American';
  } else {
    foreach ($rs as $cat_arr) {
      $rv []= $cat_arr['category'];
    }
  }
  return $rv;
}

function mod_get_hall_art() {
  $sql = 'SELECT * FROM hallart ORDER BY artid DESC';
  $rs = queryf_all($sql);
  if (count($rs) == 0) {
    $rs = array(array('artid' => '1',
                      'category' => 'American',
                      'datestr' => '01_01_21',
                      'arturl' => 'http://thesuzy.com',
                      'sumstr' => 'TheSuzy Trilogy by Todd Perry'));
  }
  return $rs;
}

function mod_max_tokid() {
  $sql = 'SELECT MAX(tokid) as maxid FROM toks';
  $rs = queryf_one($sql);
  return $rs['maxid'];
}

function mod_max_chestid() {
  $sql = 'SELECT MAX(chestid) as maxid FROM chests';
  $rs = queryf_one($sql);
  return $rs['maxid'];
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

function mod_get_book_title($book_id) {
  $sql = 'SELECT titlestr FROM books WHERE bookid = %d';
  $rs = queryf_one($sql, $book_id);
  //TODO error handling
  return $rs['titlestr'];
}



function mod_load_chest($chestid) {
  $sql = 'SELECT * FROM chests WHERE chestid = %d';
  $rv = queryf_one($sql, $chestid);
  return $rv;
}

function mod_get_user_gems($userid, $maxgems = 5) {
  $sql = 'SELECT gems.tokid, datecreated, tokstr, gemid,';
  $sql .= ' wordcount, charcount, stepint FROM gems, toks';
  $sql .= ' WHERE userid = %d AND toks.tokid = gems.tokid';
  $sql .= ' ORDER BY lastloaded DESC LIMIT 100';
  $rs = queryf_all($sql, $userid, $maxgems);

  $rv = array();
  foreach ($rs as $row) {
    $sql = 'SELECT * from steps WHERE gemid = %d AND whichint = 1';
    $rs2 = queryf_one($sql, $row['gemid']);
    if (isset($rs2)) {
      if (strlen($rs2['stepstr']) > 4) {
        $rv []= $row;
      }
    }
  }
  return $rv;
}

function mod_log_search($logtxt) {
  $logtxt = '[' . fd(time()) . ']' . $logtxt . "\n";
  file_put_contents($GLOBALS['SLOGFILE'], $logtxt, FILE_APPEND);
}

function mod_flag_from_toggle($intoggle) {
  switch ($intoggle) {
   case TOGGLE_SPLASH_CMD: return FATE_SPLASH_FLAG;
   case TOGGLE_CHAT_CMD: return CHAT_OPEN_FLAG;
   case TOGGLE_TEXT_CMD: return TEXT_AREA_FLAG;
   case TOGGLE_OPTION_CMD: return AUTHORTEXT_FLAG;
   case TOGGLE_INVERTEDCS_CMD: return INVERTEDCS_FLAG;
   case TOGGLE_TOOLTIP_CMD: return HIDETOOLTIP_FLAG;
  }
  util_except('attempted to reference an unknown flag: '
              . $intoggle);
}
