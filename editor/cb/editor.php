<?php

if(session_id() == '') session_start();

$parameters = json_decode(base64_decode($_GET['i'] ?? ''), true);


if(!isset($_GET['i'])) die();


define( "_VALID_PHP", true);
require_once '../../core/autoload.php';

if(isset($parameters['token'])){
	
	
	require_once(BASEPATH.'core/classes/class.cryptor.php');
	$_SESSION['token'] = Cryptor::Decrypt($parameters['token'], SAAS_KEY);
	
	$user = new Users();
}



$editor = new Editor($_GET['i']);



$dir = SITEURL.'/editor/cb/';
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
     <title>Editor-<?= $page->type;?>-<?= $page->slug?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link rel="shortcut icon" href="#"> 
    
	<script type="text/javascript">
  		const SITEURL = "<?= SITEURL ?>";
  		const LANG = "<?= $lang->language ?>";
  		const SAAS_KEY = "<?= SAAS_KEY ?>";
  		const COREURL = "<?= CORE_URL; ?>api/"
  		localStorage.setItem('token', '<?= $_SESSION['token']?>');
  	</script>
	
	
	
    <!-- Required css for production -->    
    <link href="<?= $dir ?>assets/minimalist-blocks/content.css" rel="stylesheet"> 
    <link href="<?= $dir ?>box/box-flex.css" rel="stylesheet"> 

    <!-- Required css for editing (not needed in production) -->   
    <link href="<?= $dir ?>contentbuilder/contentbuilder.css" rel="stylesheet">
    <link href="<?= $dir ?>contentbox/contentbox.css" rel="stylesheet">
	
	<style>
        /* Switch the device buttons to save space on smaller screen */
        @media all and (max-width: 1380px) {
            .custom-topbar .btn-device-desktop,
            .custom-topbar .btn-device-tablet,
            .custom-topbar .btn-device-tablet-landscape,
            .custom-topbar .btn-device-mobile {display:none !important} /* Hide the topbar buttons */

            .is-responsive-toolbar {display:inline-flex !important} /* Show the default buttons */
        }
        .topbar-shadow {
            position: fixed;
            left: 0;top: 47px;
            width: 100%;
            height: 5px;
            z-index: 1;
            box-shadow: rgba(0, 0, 0, 0.04) 0px 5px 5px 0px;
        }
    </style>
</head>
<body>

<!-- Example of custom toolbar -->
<div class="builder-ui keep-selection custom-topbar" data-tooltip>
    <div>
		
        <?php echo $editor->title ?>
    </div>
    <div>
        <!-- custom buttons here -->
        

        <div class="separator"></div>



        <button class="btn-save" title="Save" id="saveBtn">
            <svg><use xlink:href="#icon-save"></use></svg>
            <span>Save</span>
        </button>
		<button class="btn-loader" title="Loading" id="loaderBtn" style="display:none;">
            <i class="fa-solid fa-spinner animate-spin text-[22px]"></i>
        </button>
		

        

    </div>
    <div>
        <!-- custom buttons here -->
        <button class="btn-device-desktop-large" data-device="desktop-lg" title="Desktop - Large Screen">
            <svg style="width:18px;height:18px;"><use xlink:href="#icon-device-desktop"></use></svg>
        </button>
        <button class="btn-device-desktop" data-device="desktop" title="Desktop / Laptop">
            <svg style="width:20px;height:20px;"><use xlink:href="#icon-device-laptop"></use></svg>
        </button>
        <button class="btn-device-tablet-landscape" data-device="tablet-landscape" title="Tablet - Landscape">
            <svg style="width:18px;height:18px;transform:rotate(-90deg)"><use xlink:href="#icon-device-tablet"></use></svg>
        </button>
        <button class="btn-device-tablet" data-device="tablet" title="Tablet - Portrait">
            <svg  style="width:18px;height:18px;"><use xlink:href="#icon-device-tablet"></use></svg>
        </button>
        <button class="btn-device-mobile" data-device="mobile" title="Mobile">
            <svg  style="width:18px;height:18px;"><use xlink:href="#icon-device-mobile"></use></svg>
        </button>
        <button class="btn-fullview" data-device="fullview" title="Full View">
            <svg  style="width:18px;height:18px;"><use xlink:href="#icon-fullview"></use></svg>
        </button>

        <div class="separator"></div>

        <button class="btn-download" title="Download">
            <svg><use xlink:href="#icon-download"></use></svg>
        </button>



        <div class="separator"></div>

        <button class="btn-togglepanel" data-button="togglepanel" title="Toggle Edit Panel"> <!-- To enable state, add:  data-state="togglepanel" -->
            <svg><use xlink:href="#icon-pencil"></use></svg>
        </button>
    </div>
