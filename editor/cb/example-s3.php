<?php
session_start();
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>Default Example</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <link rel="shortcut icon" href="#"> 
    
    <!-- Required css for production -->    
    <link href="assets/minimalist-blocks/content.css" rel="stylesheet"> 
    <link href="box/box-flex.css" rel="stylesheet"> 

    <!-- Required css for editing (not needed in production) -->   
    <link href="contentbuilder/contentbuilder.css" rel="stylesheet">
    <link href="contentbox/contentbox.css" rel="stylesheet">
</head>
<body>

<div class="is-wrapper" style="opacity:0">
</div>

<!-- Slider feature (by setting slider: 'glide') -->
<link href="assets/scripts/glide/css/glide.core.css" rel="stylesheet">
<link href="assets/scripts/glide/css/glide.theme.css" rel="stylesheet">
<script src="assets/scripts/glide/glide.js"></script>

<!-- Navbar feature (by setting navbar: true) -->
<link href="assets/scripts/navbar/navbar.css" rel="stylesheet">
<script src="assets/scripts/navbar/navbar.min.js"></script>

<!-- Required js for editing (not needed in production) -->  
<script src="contentbox/lang/en.js"></script>
<script src="contentbox/contentbox.min.js"></script>

<script>
	
    var timeoutId; //Used for Auto Save

    //Enable editing
    const builder = new ContentBox({
        
        canvas: true,
        previewURL: 'preview.html',

        controlPanel: true,
        iframeSrc: 'blank.html',
        zoom: 1,
        screenMode: 'desktop', // or fullview

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
        textToImageUrl: 'api/texttoimage-s3.php', 
        upscaleImageUrl: 'api/upscaleimage-s3.php',
        imageAutoUpscale: true,
        viewFileUrl: 'api/viewfile.php', // Used if using S3 (public bucket).
        
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
            clearTimeout(timeoutId);
            timeoutId = setTimeout(function () {
                save();                    
            }, 1000);
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

        builder.loadHtml(html); // Load html
        builder.loadStyles(mainCss, sectionCss); // Load styles

        // For viewing the content, call pageReRender() (from box-flex.js include)
        window.pageReRender();
    });

    
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
        'pos': 4, 
        'title': 'Animation',
        'html': '<svg class="is-icon-flex" style="fill:rgba(0, 0, 0, 0.7);width:14px;height:14px;"><use xlink:href="#icon-wand"></use></svg>', // icon
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
    // builder.addButton({ 
    //     'pos': 7,
    //     'title': 'Settings',
    //     'html': '<svg class="is-icon-flex" style="width:15px;height:15px;"><use xlink:href="#icon-settings"></use></svg>', // icon
    //     'onClick': (e)=>{
    //         builder.openSettings(e);
    //     }
    //     });
    builder.addButton({ 
        'pos': 8,
        'title': 'Clear Content',
        'html': '<svg class="is-icon-flex"><use xlink:href="#icon-eraser"></use></svg>', 
        'onClick': (e)=>{
            builder.clear();
        }
    });
    builder.addButton({ 
        'pos': 9, 
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

    function uploadFile(e, callback) {

        const selectedFile = e.target.files[0];
        const filename = selectedFile.name;

        const formData = new FormData();
        formData.append('file', selectedFile);
        fetch('api/uploadfile-s3.php', {
            method: 'POST',
            body: formData
        })
        .then(response=>response.json())
        .then(response=>{
            if(callback) callback(response);
        });

    }

    function save() {

        builder.saveImages('', function(){
      
            var html = builder.html();
            var mainCss = builder.mainCss(); //mainCss() returns css that defines typography style for the body/entire page.
            var sectionCss = builder.sectionCss(); //sectionCss returns css that define typography styles for certan section(s) on the page
            
            const reqBody = { content: html, mainCss: mainCss, sectionCss: sectionCss };
            fetch('api/savecontent.php', {
                method:'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(reqBody),
            })
            
            .then(response=>response.json())
            .then(data=>{
 
            });

        }, function(img, base64, filename){

            // Upload image process
            const reqBody = { image: base64, filename: filename };
            fetch('api/uploadbase64-s3.php', {
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
</script>

<!-- Required js for production --> 
<script src="box/box-flex.js"></script> <!-- Box Framework js include -->

</body>
</html>
