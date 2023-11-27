<?php

if(session_id() == '') session_start();

$parameters = json_decode(base64_decode($_GET['i'] ?? ''), true);

if(isset($parameters['token'])){ $_SESSION['token'] = $parameters['token'];}

if(!isset($_GET['i'])) die();

define( "_VALID_PHP", true);
require_once '../../core/autoload.php';


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
	<script src="https://kit.fontawesome.com/134d7d4e2d.js" crossorigin="anonymous"></script>
	<!-- Render save styles needed by the content - also required for production -->
    <?php
  if(!empty($_SESSION['mainCss'])) {
    echo $_SESSION['mainCss'];
  }
  if(!empty($_SESSION['sectionCss'])) {
    echo $_SESSION['sectionCss'];
  }
    ?>

    <!-- СТИЛ ЗА ЗАКЛЮЧЕНИТЕ СЕКЦИИ -->
    <style media="screen">
    .is-section.lock{
      background-image: url(/editor/cb/assets/images/lock2.png) !important;
      background-size: contain!important;
      background-repeat: no-repeat!important;
      background-position: center!important;
      background-color: white !important;
      width: 100% !important;
      max-width: 100% !important;

      min-height: unset !important;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
      margin-bottom: 10px !important;
      aspect-ratio: 5 / 1;
    }

    .is-section.lock > div{
      opacity: 0 !important;
    }

    </style>

    <!-- Required css for editing (not needed in production) -->
    <link href="<?= $dir ?>contentbuilder/contentbuilder.css" rel="stylesheet">
    <link href="<?= $dir ?>contentbox/contentbox.css" rel="stylesheet">
</head>
<body x-data="{openRevisionsModal:false}">

<div class="is-wrapper" style="opacity:0" id="mainContent">
  <?php

  if(empty($editor->html)) {
  	echo '';
  }else {
    echo $editor->html;

  }
   ?>
</div>

