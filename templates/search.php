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

$curuser = web_get_user();
$safetext = '';
$incat = '';
if (isset($data['stxt'])) {
  $safetext = htmlentities($data['stxt']);
}
if (isset($data['category'])) {
  $incat = $data['category'];
}
$textarea = web_get_user_flag($curuser, TEXT_AREA_FLAG);
echo gen_search_form($safetext, $textarea, $incat);

$guessdata = null;
$lastgemid = mod_get_user_lastgem($curuser);
if ($lastgemid == null) {
  echo gen_p('Choose a text and then click on the [Search] button in order to generate a gem!');
} else {
  $gemdata = mod_load_gem($lastgemid);
  $tempstr = gen_b('GEM #' . gen_i($gemdata['gemid']));
  $tempstr .= PADDING_STR . gen_b('(') . 'Created ' . gen_b('at ');
  $tempstr .= gen_i(fd($gemdata['datecreated']));
  if ($gemdata['stepint'] > 0) {
    $guessdata = mod_load_step($gemdata['gemid'], 1);
    $duration = $guessdata['datecreated'] - $gemdata['datecreated'];
    $tempstr .= PADDING_STR . gen_b('\'in ') . $duration . ' secs';
  }
  echo gen_p(gen_u($tempstr . gen_b(')') . PADDING_STR));

  if ($gemdata['stepint'] == 0) {

    $tempstr = 'Guess the blanked out word in the following sentence:';
    $tempstr = gen_p(gen_b('Step 1: ') . $tempstr);
    $tempstr .= gen_div($gemdata['chester'], 'gem_text');
    $tempstr .= gen_p(gen_gem_guess_form($gemdata));
    echo gen_div($tempstr, 'gem_step');

  } else {

    util_assert(isset($guessdata));
    $tempstr = gen_b('Step 1: ') . ' you guessed ';
    if (strtolower($guessdata['stepstr']) == strtolower($gemdata['tokstr'])) {
      $coin_url = gen_url('coin');
      $tempstr .= gen_link($coin_url, 'correctly') . '! (ANSWER: ';
      $tempstr .= gen_b($gemdata['tokstr']) . ')';
    } else {
      $tempstr .= gen_b(gen_u($guessdata['stepstr']));
      $tempstr .= ' (ANSWER: ' . gen_b($gemdata['tokstr']) . ')';
    }
    $tempstr = gen_p($tempstr);
    $tempstr .= gen_div($gemdata['chester'], 'gem_text');
    echo gen_div($tempstr, 'gem_step');

    if ($gemdata['stepint'] == 1) {

      $tempstr = 'Now, ask a question about that same sentence:';
      $tempstr = gen_p(gen_b('Step 2: ') . $tempstr);
      $tempstr .= gen_gem_quest_form($gemdata);
      echo gen_div($tempstr, 'gem_step');

    } else {

      $questdata = mod_load_step($gemdata['gemid'], 2);
      $tempstr = 'You asked the following question at ';
      $tempstr .= fd($questdata['datecreated']) . ':';
      $tempstr = gen_p(gen_b('Step 2: ') . $tempstr);
      $tempstr .= gen_div($questdata['stepstr'], 'quest_text');
      echo gen_div($tempstr, 'gem_step');      

      $stepvalue = '';
      $lastsaved = 0;
      if ($gemdata['stepint'] > 2) {
        $ansdata = mod_load_step($gemdata['gemid'], 3);
        $stepvalue = $ansdata['stepstr'];
        $lastsaved = $ansdata['datecreated'];
      }

      $tempstr = 'Please record the best answer to your question:';
      $tempstr = gen_p(gen_b('Step 3: ') . $tempstr);
      $tempstr .= gen_gem_answer_form($gemdata, $stepvalue, $lastsaved);
      echo gen_div($tempstr, 'gem_step');

    }
  }
}

/*$safecat = '';
if ($incat != '') {
  $safecat = htmlentities($incat);
  $safecat = '<b>[</b>' . $safecat . '<b>]</b><br>';
}

if ($safetext != '') {
  $safetext = str_replace("\n", '<br>', $safetext);
  $huttxt = $safecat . $safetext;
  echo 'Safe hut = "<br>' . $huttxt . '"';
  mod_log_search($huttxt);
} else {
  echo 'TODO generate new hug';
  if ($incat != '') {
  	echo ' from ' . $safecat;
  }
  mod_log_search('');
}*/
?>