</div>
<div class="topbar-shadow"></div>

    <div class="is-wrapper" style="opacity:0;" id="mainContent">
    <?php  echo empty($editor->html) ? '' : $editor->html;   ?>
        </div>
  


        <div style="display: none;" id="tailwindCss"></div>
<!-- <div style="display: none;" id="templatesDiv"></div> -->
<div style="display: none !important;" id="headerFooterHtml">
<?php

		if($page->slug == 'header'){
			echo $page->footer;

		} else if($page->slug == 'footer'){
			echo $page->header;
		}
?>
<?php  ?></div>

<!-- Slider feature (by setting slider: 'glide') -->
<link href="<?= $dir ?>assets/scripts/glide/css/glide.core.css" rel="stylesheet">
<link href="<?= $dir ?>assets/scripts/glide/css/glide.theme.css" rel="stylesheet">
<script src="<?= $dir ?>assets/scripts/glide/glide.js"></script>

<!-- Navbar feature (by setting navbar: true) -->
<link href="<?= $dir ?>assets/scripts/navbar/navbar.css" rel="stylesheet">
<script src="<?= $dir ?>assets/scripts/navbar/navbar.min.js"></script>

<!-- Required js for editing (not needed in production) -->  
<script src="<?= $dir ?>contentbox/lang/en.js"></script>
<script src="<?= $dir ?>contentbox/contentbox.min.js"></script>

