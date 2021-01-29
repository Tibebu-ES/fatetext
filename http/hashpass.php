<form action="" method="get">
<input type="submit" value="Get HASHPASS">
<input type="text" name="inpass" size="30">
</form>

<?php
$inpass = $_REQUEST['inpass'];
$salt = 'asdf';
$hashpass = sha1($inpass . $salt);
echo '<p><u>' . $inpass . '</u> -&gt; ' . $hashpass . '</p>';
