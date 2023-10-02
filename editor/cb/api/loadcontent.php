<?php
require_once 'config.php';

session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if(empty($_SESSION['content'])) {

        echo json_encode(['content' => '<div class="is-section is-box is-section-100 type-poppins">
            <div class="is-overlay">
            </div>
            <div class="is-container v2 is-content-640 size-17 leading-13">
                <div class="row">
                    <div class="column full">
                        <p class="uppercase size-14 tracking-400 text-left">Hello, This is Company-Name</p>
                    </div>
                </div>
                <div class="row">
                    <div class="column full">
                        <div class="spacer height-40"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="column full">
                        <h1 class="leading-none text-left size-64 font-semibold">We create simple and effective designs.</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="column full">
                        <div class="spacer height-40"></div>
                    </div>
                </div>
                <div class="row">
                    <div style="width: 84%; flex: 0 0 auto;" class="column half">
                        <p class="text-left">Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s,
                            when an unknown printer took a galley of type.</p>
                    </div>
                    <div style="width: 100%;" class="column half">
                        <div class="spacer height-20"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="column full">
                        <div class="spacer height-40"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="column full">
                        <div class="text-left">
                            <a href="#" role="button" class="transition-all inline-block cursor-pointer no-underline border-2 border-solid mr-1 mt-2 mb-2 uppercase py-2 size-14 px-8 border-current hover:border-current font-normal leading-relaxed ml-0 rounded-full tracking-125" title="" onmouseover="if(this.getAttribute(\'data-hover-bg\'))this.style.backgroundColor=this.getAttribute(\'data-hover-bg\');" onmouseout="if(this.getAttribute(\'data-bg\'))this.style.backgroundColor=this.getAttribute(\'data-bg\');else this.style.backgroundColor=\'\'" style="color: rgb(0, 0, 0);">How We Work</a>
                            <a href="#" role="button" class="transition-all inline-block cursor-pointer no-underline border-2 border-solid mr-1 mt-2 mb-2 uppercase py-2 size-14 px-8 text-black ml-1 leading-relaxed rounded-full border-transparent hover:border-transparent font-normal tracking-125" title="" onmouseover="if(this.getAttribute(\'data-hover-bg\'))this.style.backgroundColor=this.getAttribute(\'data-hover-bg\');" onmouseout="if(this.getAttribute(\'data-bg\'))this.style.backgroundColor=this.getAttribute(\'data-bg\');else this.style.backgroundColor=\'\'" data-bg="rgb(240,240,240)" style="background-color: rgb(240, 240, 240);" data-hover-bg="">Get in Touch</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>', 'mainCss' => '', 'sectionCss' => '<link data-name="contentstyle" data-class="type-poppins" href="assets/styles/type-poppins.css" rel="stylesheet">']);

    } else {

        $content = $_SESSION['content'];
        $mainCss = $_SESSION['mainCss'];
        $sectionCss = $_SESSION['sectionCss'];
    
        echo json_encode(['content' => $content, 'mainCss' => $mainCss, 'sectionCss' => $sectionCss]);
    }
}
?>