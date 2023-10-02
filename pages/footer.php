<?php if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); } ?>


<div id="footer" class="headerFooterCss" style="<?php if($page->slug == 'checkout' || $page->slug == 'checkout2') echo "display:none"; ?>">
	<?php echo  Page::html_res_change($page->footer, '10x10'); ?>
</div>





 <?= Inline::buttonEdit(); ?>


<div id="templatesDiv" style="display:none;" ></div>
</body>

<link rel="stylesheet" href="/assets/css/animate.css">


<?= $core->web['scripts']['footer'];?>

<!-- IMPORTNAT : AUTOLOAD.JS MUST BE BEFORE ALPINE.js  -->

<script type="module" src="\components\core\autoload.js" ></script>

<script type="module" src="\assets\plugins\alpinejs\alpine.js"></script>

<script type="module" src="\components\core\classes\page.js" charset="utf-8"></script>


<!-- SCRIPTS AND STYLES FOR GLIDE SLIDER -->
 <link href="<?php echo $core->site_url ?>/editor/cb/assets/scripts/glide/css/glide.core.min.css" rel="stylesheet">
<link href="<?php echo $core->site_url ?>/editor/cb/assets/scripts/glide/css/glide.theme.css" rel="stylesheet">
<script src="<?php echo $core->site_url ?>/editor/cb/assets/scripts/glide/glide.min.js"></script>


<!-- ICONS  -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
<!-- <link rel="stylesheet" type="text/css" href="<?= CBURL ?>assets/styles/simple-line-icons/css/simple-line-icons.min.css"> -->

<script src="https://kit.fontawesome.com/134d7d4e2d.js" crossorigin="anonymous"></script>


<!-- SCRIPT FOR EDITOR  -->
<script src="<?= CBURL ?>box/box-flex.js"></script>


<!--  INTERCEPT LINKS AND LOAD NEW PAGE SCRITP   -->
<script type="module" src="\components\core\classes\page.js"></script>
<script type="module" src="\components\core\classes\link.js"></script>

<?= $core->web['scripts']['footer'] ?? '' ?>

</html>
