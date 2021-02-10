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

function user_get_default_rows($user_id) {
  $sql = 'SELECT datarows FROM users WHERE userid = %d';
  $rs = queryf_one($sql, $user_id);
  return $rs['datarows'];
}

function user_get_current_rows($user_id) {
  $gem_id = mod_get_user_lastgem($user_id);
  if ($gem_id === null) {
    return 0;
  }
  $sql = 'SELECT ansrows FROM gems WHERE gemid = %d';
  $rs = queryf_one($sql, $gem_id);
  return $rs['ansrows'];
}

function user_set_default_rows($user_id, $num_rows) {
  $sql = 'UPDATE users SET datarows = %d WHERE userid = %d';
  queryf($sql, $num_rows, $user_id);
}

function user_set_current_rows($user_id, $num_rows) {
  $gem_id = mod_get_user_lastgem($user_id);
  if ($gem_id !== null) {
    $sql = 'UPDATE gems SET ansrows = %d WHERE gemid = %d';
    queryf($sql, $num_rows, $gem_id);
  }
}

function mod_get_user_coins($userid) {
  return mod_get_user_int($userid, 'storycoins');
}

function mod_increment_user_coins($userid, $incr_amount = 1) {
  $coins = mod_get_user_coins($userid) + $incr_amount;
  mod_update_user_int($userid, 'storycoins', $coins);
}

function mod_update_user_lastgem($userid, $gemid) {
  mod_update_user_int($userid, 'lastgem', $gemid);
}

//returns null if there is no gem
function mod_get_user_lastgem($userid) {
  $rv = mod_get_user_int($userid, 'lastgem');
  if ($rv == 0) return null;
  return $rv;
}

function mod_get_gem_count() {
  $userid = web_get_user();
  $sql = 'SELECT COUNT(gemid) as gemcount FROM gems WHERE userid = %d';
  $rs = queryf_one($sql, $userid);
  return $rs['gemcount'];
}

function mod_get_int($intname) {
  return mod_get_user_int(web_get_user(), $intname);
}

function mod_get_user_int($userid, $intname) {
  $sql = 'SELECT ' . $intname . ' FROM users';
  $sql .= ' WHERE userid = %d';
  $rs = queryf_one($sql, $userid);
  if (!isset($rs) || !isset($rs[$intname])) {
    util_except('get_user_int(' . $intname
                . ') query result missing '
                . $intname);
  }
  return $rs[$intname];
}

function mod_update_user_int($userid, $intname, $newvalue) {
  $sql = 'UPDATE users SET ' . $intname . ' = %d';
  $sql .= ' WHERE userid = %d';
  queryf($sql, $newvalue, $userid);
}

function mod_update_user_lastdate($userid) {
  $nowtime = time();
  $sql = 'UPDATE users SET lastlogin = %d';
  $sql .= ' WHERE userid = %d';
  queryf($sql, $nowtime, $userid);
}

function mod_update_user_password($userid, $hashpass) {
  $nowtime = time();
  $sql = 'UPDATE users SET lastchange = %d, hashpass = %s';
  $sql .= ' WHERE userid = %d';
  queryf($sql, $nowtime, $hashpass, $userid);
}
