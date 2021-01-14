<?php
$heading_class = 'page_heading';
$link_str = 'Back to the Hall of Fame';
$hall_str = gen_link('index.php?page=' . APP_PREFIX, $link_str); 
echo gen_p($hall_str, $heading_class);
?>

<h2>Articles on <?php echo $datestr; ?></h2>
<div class="content">
TODO
</div>
