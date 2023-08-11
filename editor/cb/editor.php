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
    <link href="<?= $dir ?>assets/minimalist-blocks/content.css" rel="stylesheet"> <!-- Snippets css include (contains a simple css for content blocks/snippets) -->
    <link href="<?= $dir ?>box/box-flex.css" rel="stylesheet"> <!-- Box Framework css include (contains a simple css for sections) -->

    <!-- Render save styles needed by the content - also required for production -->
    <?php
  if(!empty($_SESSION['mainCss'])) {
    echo $_SESSION['mainCss'];
  }
  if(!empty($_SESSION['sectionCss'])) {
    echo $_SESSION['sectionCss'];
  }
    ?>

    <!-- Required css for editing (not needed in production) -->
    <link href="<?= $dir ?>contentbuilder/contentbuilder.css" rel="stylesheet" type="text/css" />
    <link href="<?= $dir ?>contentbox/contentbox.css" rel="stylesheet" type="text/css" />

    <link data-name="contentstyle" data-class="type-poppins" href="<?php echo $dir ?>assets/styles/type-poppins.css" rel="stylesheet">

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


</head>
<body>

<div class="is-wrapper" style="opacity:0">
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
<div style="display: none !important;"><?php echo $page->header; echo $page->footer; ?></div>
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

// document.getElementById("saveBtn").onclick = function() {
//
//   alpineTemplatesGen();
//   tailwindGen();
//         timeoutId = setTimeout(function () {
//             save();
//         }, 100);
// };

