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

function mod_generate_gem($userid, $stxt, $category) {
  $book_filter = ' WHERE ';

  if (!isset($category)) {
    util_except('called generate_gem without a category');
  }

  switch ($category) {
   case 'CLEAR':
   case 'CUSTOM':
     util_except('called generate_gem with invalid category ' . $category);
     break;

   case DEFAULT_CATEGORY:
     $book_filter = '';
     break;

   case 'horace':
     $book_filter .= 'bookid = 11';
     break;

   case 'politics':
     $book_filter .= 'bookid = 10';
     break;

   case 'republic':
     $book_filter .= 'bookid = 9';
     break;

   case 'iliad':
     $book_filter .= 'bookid = 8';
     break;

   case 'aeneid':
     $book_filter .= 'bookid = 7';
     break;

   case 'marcus':
     $book_filter .= 'bookid = 6';
     break;

   case 'theBard':
     $book_filter .= 'bookid = 5';
     break;

   case 'kjBible':
     $book_filter .= 'bookid = 4';
     break;

   case 'suzyThe':
     $book_filter .= 'bookid = 1 OR bookid = 2 OR bookid = 3';
     break;

   case 'suzyMem':
     $book_filter .= 'bookid = 1';
     break;

   case 'theShow':
     $book_filter .= 'bookid = 2';
     break;

   case 'theMems':
     $book_filter .= 'bookid = 3';
     break;
  }

  $sql = 'SELECT MIN(tokid) as minid, MAX(tokid) as maxid';
  $sql .= ' FROM toks' . $book_filter;
  $rs = queryf_one($sql);
  $minid = $rs['minid'];
  $maxid = $rs['maxid'];

  $randtokid = rand($minid, $maxid);
  $sql = 'SELECT chestidstr, bookid FROM toks WHERE tokid = %d';
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

  $default_rows = user_get_default_rows($userid);

  $nowtime = time();
  $sql = 'INSERT INTO gems (userid, chestid, tokid, stepint, datecreated,';
  $sql .= ' wordcount, charcount, lastloaded, ansrows)';
  $sql .= ' VALUES (%d, %d, %d, %d, %d, %d, %d, %d, %d)';
  queryf($sql, $userid, $randchestid, $randtokid, 0, $nowtime, $wordcount,
         $charcount, $nowtime, $default_rows);
  return last_insert_id();
}

function mod_get_gem_book($gem_id) {
  $sql = 'SELECT chestid FROM gems WHERE gemid = %d';
  $rs = queryf_one($sql, $gem_id);
  $sql = 'SELECT bookid FROM chests WHERE chestid = %d';
  $rs2 = queryf_one($sql, $rs['chestid']);
  return $rs2['bookid'];
}

function mod_get_gem_auth($gem_id) {
  $book_id = mod_get_gem_book($gem_id);
  $sql = 'SELECT authorstr FROM books WHERE bookid = %d';
  $rs = queryf_one($sql, $book_id);
  return $rs['authorstr'];
}

function mod_update_gem_auth_and_text($gem_id, $auth_str, $text_str) {
  $sql = 'UPDATE gems SET authguess = %s, bookguess = %s';
  $sql .= ' WHERE gemid = %d';
  queryf($sql, $auth_str, $text_str, $gem_id);
}

function mod_update_gem_step($gemid, $newstep) {
  $sql = 'UPDATE gems SET stepint = %d';
  $sql .= ' WHERE gemid = %d';
  queryf($sql, $newstep, $gemid);
}

function mod_load_gem($gemid) {
  $sql = 'SELECT * FROM gems WHERE gemid = %d';
  $rv = queryf_one($sql, $gemid);

    if ($rv['authguess'] === null) {
    $rv['authstr'] = 'n/a';
  } else {
    $rv['authstr'] = $rv['authguess'];
  }

  if ($rv['bookguess'] == 0) {
    $rv['bookstr'] = 'n/a';
  } else {
    $rv['bookstr'] = mod_get_book_title($rv['bookguess']);
  }

  $sql = 'SELECT tokstr FROM toks WHERE tokid = %d';
  $rs = queryf_one($sql, $rv['tokid']);
  $rv['tokstr'] = $rs['tokstr'];

  $sql = 'SELECT datastr, bookid FROM chests WHERE chestid = %d';
  $rs = queryf_one($sql, $rv['chestid']);
  $rv['datastr'] = $rs['datastr'];

  if ($rv['chestid'] > 1 && $rs['bookid'] > 4) {
    $sql = 'SELECT datastr FROM chests WHERE chestid = %d';
    $rs2 = queryf_one($sql, $rv['chestid'] - 1);
    $rv['datastr'] = $rs2['datastr'] . ' ' . $rv['datastr'];
    $sql = 'SELECT datastr FROM chests WHERE chestid = %d';
    $rs2 = queryf_one($sql, $rv['chestid'] + 1);
    $rv['datastr'] .= ' ' . $rs2['datastr'];
  }

  $rv['chester'] = preg_replace('/' . $rv['tokstr'] . '/i',
                                '_______', $rv['datastr']);
  return $rv;
}

function mod_update_gem_lastloaded($gemid) {
  $nowtime = time();
  $sql = 'UPDATE gems SET lastloaded = %d';
  $sql .= ' WHERE gemid = %d';
  queryf($sql, $nowtime, $gemid);
}
