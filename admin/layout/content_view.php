<?php
$slug = $args['content']->slug;
$content_link = SITE_URL.'/'.$slug;
?>
<iframe width="100%" src="<?php echo $content_link; ?>" scrolling="no" id="fullheight_iframe" onLoad="calcHeight();" height="1px" frameborder="0" ></iframe>