<div style="display: none;" id="tailwindCss"></div>
<div style="display: none;" id="templatesDiv"></div>
<div style="display: none !important;">
<?php
		if($page->type == 'header'){
			echo $page->footer;

		} else if($page->type == 'footer'){
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

        previewURL: 'preview.html',

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
        upscaleImageUrl: 'api/upload_expozy.php',
        imageAutoUpscale: true,

        templates: [
            {
                url: 'assets/templates-simple/templates.js',
                path: 'assets/templates-simple/',
                pathReplace: [],
                numbering: true,
                showNumberOnHover: true,
            },
            {
                url: 'assets/templates-quick/templates.js',
                path: 'assets/templates-quick/',
                pathReplace: [],
                numbering: true,
                showNumberOnHover: true,
            },
            {
                url: 'assets/templates-animated/templates.js',
                path: 'assets/templates-animated/',
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
        mediaSelect: 'assets.html', // for image & video

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
            clearTimeout(timeoutId);
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
//    fetch('api/loadcontent.php', {
//        method:'GET',
//        headers: {
//            'Content-Type': 'application/json'
//        }
//    })
//    .then(response=>response.json())
//    .then(data=>{
//        const html = data.content;
//        const mainCss = data.mainCss;
//        const sectionCss = data.sectionCss;
//
//        builder.loadHtml(html); // Load html
//        builder.loadStyles(mainCss, sectionCss); // Load styles
//
//        // For viewing the content, call pageReRender() (from box-flex.js include)
//        window.pageReRender();
//    });

    // Example of adding custom buttons
    builder.addButton({
        'pos': 2, // button position
        'title': 'Undo',
        'html': '<svg class="is-icon-flex" style="width:14px;height:14px;"><use xlink:href="#ion-ios-undo"></use></svg>', // icon
        'onClick': ()=>{
            builder.undo();
        }
    });
    builder.addButton({
        'pos': 3,
        'title': 'Redo',
        'html': '<svg class="is-icon-flex" style="width:14px;height:14px;"><use xlink:href="#ion-ios-redo"></use></svg>', // icon
        'onClick': ()=>{
            builder.redo();
        }
    });
    builder.addButton({
        'pos': 5,
        'title': 'Animation',
        'html': '<svg class="is-icon-flex" style="fill:rgba(0, 0, 0, 0.7);width:14px;height:14px;"><use xlink:href="#icon-wand"></use></svg>', // icon
        'onClick': ()=>{
            builder.openAnimationPanel();
        }
    });
    builder.addButton({
        'pos': 6,
        'title': 'Settings',
        'html': '<svg class="is-icon-flex" style="width:15px;height:15px;"><use xlink:href="#icon-settings"></use></svg>', // icon
        'onClick': ()=>{
            builder.openSettings();
        }
    });
    builder.addButton({
        'pos': 7,
        'title': 'Preview',
        'html': '<svg class="is-icon-flex" style="width:16px;height:16px;"><use xlink:href="#ion-eye"></use></svg>', // icon
        'onClick': ()=>{

            var sHTML = builder.html();
            localStorage.setItem('preview-html', sHTML);
            var sMainCss = builder.mainCss();
            localStorage.setItem('preview-maincss', sMainCss);
            var sSectionCss = builder.sectionCss();
            localStorage.setItem('preview-sectioncss', sSectionCss);

            window.open('preview.html', '_blank').focus();
        }
    });
    builder.addButton({
        'pos': 8,
        'title': 'AI Assistant',
        'html': '<svg class="is-icon-flex"><use xlink:href="#icon-microphone"></use></svg>',
        'onClick': (e)=>{

            builder.openAIAssistant();

        }
    });

    builder.addButton({
         'pos': 9,
         'title': 'Revisions',
         'html': '<i @click="openRevisionsModal =! openRevisionsModal" class="fa-solid fa-hard-drive" id="revisions" style="font-size: 18px !important;"></i> ',
         'onClick': ()=>{

         }
     });

	 builder.addButton({
        'pos': 10,
        'title': 'Save',
        'html': '<i class="fa-regular fa-floppy-disk" id="saveBtn" style="font-size: 18px !important;"></i> <i class="fa-solid fa-spinner fa-spin" id="loaderBtn" style="font-size: 18px !important; display:none;"></i>', // icon
        'onClick': ()=>{
            saveContent();
        }
    });

//    function uploadFile(e, callback) {
//
//        const selectedFile = e.target.files[0];
//        const filename = selectedFile.name;
//
//        const formData = new FormData();
//        formData.append('file', selectedFile);
//
//
//         fetch('api/uploadfile.php', {
//            method: 'POST',
//            body: formData,
//			headers: {
//                    'Content-Type': 'application/json',
//                },
//        })
//        .then(response=>response.json())
//        .then(response=>{
//            if(callback) callback(response);
//        });
//
//    }
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

			const styles = document.getElementById('tailwindCss').getElementsByTagName('style');
			const lastElement = styles[styles.length - 1];
			const lastElementString = lastElement.outerHTML;
      const revision_title = document.getElementById('revisionName').value;


            var html = builder.html();
            var mainCss = builder.mainCss(); //mainCss() returns css that defines typography style for the body/entire page.
            var sectionCss = builder.sectionCss(); //sectionCss returns css that define typography styles for certan section(s) on the page

          const reqBody = {savecontent:1, content: html, mainCss: mainCss, sectionCss: sectionCss, tailwindCss:lastElementString,revision_title:revision_title, get:<?= json_encode($_GET); ?> };
            fetch('api/post.php', {
                method:'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(reqBody),
            })

            .then(response=>response.json())
            .then(data=>{

            });
            document.getElementById('saveBtn').style.display = "block";
            document.getElementById('loaderBtn').style.display = "none";

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

	function saveContent(){
		document.getElementById('saveBtn').style.display = "none";
		document.getElementById('loaderBtn').style.display = "block";

		alpineTemplatesGen();
		tailwindGen();
			  timeoutId = setTimeout(function () {
				  save();
			  }, 100);
	}

	function alpineTemplatesGen() {
		var templates = document.querySelectorAll("template");

		var container = document.getElementById("templatesDiv");
		container.innerHTML = '';


		processTemplates(container, templates);
	}

	function processTemplates(container, templates) {
		templates.forEach(function (template) {
		  var templateContent = template.content;
		  var div = document.createElement("div");
		  div.appendChild(templateContent.cloneNode(true));

		  var nestedTemplates = div.querySelectorAll("template");
		  if (nestedTemplates.length > 0) {
			processTemplates(div, nestedTemplates);
		  }

		  container.appendChild(div);
		});
	}




</script>

<!-- Required js for production -->
<script src="<?= $dir ?>box/box-flex.js"></script> <!-- Box Framework js include -->
<script src="/assets/plugins/tailwindcss.3.3.1.js"></script>
<script type="module" src="\assets\plugins\alpinejs\alpine.js"></script>
<script type="text/javascript">
  const rev = <?php echo json_encode( $editor->revisions, JSON_UNESCAPED_UNICODE)  ?>;

  function loadRevision(){
    let selectedRevision = document.getElementById('revisionsSelect').value;

    builder.loadHtml(rev.result[selectedRevision].object_desc);

  }

</script>

<div x-show="openRevisionsModal" class="w-[600px] h-[400px] overflow-y-scroll  absolute top-0 right-0" style="display:none">

<!-- Main modal -->
<div class="  justify-center items-center w-full md:inset-0  max-h-full">
    <div class="p-4 w-full ">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow ">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Ревизии
                </h3>

            </div>
            <!-- Modal body -->

                <div class="grid gap-4 mb-4 grid-cols-2 mt-3">
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Име на ревизия</label>
                        <input value="<?php echo $editor->title . ' '. date("Y-m-d"); ?>" type="text" name="revisionName" id="revisionName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Type product name" required="">
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ревизии</label>
                        <select id="revisionsSelect" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                          <?php
                          foreach ($editor->revisions['result'] as $key => $revision) {
                            ?>
                              <option value="<?php echo $key?>" ><?php echo $revision['title'] ?></option>

                            <?php
                            }
                           ?>

                        </select>
                    </div>

                </div>
                <button onclick="loadRevision()"  type="button" class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Зареди ревизия
                </button>

        </div>
    </div>
</div>

</div>

</body>
</html>
