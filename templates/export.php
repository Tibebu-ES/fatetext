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

$sql = 'SELECT * FROM gems WHERE userid = %d ORDER BY userid, lastloaded DESC';
$rs = queryf_all($sql, web_get_user());

foreach ($rs as $row) {
  $guess = $quest = $answer = 'n/a';
  $username = web_get_user_name($row['userid']);
  $gemdata = mod_load_gem($row['gemid']);

  $sql = 'SELECT * FROM steps WHERE gemid=%d ORDER BY whichint';
  $rs2 = queryf_all($sql, $row['gemid']);
  $numrows = count($rs2);
  if ($numrows == 0) {
    $gemdata['tokstr'] = '_______';
  }
  if ($numrows > 0) {
    $guess = $rs2[0]['stepstr'];
  }
  if ($numrows > 1) {
    $quest = $rs2[1]['stepstr'];
  }
  if ($numrows > 2) {
    $answer = $rs2[2]['stepstr'];
  }

  echo '<p><table style="border: 1px solid #555555;">';

  echo '<tr><td valign="top">USER</td><td valign="top">';
  echo $username;
  echo '</td></tr>' . "\n";

  echo '<tr><td valign="top">CHEST</td><td valign="top">';
  echo $gemdata['chester'];
  echo '</td></tr>' . "\n";

  echo '<tr><td valign="top">TOKEN</td><td valign="top">';
  echo $gemdata['tokstr'];
  echo '</td></tr>' . "\n";

  echo '<tr><td valign="top">GUESS</td><td valign="top">';
  echo $guess;
  echo '</td></tr>' . "\n";

  echo '<tr><td valign="top">QUEST</td><td valign="top">';
  echo $quest;
  echo '</td></tr>' . "\n";

  echo '<tr><td valign="top">ANSWER</td><td valign="top">';
  echo $answer;
  echo '</td></tr>' . "\n";

  echo '</table></p>';
}
