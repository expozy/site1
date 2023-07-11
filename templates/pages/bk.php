<div class="is-section is-box bg-neutral-100 py-5 shadow-xl w-full is-section-auto !h-[100px] !min-h-unset">
    <div class="flex justify-between items-center container px-10 is-boxex is-container">

        <div class="flex items-center gap-5">
            <i class="fa-solid fa-truck-fast text-[26px]"></i>
            <div class="is-box">

                <p class="text-base font-bold">Безплатна доставка</p>
                <p class="text-base">Безплатна доставка над 100 лв.</p>
            </div>

        </div>
        <div class="flex items-center gap-5 hidden lg:block">
            <i class="fa-solid fa-phone-volume text-[26px]"></i>
            <div>
                <p class="text-base font-bold">Обадете ни се </p>
                <p class="text-base">0888 039 850</p>
            </div>

        </div>
        <div class="flex items-center gap-5">
            <i class="fa-regular fa-circle-check text-[26px]"></i>
            <div>
                <p class="text-base font-bold">Гарантирано </p>
                <p class="text-base">Качество и произход</p>
            </div>

        </div>
        <div class="flex items-center gap-5">
            <i class="fa-solid fa-repeat text-[26px]"></i>
            <div>
                <p class="text-base font-bold">Обратна връзка</p>
                <p class="text-base">Възможност за обратна връзка с нас</p>
            </div>

        </div>
    </div>
</div>

<div class="!mb-[100px] container is-section is-section-auto" style=" background-color: unset;">
    <div>
        <p class="text-xl font-bold pt-[100px] pb-[50px]">Най-продавани продукти : </p>
        <div class="container grid grid-cols-4 gap-10" apidata="Shop.get_products" data-limit="4" keyname="featured" data-only_featured="true">
            <template x-for="product in data.featured.result">

                <div class="relative text-center shadow-xl flex flex-col justify-between  bg-neutral-100 " x-data="{hover:false}" @mouseenter="hover = true" @mouseleave="hover = false">

                    <template x-if="product.variations[0].promoprice != 0">
                        <div class="ribbon ribbon-secondary ribbon-clip !top-[10px]">Sale</div>
                    </template>
                    <div x-show="product.isNew" x-cloak="">
                        <div class="ribbon ribbon-success ribbon-clip " :class="product.variations[0].promoprice != 0 ? '!top-[55px]': '!top-[10px]'">New</div>
                    </div>

                    <div class="absolute top-4 right-4 z-40" x-data="{wishlist: product.isWishlisted}">
                        <template x-if="!wishlist">
                            <i class="fa-regular fa-heart text-[22px] cursor-pointer drop-shadow-xl hover:text-red-600" @click="alpineListeners('Shop.post_wishlist', $event); wishlist =! wishlist" :data-product_id="product.id"></i>
                        </template>

                        <template x-if="wishlist">
                            <i class="fa-solid fa-heart text-red-600 fa-heart text-[22px] cursor-pointer drop-shadow-xl hover:text-red-600" @click="alpineListeners('Shop.delete_wishlist', $event); wishlist =! wishlist" :data-product_id="product.id"></i>
                        </template>

                    </div>

                    <div class="p-10 hover:p-0 h-[300px] bg-white">
                        <img :src="product.images[0].image" @click="forceChange(product.url)" alt="" class="!m-0 w-full h-full object-scale-down cursor-pointer">
                    </div>

                    <div class="px-3 ">
                        <p x-text="product.title" @click="forceChange(product.url)" class="pt-[15px] line-clamp-2 cursor-pointer hover:text-orange-400">Deba Fuji Ryutoku, Деба нож, 15 см, FC-572</p>
                        <template x-if="product.variations[0].promoprice != 0">
                            <div class="flex items-center justify-center py-1">
                                <p class="text-lg font-bold text-red-400" x-text="product.variations[0].selling_price">750</p>
                                <span class="text-base ml-2 text-red-400" x-text="product.variations[0].currency">лв.</span>
                                <p class="text-base font-bold">
                                    <s>
                                        <span x-text="product.variations[0].price">890</span>
                                        <span x-text="product.variations[0].currency">лв.</span>
                                    </s>
                                </p>

                            </div>
                        </template>

                        <template x-if="product.variations[0].promoprice == 0">
                            <p x-text="product.variations[0].price + ' лв.'" class=" py-1 text-base font-bold">80.00 BGN</p>
                        </template>
                    </div>

                    <div class="px-3 py-3 w-full">
                        <button @click="alpineListeners('Shop.post_carts', $event)" :data-product_id="product.id" :data-variations_id="product.variations[0].id" :data-qty="1" name="button" class=" w-full py-2 bg-neutral-700 text-white hover:bg-neutral-100 hover:text-neutral-700 border-2 border-neutral-600">Купи</button>
                    </div>

                </div>
            </template>
        </div>
    </div>