function saveContent(){
  document.getElementById('saveBtn').style.display = "none";
  document.getElementById('loaderBtn').style.display = "block";

  alpineTemplatesGen();
  tailwindGen();
        timeoutId = setTimeout(function () {
            save();
        }, 100);
}




    var timeoutId; //Used for Auto Save

    //Enable editing
    const builder = new ContentBox({

        wrapper: '.is-wrapper',

        templates: [
            {
                url: '<?= $dir ?>assets/simplestart/templates.js',
                path: '<?= $dir ?>assets/simplestart/',
                pathReplace: []
            },
            {
                url: '<?= $dir ?>assets/quickstart/templates.js',
                path: '<?= $dir ?>assets/quickstart/',
                pathReplace: []
            },
            {
                url: '<?= $dir ?>assets/animated/templates.js',
                path: '<?= $dir ?>assets/animated/',
                pathReplace: []
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

        previewURL: 'preview.html',

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

        // Upload using Form Method:
        // coverImageHandler: 'savecover.php', // for uploading box background
        // imageHandler: 'savemedia.php', // for uploading image
        // videoHandler: 'savemedia.php', // for uploading video
        // audioHandler: 'savemedia.php', // for uploading audio
        // mediaHandler: 'savemedia.php', // for uploading image or video
        // fileHandler: 'savemedia.php', // for uploading file

        // Upload using AJAX Method:
        // onImageUpload: function(e){},
        // onVideoUpload: function(e){},
        // onAudioUpload: function(e){},
        // onMediaUpload: function(e){},
        // onFileUpload: function(e){},

        slider: 'glide',
        navbar: true,
        zoom: 0.6,

        // AJAX Method:
        onUploadCoverImage: (e) => {

            uploadFile(e, (response)=>{
                if(!response.data) {
                    console.log(response);
                    return;
                }
                const uploadedImageUrl = response.data.url; // get saved image url
                if(uploadedImageUrl) builder.boxImage(uploadedImageUrl); // change cover image
            });
        },
        onMediaUpload: (e)=>{
            uploadFile(e, (response)=>{
                if(!response.data) {
                    console.log(response);
                    return;
                }
                const uploadedImageUrl = response.data.url; // get saved file url
                if(uploadedImageUrl) builder.returnUrl(uploadedImageUrl); // apply
            });
        },
        onVideoUpload: (e)=>{
            uploadFile(e, (response)=>{
                if(!response.data) {
                    console.log(response);
                    return;
                }
                const uploadedFileUrl = response.data.url; // get saved file url
                if(uploadedImageUrl) builder.returnUrl(uploadedFileUrl); // apply
            });
        },

        onChange: function () {
            //Auto Save
            clearTimeout(timeoutId);
					       //save();
			// alpineTemplatesGen();
			// tailwindGen();
      //       timeoutId = setTimeout(function () {
      //           save();
      //       }, 100);
        },

        /* ContentBox settings */
        // designUrl1: 'assets/designs/basic.js',
        // designUrl2: 'assets/designs/examples.js',
        // designPath: 'assets/designs/',
        contentStylePath: '<?php echo $dir ?>assets/styles/',

        /* ContentBuilder settings */
        // modulePath: 'assets/modules/',
        fontAssetPath: '<?php echo $dir ?>assets/fonts/',
        assetPath: '<?php echo $dir ?>assets/',
        // snippetUrl: 'assets/minimalist-blocks/content.js',
        // snippetPath: 'assets/minimalist-blocks/',
        // pluginPath: 'contentbuilder/',
        // useLightbox: true,
    });

    localStorage.removeItem('mypage'); // clear saved content

    // Load saved content. In this example we use browser's localStorage.
    let html = localStorage.getItem('mypage') || '';
    let mainCss = localStorage.getItem('maincss') || '';
    let sectionCss = localStorage.getItem('sectioncss') || '';
    if(html!=='') {
        builder.loadStyles(mainCss, sectionCss); // Load styles
        builder.loadHtml(html); // Load html
    }


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
            var html = builder.html();
            localStorage.setItem('preview-html', html);
            var mainCss = builder.mainCss();
            localStorage.setItem('preview-maincss', mainCss);
            var sectionCss = builder.sectionCss();
            localStorage.setItem('preview-sectioncss', sectionCss);

            window.open('preview.html', '_blank').focus();
        }
    });

    builder.addButton({
        'pos': 8,
        'title': 'Save',
        'html': '<i class="fa-regular fa-floppy-disk" id="saveBtn" style="font-size: 18px !important;"></i> <i class="fa-solid fa-spinner fa-spin" id="loaderBtn" style="font-size: 18px !important; display:none;"></i>', // icon
        'onClick': ()=>{
            saveContent();
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
            fetch('upload_expozy.php', {
                method: 'POST',
				credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify( reqBody ),
            })
            .then(response=>response.json())
            .then(data=>{
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


          var html = builder.html();
          var mainCss = builder.mainCss(); //mainCss() returns css that defines typography style for the body/entire page.
          var sectionCss = builder.sectionCss(); //sectionCss returns css that define typography styles for certan section(s) on the page

          const reqBody = {savecontent:1, content: html, mainCss: mainCss, sectionCss: sectionCss, tailwindCss:lastElementString, get:<?= json_encode($_GET); ?> };
          fetch('../post.php', {
              method:'POST',
			  credentials: 'same-origin',
              headers: {
                  'Content-Type': 'application/json'
              },
              body: JSON.stringify(reqBody),
          })

          .then(response=>response.json())
          .then(data=>{
            document.getElementById('saveBtn').style.display = "block";
            document.getElementById('loaderBtn').style.display = "none";
          });

      }, function(img, base64, filename){

          // Upload image process
          const reqBody = { image: base64, filename: filename, get:<?= json_encode($_GET); ?> };
          fetch('upload_expozy.php', {
              method: 'POST',
      credentials: 'same-origin',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify( reqBody ),
          })
          .then(response=>response.json())
          .then(response=>{
              const uploadedImageUrl = response.data.url; // get saved image url

              img.setAttribute('src', uploadedImageUrl); // set image src
          });

      });
    }

function alpineTemplatesGen(){
	var templates = document.querySelectorAll("template");

      var container = document.getElementById("templatesDiv");
      templates.forEach(function(template, index) {
        var templateContent = template.innerHTML;
        var div = document.createElement("div");
        div.innerHTML = templateContent;
        container.appendChild(div);
      });
}
</script>

<!-- Required js for production -->
<script src="<?= $dir ?>box/box-flex.js"></script> <!-- Box Framework js include -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Optional: if you want to add smooth scrolling -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/smoothscroll/1.4.10/SmoothScroll.min.js"></script>
<script>
SmoothScroll({
    frameRate: 150,
    animationTime: 800,
    stepSize: 120,
    pulseAlgorithm: 1,
    pulseScale: 4,
    pulseNormalize: 1,
    accelerationDelta: 300,
    accelerationMax: 2,
    keyboardSupport: 1,
    arrowScroll: 50,
    fixedBackground: 0
});
</script>
<script src="/assets/plugins/tailwindcss.3.3.1.js"></script>
</body>
</html>
