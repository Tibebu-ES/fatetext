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

define('PADDING_STR', '&nbsp;&nbsp;');

function gen_copyright_notice($add_break = false, $add_llc = true) {
  $cw_holder = $GLOBALS['COPYRIGHT_HOLDER'];
  $cw_url = $GLOBALS['COPYRIGHT_URL'];

  $rv = '';
  $rv .= '<p class="copyright">© ';
  $rv .= gen_link($cw_url, $cw_holder, '', false);
  if ($add_llc) {
    $rv .= ' LLC';
  }
  if ($add_break) {
    $rv .= '<br>';
  }
  $rv .= ' All Rights Reserved.</p>';
  return $rv;
}

function gen_link($url, $text, $css_class = '', $relative = true) {
  if ($relative) {
    $url = util_url($url);
  }
  $rv = '<a href="' . $url . '"';
  if ($css_class != '') {
    $rv .= ' class="' . $css_class . '"';
  }
  $rv .= '>' . $text . '</a>';
  return $rv;
}

function gen_img($img_url, $alt_text,
                 $width = null, $height = null,
                $link_url = null) {
  $rv = '';
  $rv .= '<img src="' . $img_url . '" alt="' . $alt_text . '"';

  if ($width !== null) {
    $rv .= ' width="' . $width . '"';    
  }
  if ($height !== null) {
    $rv .= ' height="' . $height . '"';    
  }

  $rv .= '">';

  if ($link_url === null) {
    return $rv;
  } else {
    return gen_link($link_url, $rv);
  }
}

function gen_video($video_id, $alt_text, $width, $height) {
  $rv = '';
  $rv .= '<iframe width="' . $width . '" ';
  $rv .= 'height="' . $height . '" alt="' . $alt_text . '"';
  $rv .= 'src="http://www.youtube.com/embed/';
  $rv .= $video_id . '?rel=0" ';
  $rv .= 'frameborder="0" allowfullscreen></iframe>';
  return $rv;
}

function gen_h($hnum, $h_text) {
  return '<h' . $hnum . '>' . $h_text . '</h' . $hnum . '>';
}

function gen_name($name) {
  return '<a name="' . $name . '"></a>';
}

function gen_b($text) {
  return '<b>' . $text . '</b>';
}

function gen_i($text) {
  return '<i>' . $text . '</i>';
}

function gen_tag($tagstr, $text, $css_class, $add_el = true) {
  $rv = '';
  $rv .= '<' . $tagstr;
  if (isset($css_class)) {
    $rv .= ' class="' . $css_class . '"';
  }
  $rv .= '>' . $text . '</' . $tagstr . '>';
  if ($add_el) $rv .= "\n";
  return $rv;
}

function gen_span($text, $css_class = null, $add_el = false) {
  return gen_tag('span', $text, $css_class, $add_el);
}

function gen_p($text, $css_class = null, $add_el = true) {
  return gen_tag('p', $text, $css_class, $add_el);
}

function gen_div($text, $css_class = null, $add_el = true) {
  return gen_tag('div', $text, $css_class, $add_el);
}

function gen_text_area($inname, $invalue, $inrows,
                      $incols, $inplace = null, $add_el = true) {
  $rv = '';
  $rv .= '<textarea name="' . $inname;
  $rv .= '" rows="' . $inrows;
  $rv .= '" cols="' . $incols;
  if (isset($inplace)) {
    $rv .= '" placeholder="' . $inplace;
  }
  $rv .= '">' . $invalue . '</textarea>';
  if ($add_el) $rv .= "\n";
  return $rv;
}

function gen_txt_input($inname, $invalue, $insize,
                       $inplace, $add_el = true) {
  $rv = '';
  $rv .= '<input type ="text';
  $rv .= '" name="' . $inname;
  $rv .= '" value="' . $invalue;
  $rv .= '" size="' . $insize;
  if (isset($inplace)) {
    $rv .= '" placeholder="' . $inplace;
  }
  $rv .= '">';
  if ($add_el) $rv .= "\n";
  return $rv;
}

function gen_input($intype, $inname, $invalue, $add_el = true) {
  $rv = '';
  $rv .= '<input type="' . $intype;
  $rv .= '" name ="' . $inname;
  $rv .= '" value="' . $invalue . '">';
  if ($add_el) $rv .= "\n";
  return $rv;
}

function gen_select_input($inname, $option_arr, $selcat, $add_el = true) {
  $rv = '<select name="' . $inname . '">';
  foreach ($option_arr as $opt) {
    if ($add_el) $rv .= "\n";
    $loweropt = strtolower($opt);
    $rv .= '<option value="' . $loweropt . '"';
    if ($selcat == $loweropt) $rv .= ' selected';
    $rv .= '>' . $opt . '</option>';
  }
  $rv .= '</select>';
  if ($add_el) $rv .= "\n";
  return $rv;
}

function gen_form($elem_arr, $fomethod = 'post',
                  $foaction = 'index.php', $add_el = true) {
  $rv = '<form method="' . $fomethod . '" action="' . $foaction . '">';
  foreach ($elem_arr as $elem) {
    //if ($add_el) $rv .= "\n";
    $rv .= $elem;
  }
  $rv .= '</form>';
  if ($add_el) $rv .= "\n";
  return $rv;
}

function gen_two_col_table($left_col, $right_col, $add_el = true) {
  $rv = '';
  $rv .= '<table><tr><td valign="top">';
  $rv .= $left_col . '</td><td valign="top">' . $right_col;
  $rv .= '</td></tr></table>';
  if ($add_el) $rv .= "\n";
  return $rv;
}

function gen_two_cols($left_col, $right_col, $add_el = true) {
  $rv = '';
  $rv .= '<div class="row">';
  $rv .= '<div class="column">';
  if ($add_el) $rv .= "\n";

  $rv .= $left_col . '</div>';
  $rv .= '<div class="column">';
  if ($add_el) $rv .= "\n";

  $rv .= $right_col . '</div>';
  $rv .= '</div>';
  if ($add_el) $rv .= "\n";
  return $rv;
}

function gen_title_bar($title_html, $is_box = false, $add_el = true) {
  $rv = '';
  $rv .= '<div class="title_bar">' . $title_html . '</div>';
  if ($add_el) $rv .= "\n";
  return $rv;
}

function gen_title_box($title_bar_html, $content_html, $add_el = true) {
  $rv = '';
  $rv .= '<div class="title_box">' . $title_bar_html . '</div>';
  if ($add_el) $rv .= "\n";
  $rv .= '<div class="title_box">';
  $rv .= '<div class="title_content">';
  $rv .= $content_html . '</div></div>';
  if ($add_el) $rv .= "\n";
  return $rv;
}
