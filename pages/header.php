<?php if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); } ?>
<!DOCTYPE html>
<html lang="<?= $lang->language ?>">
	<head>

		<!-- SEO PAGE SETTINGS  -->
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo ($page->seo_title != '') ? $page->seo_title : 'Ножовете'?></title>
		<link rel="icon" type="image/x-icon" href="<?php echo $core->web['favicon'] ?>">
		<meta property="og:url" content="<?php echo "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>" />
		<meta property="og:type" content="website" />
		<meta name="author" content="https://expozy.com/">




		<!--START META TAG FOR BING  -->
		<meta name="msvalidate.01" content="F2343A2EA0EE68B8F817CB128D3CD061" />
		<!--END META TAG FOR BING  -->


<!-- CORE SYSTEM SETTINGS -->
		<script type="text/javascript">
			const SITEURL = "<?php echo $core->site_url ?>";
			const LANG = "<?php echo $lang->language ?>";
			const SAAS_KEY = "<?php echo SAAS_KEY ?>";
			const COREURL = "<?php echo CORE_URL; ?>api/";
			const LOGGED_IN = "<?php echo $user->logged_in ?>";
			const USER_EMAIL = "<?php echo $user->email ?>";
			const PAGEINIT = <?php echo json_encode($page, JSON_UNESCAPED_UNICODE) ?>;
			const USER = <?php echo json_encode($user, JSON_UNESCAPED_UNICODE) ?>;
			const LOGO_URL = "<?php echo $core->web['logo'] ?>";
			const FAVICON_URL = "<?php echo $core->web['favicon'] ?>";
			const SOCIAL_NETWORKS = <?php echo json_encode($core->web['links'], JSON_UNESCAPED_UNICODE) ?>;
			const DEV_MODE = <?php echo $core->devMode ? 1:0 ?>;
			const URL_PARAMETERS = <?php echo json_encode($_GET) ?>;
		</script>



		<!-- SCRIPT WITH LANGUAGE VARIABLES. LOAD ONLY CURRENT LANGUAGE   -->
		<script src="/assets/lang/<?php echo $lang->language ?>.js" charset="utf-8"></script>


		<link href="<?php echo SITEURL. (isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : '') ?>" rel="canonical" />
		<script  src="/components/core/globals.js" charset="utf-8"></script>


		<!-- CORE SYSTEM SETTINGS -->
		<link rel="stylesheet" href="/assets/css/custom.css">
		<link href="<?= CBURL ?>assets/minimalist-blocks/content.css" rel="stylesheet" type="text/css" />
		<link href="<?= CBURL ?>box/box-flex.css" rel="stylesheet" type="text/css" />

		<link href="https://db.onlinewebfonts.com/c/74e0e2072464f4b71945ae7a9b3c85d4?family=Repo-DemiBold" rel="stylesheet">

	</head>

	<!-- CSS FOR CURRENT PAGE GENERATOR FROM ALPINE -->
		<div id="pageCss"><?= $page->css ?></div>

		<!-- INIT BODY FUNCTION. CONNECTED WITH ALPINE X_DATA  -->
		<body x-data="dataset" @update.window="updatedata($event.detail)" id="body" x-init="$watch('data', value => console.log(value))">

			<!-- NOTIFICATION CONTAINER. ALPINE ADD MESSAGES FROM CORE  -->
			<div id="notification" @notice.window="add($event.detail)" x-data="notification" style="z-index:999999;" class=" p-7 fixed inset-0 w-screen flex flex-col-reverse items-end justify-end pointer-events-none" >
				<template x-for="notice of notices" :key="notice.id">
					<div
					x-show="visible.includes(notice)"
					x-transition:enter="transition ease-in duration-400"
					x-transition:enter-start="transform opacity-0 translate-x-full"
					x-transition:enter-end="transform opacity-100"
					x-transition:leave="transition ease-out duration-500"
					x-transition:leave-start="transform translate-x-0 opacity-100"
					x-transition:leave-end="transform translate-x-full opacity-0"§
					@click="remove(notice.id)"
					class="rounded mb-4 p-3 w-full max-w-md text-white shadow-lg cursor-pointer pointer-events-auto " :class="notice.type == 'error' ? 'bg-red-800' : 'bg-green-800'"
					x-text="notice.text">
				</div>
			</template>
		</div>

<?php	 if($core->devMode && $user->logged_in && $user->userlevel == 99) { ?>

	<div style="display: none;" id="tailwindCss"></div>
	<div style="width:100px;height: 50px;position: fixed;right: 100px;bottom: 60px;background-color: red;z-index: 1000;display: flex;justify-content: center;align-items: center;border-radius: 25px;color: white;font-weight: bold;letter-spacing: 1.2px;font-size: 18px;cursor: pointer;" id="dev_save">Save</div>
	<script src="/assets/plugins/tailwindcss.3.3.1.js"></script>
<?php } ?>

<div  :class="data.corePage.slug != 'checkout' ? '!block' : ' hidden' " x-cloack  style="display:none;">
	<?php echo  Page::html_res_change($page->header, '10x10'); ?>
</div>
