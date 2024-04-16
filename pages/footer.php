<?php if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); } ?>


<div id="footer" class="headerFooterCss" style="<?php if($page->slug == 'checkout' || $page->slug == 'checkout2') echo "display:none"; ?>">
	<?php echo  Page::html_res_change($page->footer, '10x10'); ?>
</div>


<div id="templatesDiv" style="display:none;" ></div>
</body>

<link rel="stylesheet" href="/assets/css/animate.css?v=<?php echo JS_VERSION ?>">


<?= $core->web['scripts']['footer'];?>

<!-- IMPORTNAT : AUTOLOAD.JS MUST BE BEFORE ALPINE.js  -->


<script type="module" src="\components\core\autoload.js?v=<?php echo JS_VERSION ?>" ></script>

<script type="module" src="\assets\plugins\alpinejs\alpine.js?v=<?php echo JS_VERSION ?>"></script>


<!-- SCRIPTS AND STYLES FOR GLIDE SLIDER -->
 <link href="<?php echo $core->site_url ?>/editor/cb/assets/scripts/glide/css/glide.core.min.css" rel="stylesheet">
<link href="<?php echo $core->site_url ?>/editor/cb/assets/scripts/glide/css/glide.theme.css" rel="stylesheet">
<script src="<?php echo $core->site_url ?>/editor/cb/assets/scripts/glide/glide.min.js"></script>

<script src="https://kit.fontawesome.com/134d7d4e2d.js" crossorigin="anonymous"></script>


<!-- SCRIPT FOR EDITOR  -->
<script src="<?= CBURL ?>box/box-flex.js?v=<?php echo JS_VERSION ?>"></script>


<!--  INTERCEPT LINKS AND LOAD NEW PAGE SCRITP   -->

<script type="module" src="\components\core\classes\link.js?v=<?php echo JS_VERSION ?>"></script>

<?= $core->web['scripts']['footer'] ?? '' ?>

</html>