</div>

<div class="!bg-neutral-100 !py-[100px] w-full is-section is-section-auto" apidata="Blog.get_blogPosts" keyname="latestBlog" data-limit="3">
    <div class="container">
        <div class="grid grid-cols-3 gap-10">
            <template x-for="blog in data.latestBlog.result">

                <div class="flex flex-col  items-center relative bg-white shadow-2xl p-8">
                    <a :href="blog.url">
                        <img :src="blog.images[0].url" alt="" class="h-[400px] w-full object-cover !m-0">
                    </a>
                    <div class="flex items-between flex-col mt-4">
                        <a :href="blog.url">
                            <p class="text-lg font-bold line-clamp-2 " x-text="blog.title">Японски нож на готвача Сантоку - как се използва?</p>
                        </a>
                        <a :href="blog.url">
                            <p class="text-base pt-2 line-clamp-4" x-text="blog.seo_description"> Сантоку е японската версия на европейския нож на готвача. Докато готварския нож в Европа се разпознава по заострения си връх и изпъкнал ръб, Сантоку има леко по-заоблена форма на острието и прав ръб. </p>
                        </a>
                    </div>
                </div>
            </template>

        </div>
    </div>
</div>

<div class="is-section is-box is-align-left is-light-text type-opensans is-section-20 bg-neutral-100 !my-[50px]">
    <div class="is-overlay container">
        <div class="is-overlay-content content-selectable " data-dialog-width="1200px" data-dialog-height="900px" data-module="slider-box" data-module-desc="Slider" data-html="xlAg3dX" data-settings="%7B%22type%22%3A%20%22carousel%22%2C%22autoplay%22%3A%20%22false%22%2C%22animationDuration%22%3A300%2C%22gap%22%3A%200%2C%22perView%22%3A4%2C%22arrow%22%3Atrue%2C%22arrowPreset%22%3A1%2C%22arrowColor%22%3A%20%22%22%2C%22dots%22%3Afalse%2C%22dotsColor%22%3A%20%22%22%2C%22mobileArrows%22%3Afalse%2C%22mobileDots%22%3Afalse%2C%22fit%22%3A%20%22contain%22%2C%22hoverPause%22%3Afalse%2C%22captionAnim%22%3A%20%22appear%22%2C%22images%22%3A%5B%7B%22src%22%3A%20%22https%3A%2F%2Fstorage.de-fra1.upcloudobjects.com%2Fexpozy%2Ffrontend%2Fcontbuilder%2Findex%2Fp-7-i-FRvGIVg5JEQBQDemJCLQ.webp%22%2C%20%22caption%22%3A%20%22%22%2C%20%22style%22%3A%20%22%22%7D%2C%7B%22src%22%3A%20%22https%3A%2F%2Fstorage.de-fra1.upcloudobjects.com%2Fexpozy%2Ffrontend%2Fcontbuilder%2Findex%2Fp-13-i-GUzsHkXwlbhvm7tsc9iQ.webp%22%2C%20%22caption%22%3A%20%22%22%2C%20%22style%22%3A%20%22%22%7D%2C%7B%22src%22%3A%20%22https%3A%2F%2Fstorage.de-fra1.upcloudobjects.com%2Fexpozy%2Ffrontend%2Fcontbuilder%2Findex%2Fp-15-i-1KUlHcmBDxScer5PeR9f.webp%22%2C%20%22caption%22%3A%20%22%22%2C%20%22style%22%3A%20%22%22%7D%2C%7B%22src%22%3A%20%22https%3A%2F%2Fstorage.de-fra1.upcloudobjects.com%2Fexpozy%2Ffrontend%2Fcontbuilder%2Findex%2Fp-16-i-mLJXDgmYQdFa5vDpLQCe.webp%22%2C%20%22caption%22%3A%20%22%22%2C%20%22style%22%3A%20%22%22%7D%2C%7B%22src%22%3A%20%22https%3A%2F%2Fstorage.de-fra1.upcloudobjects.com%2Fexpozy%2Ffrontend%2Fcontbuilder%2Findex%2Fp-17-i-wGFsdPPesPQ0h1Ls7r4I.webp%22%2C%20%22caption%22%3A%20%22%22%2C%20%22style%22%3A%20%22%22%7D%5D%7D">
            <div id="_style_ThPWpZ0" style="display:none">

            </div>
            <div id="ThPWpZ0" class="glide contain " style="display:none">
                <div data-glide-el="track" class="glide__track">
                    <ul class="glide__slides">
                        <li class="glide__slide" style="height:100%;"><img src="https://storage.de-fra1.upcloudobjects.com/expozy/frontend/contbuilder/index/p-7-i-FRvGIVg5JEQBQDemJCLQ.webp" alt=""></li>
                        <li class="glide__slide" style="height:100%;"><img src="https://storage.de-fra1.upcloudobjects.com/expozy/frontend/contbuilder/index/p-13-i-GUzsHkXwlbhvm7tsc9iQ.webp" alt=""></li>
                        <li class="glide__slide" style="height:100%;"><img src="https://storage.de-fra1.upcloudobjects.com/expozy/frontend/contbuilder/index/p-15-i-1KUlHcmBDxScer5PeR9f.webp" alt=""></li>
                        <li class="glide__slide" style="height:100%;"><img src="https://storage.de-fra1.upcloudobjects.com/expozy/frontend/contbuilder/index/p-16-i-mLJXDgmYQdFa5vDpLQCe.webp" alt=""></li>
                        <li class="glide__slide" style="height:100%;"><img src="https://storage.de-fra1.upcloudobjects.com/expozy/frontend/contbuilder/index/p-17-i-wGFsdPPesPQ0h1Ls7r4I.webp" alt=""></li>
                    </ul>
                </div>

                <div class="glide__arrows" data-glide-el="controls"><button class="glide__arrow glide__arrow--left" data-glide-dir="<"><svg style="width:4.3vw;height:4.3vw;min-width:30px;min-height:30px;" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                            <path d="M352 115.4L331.3 96 160 256l171.3 160 20.7-19.3L201.5 256z"></path>
                        </svg></button><button class="glide__arrow glide__arrow--right" data-glide-dir=">"><svg style="width:4.3vw;height:4.3vw;min-width:30px;min-height:30px;" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                            <path d="M160 115.4L180.7 96 352 256 180.7 416 160 396.7 310.5 256z"></path>
                        </svg></button></div>

            </div>
            <script>
                var css = document.querySelector('#_style_ThPWpZ0').innerHTML;
                var head = document.getElementsByTagName('head')[0];
                var s = document.createElement('style');
                s.appendChild(document.createTextNode(css));
                head.appendChild(s);

                var svgDef = '<svg width="0" height="0" style="position:absolute;display:none;">' +
                    '<defs>' +
                    '<symbol viewBox="0 0 512 512" id="ion-ios-arrow-left">' +
                    '<path d="M352 115.4L331.3 96 160 256l171.3 160 20.7-19.3L201.5 256z"></path>' +
                    '</symbol>' +
                    '<symbol viewBox="0 0 512 512" id="ion-ios-arrow-right">' +
                    '<path d="M160 115.4L180.7 96 352 256 180.7 416 160 396.7 310.5 256z"></path>' +
                    '</symbol>' +
                    '</defs>' +
                    '</svg>';
                var pre = document.querySelector('#ion-ios-arrow-left');
                if (!pre) {
                    document.body.insertAdjacentHTML('beforeend', svgDef);
                }

                var docReady = function(fn) {
                    var stateCheck = setInterval(function() {
                        // if (typeof Glide === 'undefined') return;
                        var waitSlider = false;
                        if (typeof Glide !== 'undefined') {
                            if ((new Glide).mount) {
                                // Do Nothing
                            } else {
                                waitSlider = true;
                            }
                        } else {
                            waitSlider = true;
                        }
                        if (waitSlider) return;
                        if (typeof skrollrr === 'undefined') return;
                        if (typeof skrollrr.lax === 'undefined') return;

                        clearInterval(stateCheck);
                        try {
                            fn();
                        } catch (e) {}
                    }, 1);
                };
                docReady(function() {
                    const glideSlide = document.querySelector("#ThPWpZ0");
                    if (!glideSlide) return;
                    glideSlide.style.display = "";

                    const glideSlides = document.querySelectorAll('#ThPWpZ0 .glide__slide');

                    const perView = 4;

                    glideSlides.forEach(slide => {
                        let video = slide.querySelector('video');
                        if (video) changeVideo(video);
                    });

                    window.addEventListener('resize', () => {
                        glideSlides.forEach(slide => {
                            let video = slide.querySelector('video');
                            if (video) changeVideo(video);
                        });
                    });

                    function changeVideo(video) {
                        if (!video) return;
                        let changed = false;
                        let source = video.querySelector('source');
                        let vidDefault = source.getAttribute('data-default');
                        let vid240 = source.getAttribute('data-240');
                        let vid360 = source.getAttribute('data-360');
                        let vid480 = source.getAttribute('data-480');
                        let vid540 = source.getAttribute('data-540');
                        let vid720 = source.getAttribute('data-720');
                        let vid1080 = source.getAttribute('data-1080');
                        let vid1440 = source.getAttribute('data-1440');
                        let vid2160 = source.getAttribute('data-2160');
                        let vW = window.innerWidth;
                        if (vW <= 426) {
                            if (vid240) {
                                if (source.getAttribute('src') !== vid240) {
                                    source.setAttribute('src', vid240);
                                    changed = true;
                                } else return;
                            } else if (vid360) {
                                if (source.getAttribute('src') !== vid360) {
                                    source.setAttribute('src', vid360);
                                    changed = true;
                                } else return;
                            } else if (vid480) {
                                if (source.getAttribute('src') !== vid480) {
                                    source.setAttribute('src', vid480);
                                    changed = true;
                                } else return;
                            } else if (vid540) {
                                if (source.getAttribute('src') !== vid540) {
                                    source.setAttribute('src', vid540);
                                    changed = true;
                                } else return;
                            } else if (vid720) {
                                if (source.getAttribute('src') !== vid720) {
                                    source.setAttribute('src', vid720);
                                    changed = true;
                                } else return;
                            }
                        } else if (426 < vW && vW <= 640) {
                            if (vid360) {
                                if (source.getAttribute('src') !== vid360) {
                                    source.setAttribute('src', vid360);
                                    changed = true;
                                } else return;
                            } else if (vid480) {
                                if (source.getAttribute('src') !== vid480) {
                                    source.setAttribute('src', vid480);
                                    changed = true;
                                } else return;
                            } else if (vid540) {
                                if (source.getAttribute('src') !== vid540) {
                                    source.setAttribute('src', vid540);
                                    changed = true;
                                } else return;
                            } else if (vid720) {
                                if (source.getAttribute('src') !== vid720) {
                                    source.setAttribute('src', vid720);
                                    changed = true;
                                } else return;
                            }
                        } else if (640 < vW && vW <= 854) {
                            if (vid480) {
                                if (source.getAttribute('src') !== vid480) {
                                    source.setAttribute('src', vid480);
                                    changed = true;
                                } else return;
                            } else if (vid540) {
                                if (source.getAttribute('src') !== vid540) {
                                    source.setAttribute('src', vid540);
                                    changed = true;
                                } else return;
                            } else if (vid720) {
                                if (source.getAttribute('src') !== vid720) {
                                    source.setAttribute('src', vid720);
                                    changed = true;
                                } else return;
                            }
                        } else if (854 < vW && vW <= 960) {
                            if (vid540) {
                                if (source.getAttribute('src') !== vid540) {
                                    source.setAttribute('src', vid540);
                                    changed = true;
                                } else return;
                            } else if (vid720) {
                                if (source.getAttribute('src') !== vid720) {
                                    source.setAttribute('src', vid720);
                                    changed = true;
                                } else return;
                            }
                        } else if (vW > 960 && vW <= 1280) {
                            if (vid720) {
                                if (source.getAttribute('src') !== vid720) {
                                    source.setAttribute('src', vid720);
                                    changed = true;
                                } else return;
                            }
                        } else if (1280 < vW && vW <= 1920) {
                            if (vid1080) {
                                if (source.getAttribute('src') !== vid1080) {
                                    source.setAttribute('src', vid1080);
                                    changed = true;
                                } else return;
                            }
                        } else if (1920 < vW && vW <= 2560) {
                            if (vid1440) {
                                if (source.getAttribute('src') !== vid1440) {
                                    source.setAttribute('src', vid1440);
                                    changed = true;
                                } else return;
                            }
                        } else if (2560 < vW) {
                            if (vid2160) {
                                if (source.getAttribute('src') !== vid2160) {
                                    source.setAttribute('src', vid2160);
                                    changed = true;
                                } else return;
                            } else if (vid1440) {
                                if (source.getAttribute('src') !== vid1440) {
                                    source.setAttribute('src', vid1440);
                                    changed = true;
                                } else return;
                            }
                        }

                        if (changed) {
                            video.pause();
                            video.currentTime = 0;
                            video.load();
                            if (video.closest('.play')) {
                                video.play();
                            }
                        } else {
                            if (source.getAttribute('src') !== vidDefault) {
                                video.pause();
                                video.currentTime = 0;
                                source.setAttribute('src', vidDefault);
                                video.load();
                            }
                        }
                    }

                    function stopVideo(slide) {
                        const video = slide.querySelector('video');
                        if (video) {
                            video.pause();
                            video.currentTime = 0;
                        }
                        slide.classList.remove('active');
                        slide.classList.remove('play');
                    }

                    function playVideo(slide) {
                        let video = slide.querySelector('video');
                        if (video) {
                            video.play();
                        }
                        slide.classList.add('play');
                    }

                    function coverflow(index) {
                        let activeSlide = glideSlides[index];
                        let nextSlide = activeSlide.nextElementSibling;
                        let next2Slide;
                        if (nextSlide) next2Slide = nextSlide.nextElementSibling;
                        let next3Slide;
                        if (next2Slide) next3Slide = next2Slide.nextElementSibling;

                        activeSlide.classList.remove('glide__slide--previous');
                        activeSlide.classList.remove('glide__slide--following');

                        if (activeSlide.nextElementSibling) {
                            activeSlide.nextElementSibling.classList.remove('glide__slide--previous');
                            activeSlide.nextElementSibling.classList.remove('glide__slide--following');
                            activeSlide.nextElementSibling.classList.add('glide__slide--following');
                        }

                        if (activeSlide.previousElementSibling) {
                            activeSlide.previousElementSibling.classList.remove('glide__slide--previous');
                            activeSlide.previousElementSibling.classList.remove('glide__slide--following');
                            activeSlide.previousElementSibling.classList.add('glide__slide--previous');
                        }

                        if (perView === 1 || perView === 2 || perView === 3 || perView === 4) playVideo(activeSlide);
                        if ((perView === 2 || perView === 3 || perView === 4) && nextSlide) playVideo(nextSlide);
                        if ((perView === 3 || perView === 4) && next2Slide) playVideo(next2Slide);
                        if (perView === 4 && next3Slide) playVideo(next3Slide);

                        activeSlide.classList.add('active');

                        let elms = activeSlide.parentNode.querySelectorAll('.glide__slide');
                        elms.forEach(elm => {
                            if (elm === activeSlide) return;
                            if (perView === 2)
                                if (elm === activeSlide || elm === nextSlide) return;
                            if (perView === 3)
                                if (elm === activeSlide || elm === nextSlide || elm === next2Slide) return;
                            if (perView === 4)
                                if (elm === activeSlide || elm === nextSlide || elm === next2Slide || elm === next3Slide) return;

                            stopVideo(elm);
                        });

                        const slider = activeSlide.closest('.glide');
                        slider.classList.add('running');
                    }

                    let myslider = document.querySelector("#ThPWpZ0");
                    let _ThPWpZ0;
                    if (myslider.classList.contains('coverflow')) {

                        _ThPWpZ0 = new Glide(myslider, {
                            type: "carousel",
                            autoplay: false,
                            animationDuration: 300,
                            gap: 0,
                            perView: 3,
                            startAt: 2,
                            hoverpause: false,
                            arrow: true,
                            dots: false,
                            breakpoints: {
                                575: {
                                    perView: 1,
                                    peek: 50
                                },

                                414: {
                                    perView: 1,
                                    peek: 40
                                },

                                360: {
                                    perView: 1,
                                    peek: 30
                                }
                            },
                            // focusAt: "center",
                        });

                    } else {

                        _ThPWpZ0 = new Glide("#ThPWpZ0", {
                            type: "carousel",
                            autoplay: false,
                            animationDuration: 300,
                            gap: 0,
                            perView: 4,
                            hoverpause: false,
                            arrow: true,
                            dots: false,
                            breakpoints: {
                                1440: {
                                    perView: 3
                                },
                                1280: {
                                    perView: 2
                                },

                                970: {
                                    perView: 1,
                                    gap: 0
                                },
                                1280: {
                                    gap: 15
                                }
                            },
                        });

                    }

                    _ThPWpZ0.on('mount.after', function() {
                        coverflow(_ThPWpZ0.index, true);
                    });

                    _ThPWpZ0.on('run', function(event) {
                        coverflow(_ThPWpZ0.index);
                    });

                    _ThPWpZ0.mount();

                });
            </script>
        </div>
    </div>
</div>

<p class="md:flex"></p>
