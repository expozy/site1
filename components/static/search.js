import {ApiClass} from '../core/api/api.js';
import {Helpers} from '../core/helpers.js';


let warehouse_id ;
// localStorage.getItem('WAREHOUSE_ID') ?  warehouse_id = parseInt(localStorage.getItem('WAREHOUSE_ID'))    :    warehouse_id = 1;


export let Search = {

		get_search: async function (data, options){
			let response = [];
			let apiEndpoint = 'search';
			data['filter'] = 1;
			//
			if("endpoint" in options && options['endpoint'] != '' && options['endpoint'] != null && options['endpoint'].includes(data['search']) ) apiEndpoint = options['endpoint'];
			let endpoint = Helpers.combineRequest(apiEndpoint , data);

			let api = new ApiClass();
			await api.get(endpoint, true);

			if(!api.response) return response['internalError'] = 'No response from api for Search.get_search';

			if("pagination" in api.response.products){
				api.response.products.pagination['pagesArray'] =  Helpers.pagination(api.response.products.pagination['current_page'], api.response.products.pagination['total_pages']);
			}

			response['obj'] = api.response;
			response.keyName = 'search';
			response['obj'].endpoint = endpoint;


			if(options !== undefined){
				if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

				if("initial" in options && options['initial'] == true){
					return Handler.responseHandler(response);
				}
			}


				return response;

		},

		change_page:async function(data, options){
			let response = [];
			let api = new ApiClass();


			// CHECK DO WE HAVE ENDPOINT ELSE RETURN ERROR
			if (!("endpoint" in data) && typeof(data.endpoint) === "undefined") return {internalError: 0, msg:`No endpoint is set for Search.change_page`};

			// REMOVE LAST PAGE IF WE HAVE
			let endpoint = data['endpoint'].replace(/([&?])page=\d+/g, '');

			let tmpEndpoint = endpoint;
			// ADD THE NEW PAGE
			endpoint +=`&page=${data.page}`;

			await api.get(endpoint , false);

			if(!api.response) return response['internalError'] = 'No response from api for Search.change_page';

			response['obj'] = api.response;
			response.obj['endpoint'] = tmpEndpoint;
			response['keyName'] = 'search';

			response.obj.products.pagination['pagesArray'] =  Helpers.pagination(response.obj.products.pagination['current_page'], response.obj.products.pagination['total_pages']);

			// if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];
			document.getElementById('main').scrollIntoView(true);

			return response;

	},
	//
	//
	// aplyFiltersSearch: async function(){
	// 	window.scrollTo({top: 0, behavior: 'smooth'});
	// 	var filters = await Shop.getFilters();
	//
	// 	var endpoint = Helpers.combineRequest('search' , JSON.stringify(filters));
	// 	await Api.get(endpoint, false);
	// 	var data = Api.response;
	//
	//
	// 	Alpine.updateDataFull('products', {'Search':data});
	// 	Alpine.updateDataFull('filterPagination', {'Pagination':data.products.pagination});
	//
	//
	// },
	//
	//
	// changePageSearch: async function(element, data){
	// 	window.scrollTo({top: 0, behavior: 'smooth'});
	// 	var data = Helpers.get_form_data(element.closest("form"));
	//
	// 	// IF NEGATIVE PAGE DONT GET PRODUCTS
	// 	if( parseInt(data.current_page) == 1 && parseInt(data.page) <= 1) return;
	//
	// 	// IF	PAGE DONT EXIST DONT GET PRODUCTS
	// 	if( parseInt(data.page) > parseInt(data.total_pages) ) return;
	//
	//
	// 	const filters = await Shop.getFilters();
	// 	filters['page'] = data.page;
	//
	// 	const filtersJSON = JSON.stringify(filters);
	// 	var endpoint = Helpers.combineRequest('search' , filtersJSON);
	//
	// 	await Api.get(endpoint, false);
	// 	var data = Api.response;
	// 		data['products']['result'] = await Shop.productInCart(data.products.result);
	// 	Alpine.updateDataFull('products', {'Search':data});
	// 	Alpine.updateDataFull('filterPagination', {'Pagination':data.products.pagination});
	//
	// },
	//
	//
	// get_search_auto: async function(element, parameters){
	//
	// 	if (element.value.length < 3 ) return;
	//
	//
	// 		await Api.get('search?cat_limit=3&sort=relevant&search='+element.value, true);
	//
	//
	//
	// 			if(Api.response){
	// 				var data = Api.response;
	//
	// 				Releva.post('search',0 ,warehouse_id, data);
	// 				Alpine.updateDataFull('modal_search', {'Search':data , 'open': true});
	//
	// 				if(data.products.result.length > 0 ){
	// 					var i = 0;
	// 					var htmlButtons ='';
	//
	// 					for(const product of data.products.result){
	// 						var productSliderTemplate = document.getElementById('productSliderTemplate').content;
	// 					  var json_data = JSON.stringify(product);
	//
	// 						productSliderTemplate.querySelector('#productItemData').setAttribute('x-data', json_data);
	// 						var copy_html = document.importNode(productSliderTemplate, true);
	// 						document.getElementById('productSliderContent').appendChild(copy_html);
	//
	// 						 htmlButtons +=`<button class="glide__bullet main_bullet" data-glide-dir="=`+i+`"></button>`;
	// 						i++;
	//
	// 					}
	//
	// 					document.getElementById('sliderButtons').innerHTML = htmlButtons;
	// 					new Glide('#search_slider', {
	// 						type: 'carousel',
	// 						startAt: 0,
	// 						perView: 2,
	// 						autoplay:false,
	// 						focus:'center',
	// 					}).mount();
	//
	// 				}
	// 			}
	// },
	//
	// get_search_btn: async function(element, parameters){
	//
	// 		var data = Helpers.get_form_data(element.closest("form"));
	//
	// 		await Api.get('search?sort=relevant&search='+data.search, false);
	//
	//
	// 			if(Api.response){
	// 				var result = Api.response;
	// 				Releva.post('search',0 ,warehouse_id, result);
	//
	// 				Alpine.updateDataFull('modal_search', {'Search':result , 'open': true});
	//
	// 				if(result.products.result.length > 0 ){
	// 					var i = 0;
	// 					var htmlButtons ='';
	//
	// 					for(const product of result.products.result){
	//
	//
	// 						var productSliderTemplate = document.getElementById('productSliderTemplate').content;
	//
	// 						  var json_data = JSON.stringify(product);
	//
	// 						productSliderTemplate.querySelector('#productItemData').setAttribute('x-data', json_data);
	// 						var copy_html = document.importNode(productSliderTemplate, true);
	// 						document.getElementById('productSliderContent').appendChild(copy_html);
	//
	// 						 htmlButtons +=`<button class="glide__bullet main_bullet" data-glide-dir="=`+i+`"></button>`;
	// 						i++;
	//
	// 					}
	// 					document.getElementById('sliderButtons').innerHTML = htmlButtons;
	// 					new Glide('#search_slider', {
	// 						type: 'carousel',
	// 						startAt: 0,
	// 						perView: 2,
	// 						autoplay:false,
	// 						focus:'center',
	// 					}).mount();
	//
	// 				}
	// 			}
	// },



}

window.Search = Search;
