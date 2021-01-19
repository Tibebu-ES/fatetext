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

date_default_timezone_set('EST');

function var_list_str($arr, $str) {
  $num = count($arr);
  $rv = '';
  if ($num > 0) {
    $rv .= $str;
    for ($i=1; $i<$num; $i++) {
      $rv .= ', ' . $str;
    }
  }
  return $rv;
}

function util_split($chars, $str) {
  $chars = addslashes($chars);
  return preg_split("/[$chars]+/", $str, -1, PREG_SPLIT_NO_EMPTY);
}

function util_strip_ident($word) {
  return preg_replace("/[^A-Za-z0-9 ]/", '', $word);
}

function util_check_password($passtxt) {
  if (strlen($passtxt) < MIN_PASSWORD_LENGTH) {
    return 'Please enter a longer password.';
  }
  return '';
}

function util_check_handle($handle) {
  if(preg_match("/^([a-zA-Z])+([a-zA-Z0-9\._-])*$/", $handle)) {
    return true;
  }
  return false;
}

function util_check_email($email) {
  if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",
                $email)){
    list($username, $domain) = explode('@', $email);
    //if(!checkdnsrr($domain, 'MX')) {
    //  return false;
    //}
    return true;
  }
  return false;
}

function util_curl($url, $post_fields = NULL) {
  $options = array(
                   CURLOPT_RETURNTRANSFER => true,     // return web page
                   CURLOPT_HEADER         => false,    // don't return headers
                   CURLOPT_FOLLOWLOCATION => true,     // follow redirects
                   CURLOPT_ENCODING       => "",       // handle all encodings
                   CURLOPT_USERAGENT      => "spider", // who am i
                   CURLOPT_AUTOREFERER    => true,     // set referer on redirect
                   CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
                   CURLOPT_TIMEOUT        => 120,      // timeout on response
                   CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
                   );
  if ($post_fields !== NULL) {
    $options[CURLOPT_POST] = true;
    $options[CURLOPT_POSTFIELDS] = $post_fields;
  }

  $ch = curl_init($url);
  curl_setopt_array($ch, $options);
  $content = curl_exec($ch);
  $err = curl_errno($ch);
  $errmsg = curl_error($ch);
  $header = curl_getinfo($ch);
  curl_close($ch);

  $header['errno'] = $err;
  $header['errmsg'] = $errmsg;
  $header['content'] = $content;
  return $header;
}
