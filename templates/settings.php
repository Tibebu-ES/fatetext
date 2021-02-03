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

$hashmsg = '';
if (isset($_REQUEST['inpass'])) {
  $inpass = $_REQUEST['inpass'];
  $salt = 'asdf';
  $hashpass = sha1($inpass . $salt);
  $inpass = htmlspecialchars($inpass);
  $hashmsg = 'haspass for "' . gen_b(gen_i($inpass));
  $hashmsg = gen_p($hashmsg . '" =<br>' . $hashpass);
}

echo gen_search_form();
?>

<pre><b>*<u>Account</u>*</b> | Archive | TheDocs (or AdminHQ)</pre>

<?php echo $hashmsg; ?>
<form action="?page=settings" method="post">
<input type="text" name="inpass" size="30"><br>
<input type="submit" value="Get HASHPASS">
</form>

<p><?php
echo gen_link(gen_url('admin'), 'AdminHQ');

?></p>