<script>
	
    var timeoutId; //Used for Auto Save

    //Enable editing
    const builder = new ContentBox({
        
        previewURL: 'preview.html', //'preview.html',

        controlPanel: true,
        iframeSrc: 'blank.html',
        zoom: 1,
        //screenMode: 'fullview', // or fullview
        topSpace: true, // to give a space on top for custom toolbar
        iframeCentered: true,
		
		// htmlButton: false, // HTML button on left sidebar
        // undoRedoButtons: false, // Undo & redo buttons on control panel
        toggleDeviceButton: false, // Toggle device button on control panel
        deviceButtons: false, // Multiple device buttons on frame
		//
		//
        // To enable AI Assistant
        sendCommandUrl: 'api/sendcommand.php',
        // AIToolbar: false,
        // showDisclaimer: false,
        // startAIAssistant: true, // Auto open 'AI Assistant' panel
        // enableShortCommands: false,
        speechRecognitionLang: 'en-US', 
        triggerWords: {
            send: ['send', 'okay', 'ok', 'execute', 'run'],
            abort: ['abort', 'cancel'],
            clear: ['clear', 'erase']
        },
        
        // If using DeepGram for speech recognition, specify the speechTranscribeUrl.
        // speechTranscribeUrl: 'ws://localhost:3002',
        // The server implementation for ws://localhost:3002 can be found in server.js (Node.js code)

        // Enabling AI image generation
        textToImageUrl: 'api/texttoimage.php', 
        upscaleImageUrl: 'api/upscaleimage.php',
        imageAutoUpscale: false,
        viewFileUrl: 'api/viewfile.php', // Used if using S3 (public bucket).
        
         templates: [
            {   
                url: '<?= $dir ?>assets/templates-simple/templates.js',
                path: '<?= $dir ?>assets/templates-simple/', 
                pathReplace: [],
                numbering: true,
                showNumberOnHover: true,
            },
            {   
                url: '<?= $dir ?>assets/templates-quick/templates.js',
                path: '<?= $dir ?>assets/templates-quick/', 
                pathReplace: [],
                numbering: true,
                showNumberOnHover: true,
            },
            {   
                url: '<?= $dir ?>assets/templates-animated/templates.js',
                path: '<?= $dir ?>assets/templates-animated/', 
                pathReplace: [],
                numbering: true,
                showNumberOnHover: true,
            },
						 
			// thelayout BLOCKS
            {
               url: '<?= $dir ?>assets/thelayout/templates.js',
               path: '<?= $dir ?>assets/thelayout/',
               pathReplace: [],
               numbering: true,
               showNumberOnHover: true,
           },
        ],
	
	
        // Open asset/file browser (can be replaced with your own asset/file manager application)
        imageSelect: 'assets.html',
        videoSelect: 'assets.html',
        audioSelect: 'assets.html',
        fileSelect: 'assets.html',
        mediaSelect: 'assets.html', // for images and videos
        // You can replace it with your own asset/file manager application
        // or use: https://innovastudio.com/asset-manager

        // Or use custom:
        // onImageSelectClick: () => {  }, 
        // onVideoSelectClick: () => {  },
        // onAudioSelectClick: () => {  },
        // onFileSelectClick: () => {  },
        // onMediaSelectClick: () => {  }, 

        onUploadCoverImage: (e) => {
            uploadFile(e, (response)=>{
                if(response.error) {
                    alert(response.error);
                    return;
                }
                const uploadedFileUrl = response.url; // get saved image url
                if(uploadedFileUrl) builder.boxImage(uploadedFileUrl); // change cover image
            });
        },
        onImageUpload: (e)=>{
            uploadFile(e, (response)=>{
                if(response.error) {
                    alert(response.error);
                    builder.returnUrl(false);
                    return;
                }
                const uploadedFileUrl = response.url; // get saved file url
                if(uploadedFileUrl) builder.returnUrl(uploadedFileUrl); // apply
            });
        },
        onVideoUpload: (e)=>{
            uploadFile(e, (response)=>{
                if(response.error) {
                    alert(response.error);
                    builder.returnUrl(false);
                    return;
                }
                const uploadedFileUrl = response.url; // get saved file url
                if(uploadedFileUrl) builder.returnUrl(uploadedFileUrl); // apply
            });
        },  
        onAudioUpload: (e)=>{
            uploadFile(e, (response)=>{
                if(response.error) {
                    alert(response.error);
                    builder.returnUrl(false);
                    return;
                }
                const uploadedFileUrl = response.url; // get saved file url
                if(uploadedFileUrl) builder.returnUrl(uploadedFileUrl); // apply
            });
        }, 
        onMediaUpload: (e)=>{
            uploadFile(e, (response)=>{
                if(response.error) {
                    alert(response.error);
                    builder.returnUrl(false);
                    return;
                }
                const uploadedFileUrl = response.url; // get saved file url
                if(uploadedFileUrl) builder.returnUrl(uploadedFileUrl); // apply
            });
        }, 
        onFileUpload: (e)=>{
            uploadFile(e, (response)=>{
                if(response.error) {
                    alert(response.error);
                    builder.returnUrl(false);
                    return;
                }
                const uploadedFileUrl = response.url; // get saved file url
                if(uploadedFileUrl) builder.returnUrl(uploadedFileUrl); // apply
            });
        }, 
        
        onChange: function () {
            //Auto Save
//            clearTimeout(timeoutId);
//            timeoutId = setTimeout(function () {
//                save();                    
//            }, 1000);
        },

        slider: 'glide',
        navbar: true,
        
        /* ContentBox settings */
        // designUrl1: 'assets/designs/basic.js',
        // designUrl2: 'assets/designs/examples.js',
        // designPath: 'assets/designs/',
        // contentStylePath: 'assets/styles/',

        /* ContentBuilder settings */
        // modulePath: 'assets/modules/', 
        // fontAssetPath: 'assets/fonts/', 
        // assetPath: 'assets/', 
        // snippetUrl: 'assets/minimalist-blocks/content.js', 
        // snippetPath: 'assets/minimalist-blocks/',
        // pluginPath: 'contentbuilder/', 
        // useLightbox: true,

    });

    // Load content
    fetch('api/loadcontent.php', {
        method:'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response=>response.json())
    .then(data=>{
        const html = data.content;
        const mainCss = data.mainCss;
        const sectionCss = data.sectionCss;

        //builder.loadHtml(html); // Load html
        builder.loadStyles(mainCss, sectionCss); // Load styles

        // For viewing the content, call pageReRender() (from box-flex.js include)
        window.pageReRender();
    });

    
    // Example of adding custom buttons
    builder.addButton({ 
        'pos': 2, // button position
        'title': 'Undo',
        'html': '<svg class="is-icon-flex" style="width:14px;height:14px;"><use xlink:href="#icon-undo"></use></svg>',
        'onClick': ()=>{
            builder.undo();
        }
    });
    builder.addButton({ 
        'pos': 3,
        'title': 'Redo',
        'html': '<svg class="is-icon-flex" style="width:14px;height:14px;"><use xlink:href="#icon-redo"></use></svg>',
        'onClick': ()=>{
            builder.redo();
        }
    });
    builder.addButton({ 
        'pos': 4, 
        'title': 'Animation',
        'html': '<svg class="is-icon-flex" style="fill:rgba(0, 0, 0, 0.7);width:14px;height:14px;"><use xlink:href="#icon-wand"></use></svg>',
        'onClick': ()=>{
            builder.openAnimationPanel();
        }
    });
    builder.addButton({ 
        'pos': 5,
        'title': 'Timeline Editor',
        'html': '<svg><use xlink:href="#icon-anim-timeline"></use></svg>', 
        'onClick': ()=>{
            builder.openAnimationTimeline();
        }
    });
    builder.addButton({ 
        'pos': 6,
        'title': 'AI Assistant',
        'html': '<svg class="is-icon-flex" style="width:16px;height:16px;"><use xlink:href="#icon-message"></use></svg>', 
        'onClick': (e)=>{

            builder.openAIAssistant();

        }
    });
     builder.addButton({ 
         'pos': 8,
         'title': 'Settings',
         'html': '<svg class="is-icon-flex" style="width:15px;height:15px;"><use xlink:href="#icon-settings"></use></svg>',
         'onClick': (e)=>{
             builder.openSettings(e);
         }
     });
    builder.addButton({ 
        'pos': 9,
        'title': 'Clear Content',
        'html': '<svg class="is-icon-flex"><use xlink:href="#icon-eraser"></use></svg>', 
        'onClick': (e)=>{
            builder.clear();
        }
    });
    builder.addButton({ 
        'pos': 10, 
        'title': 'Preview',
        'html': '<svg class="is-icon-flex" style="width:16px;height:16px;"><use xlink:href="#ion-eye"></use></svg>',
        'onClick': ()=>{
            // var html = builder.html();
            // localStorage.setItem('preview-html', html); 
            // var mainCss = builder.mainCss(); 
            // localStorage.setItem('preview-maincss', mainCss); 
            // var sectionCss = builder.sectionCss();
            // localStorage.setItem('preview-sectioncss', sectionCss);

            window.open('<?= $page->url ?>', '_blank').focus();
        }
    });
	builder.addButton({
         'pos': 11,
         'title': 'Revisions',
         'html': '<svg class="is-icon-flex" style="width:16px;height:16px;"><use xlink:href="#icon-list-search"></use></svg>',
         'onClick': ()=>{
				toggleDisplay();
         }
     });

	function uploadFile(e, callback) {
        const selectedFile = e.target.files[0];
        const filename = selectedFile.name;
        const reader = new FileReader();
        reader.onload = (e) => {
            let base64 = e.target.result;
            base64 = base64.replace(/^data:(.*?);base64,/, "");
            base64 = base64.replace(/ /g, '+');

            const reqBody = { image: base64, filename: filename,get:<?= json_encode($_GET); ?> };
            fetch('api/upload_expozy.php', {
                method: 'POST',
				credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify( reqBody ),
            })
            .then(response=>response.json())
            .then(data=>{
				console.log(data);
                callback(data);
            });
        }
        reader.readAsDataURL(selectedFile);
    }

    function save() {

        builder.saveImages('', function(){
           
            var iframe = document.getElementById('ifrBuilder');
            const styles = iframe.contentDocument.getElementById('tailwindCss').getElementsByTagName('style');
			const lastElement = styles[styles.length - 1];
			const lastElementString = lastElement.outerHTML;
			const revision_title = document.getElementById('revisionName').value;


			
            var html = builder.html();
			
            var mainCss = builder.mainCss(); //mainCss() returns css that defines typography style for the body/entire page.
           
			var sectionCss = builder.sectionCss(); //sectionCss returns css that define typography styles for certan section(s) on the page
           
			const reqBody = {savecontent:1, content: html, mainCss: mainCss, sectionCss: sectionCss, tailwindCss:lastElementString,revision_title:revision_title, get:<?= json_encode($_GET); ?> };
            fetch('api/savecontent.php', {
                method:'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(reqBody),
            })
            
            .then(response => {
				if (response.redirected) {
					window.location.replace(response.url);
				}
				return response.json();
			})
            .then(data=>{
					document.getElementById('saveBtn').style.display = "flex";
					document.getElementById('loaderBtn').style.display = "none";
            });

        }, function(img, base64, filename){

            // Upload image process
            const reqBody = { image: base64, filename: filename, get:<?= json_encode($_GET); ?> };
            fetch('api/upload_expozy.php', {
                method: 'POST', 
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify( reqBody ),
            })
            .then(response=>response.json())
            .then(response=>{
                const uploadedImageUrl = response.url; // get saved image url

                img.setAttribute('src', uploadedImageUrl); // set image src
            });

        });
        
    }

    /* Custom Topbar */

    const btnSave = document.querySelector('.custom-topbar .btn-save');
    btnSave.addEventListener('click', ()=>{
		saveContent();
    });



    const btnFullView = document.querySelector('.custom-topbar .btn-fullview');
    const btnDeviceDesktopLarge = document.querySelector('.custom-topbar .btn-device-desktop-large');
    const btnDeviceDesktop = document.querySelector('.custom-topbar .btn-device-desktop');
    const btnDeviceTabletLandscape = document.querySelector('.custom-topbar .btn-device-tablet-landscape');
    const btnDeviceTablet = document.querySelector('.custom-topbar .btn-device-tablet');
    const btnDeviceMobile = document.querySelector('.custom-topbar .btn-device-mobile');

    const clearActiveButtons = () => {
        btnFullView.classList.remove('on');
        btnDeviceDesktop.classList.remove('on');
        btnDeviceDesktopLarge.classList.remove('on');
        btnDeviceTabletLandscape.classList.remove('on');
        btnDeviceTablet.classList.remove('on');
        btnDeviceMobile.classList.remove('on');
    }

    btnFullView.addEventListener('click', ()=>{
        builder.setScreenMode('fullview');
        clearActiveButtons();
        btnFullView.classList.add('on');
    });

    btnDeviceDesktopLarge.addEventListener('click', ()=>{
        builder.setScreenMode('desktop-lg');
        clearActiveButtons();
        btnDeviceDesktopLarge.classList.add('on');
    });

    btnDeviceDesktop.addEventListener('click', ()=>{
        builder.setScreenMode('desktop');
        clearActiveButtons();
        btnDeviceDesktop.classList.add('on');
    });

    btnDeviceTabletLandscape.addEventListener('click', ()=>{
        builder.setScreenMode('tablet-landscape');
        clearActiveButtons();
        btnDeviceTabletLandscape.classList.add('on');
    });

    btnDeviceTablet.addEventListener('click', ()=>{
        builder.setScreenMode('tablet');
        clearActiveButtons();
        btnDeviceTablet.classList.add('on');
    });

    btnDeviceMobile.addEventListener('click', ()=>{
        builder.setScreenMode('mobile');
        clearActiveButtons();
        btnDeviceMobile.classList.add('on');
    });

    if(builder.screenMode==='fullview'){
        btnFullView.classList.add('on');
    } else if(builder.screenMode==='desktop-lg'){
        btnDeviceDesktopLarge.classList.add('on');
    } else if(builder.screenMode==='desktop'){
        btnDeviceDesktop.classList.add('on');
    } else if(builder.screenMode==='tablet-landscape'){
        btnDeviceTabletLandscape.classList.add('on');
    } else if(builder.screenMode==='tablet'){
        btnDeviceTablet.classList.add('on');
    } else if(builder.screenMode==='mobile'){
        btnDeviceMobile.classList.add('on');
    } 

    const btnDownload = document.querySelector('.custom-topbar .btn-download');
    if(btnDownload) btnDownload.addEventListener('click', ()=>{
        builder.download();
    });

 



    const btnTogglePanel = document.querySelector('.custom-topbar .btn-togglepanel');
    if(btnTogglePanel) btnTogglePanel.addEventListener('click', ()=>{
        builder.toggleEditPanel();
    });
	
	
	
	
	function saveContent(){
		document.getElementById('saveBtn').style.display = "none";
		document.getElementById('loaderBtn').style.display = "flex";
        


        var iframe = document.getElementById('ifrBuilder');

        let html = document.getElementById('headerFooterHtml').innerHTML;
        if(iframe.contentWindow.document.getElementById('headerFooter').innerHTML === ''){
            iframe.contentWindow.document.getElementById('headerFooter').innerHTML = html;
        }



        iframe.contentWindow.alpineTemplatesGen();
        iframe.contentWindow.classGen();
		// alpineTemplatesGen();
		// classGen();
		// tailwindGen();
			  timeoutId = setTimeout(function () {
				  save();
			  }, 100);
	}


	// function classGen(){
	// 	var elementsWithClassAttribute = document.querySelectorAll('[\\:class]');
	// 	debugger;
	// }
	
	// function alpineTemplatesGen() {
	// 	var templates = document.querySelectorAll("template");

	// 	var container = document.getElementById("templatesDiv");
	// 	container.innerHTML = '';


	// 	processTemplates(container, templates);
	// }

	// function processTemplates(container, templates) {
	// 	templates.forEach(function (template) {
	// 	  var templateContent = template.content;
	// 	  var div = document.createElement("div");
	// 	  div.appendChild(templateContent.cloneNode(true));

	// 	  var nestedTemplates = div.querySelectorAll("template");
	// 	  if (nestedTemplates.length > 0) {
	// 		processTemplates(div, nestedTemplates);
	// 	  }

	// 	  container.appendChild(div);
	// 	});
	// }


</script>

<!-- Required js for production --> 
<script src="box/box-flex.js"></script> <!-- Box Framework js include -->
<script src="/assets/plugins/tailwindcss.3.3.1.js"></script>
<script src="https://kit.fontawesome.com/134d7d4e2d.js" crossorigin="anonymous"></script>
<!-- <script src="/assets/plugins/tailwindcss.3.3.1.js"></script>
<script>
      tailwind.config = {
        darkMode: 'class',

      }
</script> -->
<script type="text/javascript">
  const rev = <?php echo json_encode( $editor->revisions, JSON_UNESCAPED_UNICODE)  ?>;

  function loadRevision(){
    let selectedRevision = document.getElementById('revisionsSelect').value;
    builder.loadHtml(rev[selectedRevision].object_desc);
  }

  function toggleDisplay() {
    var element = document.getElementById('openRevisionsModal');

    if (element) {
        var currentDisplayStyle = window.getComputedStyle(element).getPropertyValue('display');

        if (currentDisplayStyle === 'none') {
            // Ако има стил display: none, премахваме го
            element.style.display = '';
        } else {
            // Ако няма стил display: none, добавяме го
            element.style.display = 'none';
        }
    }
}


</script>
<div id="openRevisionsModal"  class="w-[600px] h-[400px] overflow-y-scroll  absolute top-0 right-0 z-20" style="display:none">

	<!-- Main modal -->
	<div class="  justify-center items-center w-full md:inset-0  max-h-full">
		<div class="p-4 w-full ">
			<!-- Modal content -->
			<div class="relative p-4 bg-white rounded-lg shadow ">
				<!-- Modal header -->
				<div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
					<h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Revisions
					</h3>

				</div>
				<!-- Modal body -->

					<div class="grid gap-4 mb-4 grid-cols-2 mt-3">
						<div class="col-span-2">
							<label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Revisions Name</label>
							<input value="<?php echo $editor->title ?>" type="text" name="revisionName" id="revisionName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
						</div>

						<div class="col-span-2 sm:col-span-1">
							<label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Revisions</label>
							<select id="revisionsSelect" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
							  <?php
                             
							  foreach ($editor->revisions as $key => $revision) {
								?>
								  <option value="<?php echo $key?>" ><?php echo $revision['title'] ?> : <?php echo $revision['date_created'] ?></option>

								<?php
								}
							   ?>

							</select>
						</div>

					</div>
					<button onclick="loadRevision()"  type="button" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
						<svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
						Load Revisions
					</button>

			</div>
		</div>
	</div>

</div>
</body>
</html>