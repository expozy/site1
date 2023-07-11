import {Api} from './api/api.js';
import {Helpers} from './helpers.js';


export let Sliders = {

	get_sliders: async function(parameters){
		var jsonParameters = JSON.parse(parameters);

		var endpoint = Helpers.combineRequest('sliders' , parameters);
		await Api.get(endpoint, false);

		if(Api.response){
			// IF WE HAVE sliderName call function with that name
			if(jsonParameters.sliderName != undefined ){
				eval(jsonParameters.sliderName + '(Api.response)');
			}

				return Api.response;
		}
	},

	get_promotions: async function(parameters){
		var jsonParameters = JSON.parse(parameters);
		// debugger;
		var endpoint = Helpers.combineRequest('sliders' , parameters);
		await Api.get(endpoint, false);
		var data = Api.response;
		if(data){
			if(data.slides.length != 0){
				// debugger;
				var i = 1;
				var arr1 = [];
				var arr2 = [];
				var arr3 = [];
				for(const slide of data.slides){

					if(i <= (data.slides.length / 3)){
						arr1.push(slide);
					}

					if(i <= (data.slides.length / 3) * 2 && i > (data.slides.length / 3)){
						arr2.push(slide);
					}

					if(i <= (data.slides.length / 3) * 3 && i > (data.slides.length / 3) * 2 ){
						arr3.push(slide);
					}

					i++;
				}
				data['col1'] = arr1;
				data['col2'] = arr2;
				data['col3'] = arr3;
			}

			return data;
		}
	}


};
window.Sliders = Sliders;


function home_slider_glide(data){

	var slider = document.getElementById('home_slider_glide');
	var glideSlidesElement = slider.getElementsByClassName('glide__slides');

	var html = '';


	if(data.slides.length != 0){


	for(const slide of data.slides){

		html +=
		`<div class=glide__slide "relative text-center text-white  ">
		<div class="flex rounded-[25px] justify-center">
			<a href="`+slide.url+`">
				<img class="w-full h-full object-contain  rounded-3xl  " src="`+slide.image.url+`">
				</a>
			</div>
		</div>`;
	}
	glideSlidesElement[0].innerHTML = html;


		new Glide('#home_slider_glide', {
		type: 'carousel',
		startAt: 1,
		perView: 1,
		focus:1,
		autoplay:3000,
		animationDuration: 2000,
		peek:{ before: 400, after: 400 },
		gap:70,
		breakpoints:{
			1350: {
	 		perView: 1,
				peek:{ before: 150, after: 150 },
					gap:35,
 			},
			1025: {
	 		perView: 1,
				peek:{ before: 0, after: 0 },
					gap:20,
 			},
			700: {
	 		perView: 1,
				peek:{ before: 0, after: 0 },

 			}
		}
	}).mount();

	}
}

function home_slider_glide2(data){

	var slider = document.getElementById('home_slider_glide2');
	var glideSlidesElement = slider.getElementsByClassName('glide__slides');

	var html = '';


if(data.slides.length != 0){
	for(const slide of data.slides){
		html +=
		`<div class="glide__slide relative text-center text-white h-fit">
		<a href="`+slide.url+`">
			<img class="rounded-[15px]" src="`+slide.image.url+`">
			</a>
		</div>
		`;
	}
	glideSlidesElement[0].innerHTML = html;



	new Glide('#home_slider_glide2', {
	type: 'carousel',
	startAt: 1,
	perView: 2,
	autoplay:3000,
	animationDuration: 2000,
	breakpoints:{
		700: {
		perView: 2,
		}
	}
	}).mount();
}
}


function cart_slider_glide(data){

	var slider = document.getElementById('cart_slider_glide');
	var glideSlidesElement = slider.getElementsByClassName('glide__slides');

	var html = '';

if(data.slides.length != 0){
	for(const slide of data.slides){
		html +=
		`<div class="glide__slide relative text-center text-white h-fit">
		<a href="`+slide.url+`">
			<img class="rounded-[15px]" src="`+slide.image.url+`">
			</a>
		</div>
		`;
	}
	glideSlidesElement[0].innerHTML = html;


	new Glide('#cart_slider_glide', {
	type: 'carousel',
	startAt: 1,
	perView: 1,
	autoplay:3000,
	animationDuration: 2000,
	}).mount();
}
}
