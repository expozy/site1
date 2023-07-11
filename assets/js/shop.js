import {ApiClass} from './api/api.js';
import {Helpers} from './helpers.js';

import {Sliders} from './sliders.js';

// localStorage.getItem('WAREHOUSE_ID')
let warehouse_id = 1;
// localStorage.getItem('WAREHOUSE_ID') ?  warehouse_id = parseInt(localStorage.getItem('WAREHOUSE_ID'))    :    warehouse_id = 1;
let releva_post_category = 0;



export let Shop = {

	put_carts: async function(data,options){
			let response = [];

		let api = new ApiClass();
		await api.put('carts', data );

		if(!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';

		response = api.response;
		response['keyName'] = 'cart';

		return response;

	},
	post_carts: async function(data, options){
		let response = [];

		let api = new ApiClass();

		await api.post('carts', data);

		if(!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';
		response = api.response;
		response['keyName'] = 'cart';

		return response;
	},
	get_cart: async function(data, options){

		let endpoint = Helpers.combineRequest('cart' , data);
		let api = new ApiClass();
		await api.get(endpoint, true);

		if(!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';


			let response = [];
			response.obj = api.response;
			response.keyName = 'cart';
			if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

			if("initial" in options && options['initial'] == true){
				// console.log(Handler.responseHandler(response));
				return Handler.responseHandler(response);
			}
			return response;

	},
	delete_cart: async function(data){
		let response = [];
		// CHECK DO WE HAVE ENDPOINT ELSE RETURN ERROR
		if (!("id" in data) && typeof(data.id) === "undefined") return {internalError: 0, msg:`No id is set for Shop.delete_cart`};

		let api = new ApiClass();
		await api.delete('carts/'+data.id, data);

		if(!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';

		response['keyName'] = 'cart';
		response['obj'] = api.response.cart;

		return response;
	},

	get_orders: async function(data, options){
		let response = [];
		let endpoint = Helpers.combineRequest('orders' , data);
		let api = new ApiClass();

		await api.get(endpoint, false);

		if(!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';

		response['keyName'] = 'orders';
		response['obj'] = api.response;

		if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		if("initial" in options && options['initial'] == true){
			// console.log(Handler.responseHandler(response));
			return Handler.responseHandler(response);
		}

		return response;


	},


	get_combinations: async function(data, options){

		let endpoint = Helpers.combineRequest('combinations' , data);
		let api = new ApiClass();
		await api.get(endpoint, true);

		if(!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';


			let response = [];
			response.obj = api.response;
			response.keyName = 'combinations';
			if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

			if("initial" in options && options['initial'] == true){
				// console.log(Handler.responseHandler(response));
				return Handler.responseHandler(response);
			}

			return response;



	},


	get_categories: async function(data, options){

		// FIX BUG WITH CAPITAL LETTER
		if("droplist" in data){
			delete  data['droplist'];
			data['dropList'] = true;
		}

		let endpoint = Helpers.combineRequest('categories' , data);
		let api = new ApiClass();
		await api.get(endpoint, true);

		if(!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';


					let response = [];
					response['obj'] = api.response;
					response['keyName'] = 'categories';


					if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

					if("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

					return response;

			},

	get_products : async function (data, options){
			let response = [];
			let apiEndpoint = 'products';

			if("endpoint" in options && options['endpoint'] != '' && options['endpoint'] != null) apiEndpoint = options['endpoint'];
			let endpoint = Helpers.combineRequest(apiEndpoint , data);

		let api = new ApiClass();
		await api.get(endpoint, true);

		if(!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';




			response.obj = api.response;
			response.obj.endpoint = endpoint;
			response.keyName = 'products';
			if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];


			if("pagination" in response.obj){
				response.obj.pagination['pagesArray'] =  Helpers.pagination(response.obj.pagination['current_page'], response.obj.pagination['total_pages']);
			}


			if("initial" in options && options['initial'] == true){
				return Handler.responseHandler(response);
			}

			return response;

	},

	post_orders : async function (data, options){

		let api = new ApiClass();
		await api.post('orders', data);

		if(!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';

		let response = [];

		response = api.response;

		if(api.response.status === 1){
			response['keyName'] = 'order';
			response['url'] = '/bg/ordersummary';
		}




		return response;


	},
	post_wishlist: async function (data, options){
		let response = [];
		let api = new ApiClass();

		await api.post('wishlist', data);

		if(!api.response) return response['internalError'] = 'No response from api for Shop.wishlist';




		response = api.response;

		return response;


	},
	delete_wishlist: async function(data, options){
		let response = [];
		// CHECK DO WE HAVE ENDPOINT ELSE RETURN ERROR
		if (!("product_id" in data) && typeof(data.product_id) === "undefined") return {internalError: 0, msg:`No product_id is set for Shop.delete_wishlist`};

		let api = new ApiClass();
		await api.delete('wishlist/'+data.product_id, data);

		if(!api.response) return response['internalError'] = 'No response from api for Shop.delete_wishlist';

		response = api.response;

		return response;
	},
	get_wishlist: async function(data, options){
		let response = [];

		let apiEndpoint = 'wishlist';

		if("endpoint" in options && options['endpoint'] != '' && options['endpoint'] != null) apiEndpoint = options['endpoint'];
		let endpoint = Helpers.combineRequest(apiEndpoint , data);


		let api = new ApiClass();

		await api.get(endpoint, true);

		if(!api.response) return response['internalError'] = 'No response from api for Shop.get_wishlist';

		response.obj = api.response;
		response.keyName = 'wishlist';
		response.obj.endpoint = endpoint;
		if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		if("initial" in options && options['initial'] == true){
			return Handler.responseHandler(response);
		}

		return response;

	},


	post_promocode: async function(data, options){
		let response = [];
		let api = new ApiClass();

		await api.post('promocode', data);

		if(!api.response) return response['internalError'] = 'No response from api for Shop.promocode';

		response = api.response;

		if(api.response.status == 1){
			response.keyName = 'cart';
			response.obj = api.response.cart;
			if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];
		}else {
			response.keyName = 'empty';
		}


		return response;


	},

		change_page:async function(data, options){
			let response = [];
			let api = new ApiClass();



			// CHECK DO WE HAVE ENDPOINT ELSE RETURN ERROR
			if (!("endpoint" in data) && typeof(data.endpoint) === "undefined") return {internalError: 0, msg:`No endpoint is set for Shop.change_page`};

			// REMOVE LAST PAGE IF WE HAVE
			let endpoint = data['endpoint'].replace(/([&?])page=\d+/g, '');

			// ADD THE NEW PAGE
			endpoint +=`&page=${data.page}`;

			await api.get(endpoint , false);

			if(!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';

			response['obj'] = api.response;
			response.obj['endpoint'] = endpoint;
			response['keyName'] = 'products';

			response.obj.pagination['pagesArray'] =  Helpers.pagination(response.obj.pagination['current_page'], response.obj.pagination['total_pages']);

			// if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];
			document.getElementById('main').scrollIntoView(true);

			return response;

	},

	delete_promocode: async function(data,options){

		let response = [];

		let api = new ApiClass();
		await api.delete('promocode', data);

		if(!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';

		response['keyName'] = 'cart';
		response['obj'] = api.response.cart;

		return response;
	},

		// get_category: async function(data){
		// 	let endpoint = Helpers.combineRequest('category' , data);
		// 	await Api.get(endpoint, true);
		// 	if(Api.response){
		// 		let data = Api.response;
		// 		return data;
		// 	}
		// },
	/* GET_PRODUCTS WITH URL CHANGE */

	// get_products: async function(data){
		// let endpoint = Helpers.combineRequest('products' , data);
		//
		// let returnResponse = {};
		// await Api.get(endpoint , true);
		// if(Api.response){
		// 	let productsResponse = Api.response;
		// 	productsResponse['endpoint'] = endpoint;
		//
		// 	// ChANGE THE PAGE DATA AND URL IF WE ARE ON CATEGORY PAGE
		// 	if(PAGE_TYPE === 'category' && ("category_id" in data && typeof(data.category_id) !== "undefined" && data.category_id != dataBody.corePage.id) ){
		// 		await Shop.get_category({id:data.category_id});
		// 		if(Api.response){
		// 			let categoryResponse = Api.response;
		// 			if("initial" in data && data['initial'] == true) return Handler.responseHandler(categoryResponse);
		// 			window.history.pushState("", "", categoryResponse.url);
		// 			dataBody.corePage.title = categoryResponse.title;
		// 			dataBody.corePage.url = categoryResponse.url;
		// 			dataBody.corePage.id = categoryResponse.id;
		// 			dataBody.corePage.slug = categoryResponse.slug;
		// 			returnResponse.global = true;
		// 		}
		// 	}
		// 	returnResponse.obj = productsResponse;
		//
		// 	return productsResponse;
	// },


// GET

// order_payments
//  orders
//  favourites ?? (favourites products)



	// get_categories_w_slider: async function(parameters){
	//
	// 	let endpoint = Helpers.combineRequest('categories' , parameters);
  //     await Api.get(endpoint, true);
	// 		let data = {};
  //       if(Api.response){
  //          data['maincategory'] = Api.response;
	//
	// 				await Api.get('tempCategories?row2=2', true);
	// 				if(Api.response){
	// 					data['tempCategory'] = Api.response;
	// 				}
	//
	// 				await Api.get('sliders/7', true);
	// 				if( Api.response){
	// 					// data['slider'] = Api.response;
	// 					console.log(Api.response.slides[0]);
	// 					for(const cat of data.maincategory){
	// 						cat['slider'] = Api.response.slides[0].image.url != '' ? Api.response.slides[0].image.url : "/assets/designs/img/nav-image.png";
	// 						cat['slider_url'] = Api.response.slides[0].url ;
	// 					}
	//
	// 				}
	//
  //         return data;
  //       }
	// },
	// get_tempCategories: async function(parameters){
	//
	// 	let endpoint = Helpers.combineRequest('tempCategories' , parameters);
  //     await Api.get(endpoint, true);
  //       if(Api.response){
  //         let data = Api.response;
  //         return data;
  //       }
	// },
	//
	// get_order_payments: async function(parameters){
	//
	// 	let endpoint = Helpers.combineRequest('order_payments' , parameters);
	// 		await Api.get(endpoint, true);
	//
	// 			if(Api.response){
	// 				let data = Api.response;
	// 				return data;
	// 			}
	// },
	//
	// get_orders_options: async function(parameters){
	//
	// 	let endpoint = Helpers.combineRequest('orders_options' , parameters);
	// 		await Api.get(endpoint, true);
	//
	// 			if(Api.response){
	// 				let data = Api.response;
	//
	// 				return data;
	// 			}
	// },
	//

	//

	//

	//
	// // ONLY FOR THIS PROJECT
	// productInCart: async function(products){
	// 	 await Api.get('cart' , false);
	// 	 if(Api.response){
	// 		 	let cartResponse = Api.response;
	// 			if(cartResponse.products.length != 0){
	// 				for(const product of products){
	// 					for(const cartProduct of cartResponse.products){
	// 						if(product['id'] === cartProduct['id']){
	// 							product['inCart'] = true;
	// 						}
	// 					}
	// 				}
	// 			}
	// 	 }
	// 	 return products;
	// },
	// // ONLY FOR THIS PROJECT
	//
	//
	//

	//
	// get_product_tags: async function(parameters){
	//
	// 	let endpoint = Helpers.combineRequest('product_tags' , parameters);
  //     await Api.get(endpoint, false);
  //       if(Api.response){
  //         let data = Api.response;
  //         return data;
  //       }
	// },
	//
	//
	//
	// get_order: async function(parameters){
	// 	let gateways = {};
	// 		await Api.get('order_payments' , true );
	// 	if(Api.response){
	// 		 gateways = Api.response;
	// 	}
	//
	//
	// 	let endpoint = Helpers.combineRequest('order' , parameters);
	// 		await Api.get(endpoint, false);
	// 			if(Api.response){
	//
	// 				let data = Api.response;
	//
	// 				if(gateways.length != 0){
	// 					for (const gateway of gateways){
	// 						if(gateway.code == data.payment_method){
	// 							data['payment_title'] = gateway.title_OLD;
	// 						}
	// 					}
	// 				}
	//
	// 				return data;
	// 			}
	// },
	//
	// post_carts: async function(element,parameters){
	//
	// 	let data = Helpers.get_form_data(element.closest("form"));
	//
	//
	// 	await Api.post('carts', data);
	//
	// 	if(Api.response){
	// 		let cartResponse = Api.response;
	// 		if(cartResponse.status === 1){
	// 			Helpers.show_toast_msg(cartResponse.msg, 'success', 1500);
	// 			// let data = Api.response;
	//
	// 			cartResponse.cart['structured'] = await Shop.cart_stuctured2(cartResponse.cart);
	// 				cartResponse.cart['productsCount'] = cartResponse.cart.products.length;
	// 			Alpine.updateDataFull('cartUpdater', {'Shop':cartResponse.cart});
	// 			Alpine.updateDataPartially('cartDropdownBtn', {'Shop':cartResponse.cart});
	// 			Alpine.updateDataPartially('cartDropdownBtn2', {'Shop':cartResponse.cart});
	//
	// 		}else {
	// 			Helpers.show_errors(cartResponse);
	// 		}
	//
	// 		if(data.releva_info != undefined){
	// 			 Releva.post('cart', data.id ,warehouse_id, '', data.releva_info);
	// 		}
	//
	// 		return cartResponse;
	// 	}
	//
	// },
	//
	// post_timeslot: async function(element,parameters){
	//
	// 	let data = {
	// 		timeSlot_id : element.getAttribute('data-id'),
	// 		timeSlot_date : element.getAttribute('data-date'),
	// 		post_code : localStorage.getItem('deliveryPostCode'),
	// 	};
	//
	// 	await Api.post('carts', data);
	//
	// 	if(Api.response){
	// 		if(Api.response.status === 1){
	// 			Api.response.cart['structured'] = await Shop.cart_stuctured2(Api.response.cart);
	// 		}else {
	// 			Helpers.show_errors(Api.response);
	// 		}
	//
	// 		return Api.response;
	// 	}
	// },
	//
	// save_parent_order: async function(element,parameters){
	// 	let data = Helpers.get_form_data(element.closest("form"));
	//
	// 	if(data.combine_order == 0){
	// 		localStorage.removeItem('parent_order_id');
	// 	}else {
	// 		localStorage.setItem('parent_order_id', data.combine_order);
	// 	}
	//
	// },
	// save_cart_options: async function(element,parameters){
	// 	let data = Helpers.get_form_data(element.closest("form"));
	//
	// 	let options = [];
	//
	// 	for(const item in data){
	// 		if(data[item] == true){
	// 			options.push(parseInt(item));
	// 		}
	// 	}
	// 	localStorage.setItem('cart_options', options);
	//
	// },
	//
	// post_mass_carts: async function(element,parameters){
	//
  //  //EXAMPLE DATA =  {id_0: '64204', qty_0: '1', id_1: '64622', qty_1: '2'}
	// 	let data = Helpers.get_form_data(element.closest("form"));
	//
	// 	let array = [];
	// 	for(const product in data){
	//
	// 		let split = product.split('_');
	//
	// 		let key = split[0];
	//
	// 		if(key === 'id' || key === 'qty'){
	//
	// 			let index = split[1];
	// 			let value = data[product];
	//
	// 			if(typeof array[index] === 'undefined') {
	// 				array[index] = {
	// 					[key] : value,
	// 				}
	// 			}else {
	// 				let obj = array[index];
	// 				let newKey = key;
	// 				let newVal = value;
	// 				obj[newKey] = newVal;
	// 				array[index] = obj;
	// 			}
	// 		}
	// 	}
	// 	array = array.filter(n => n);
	// 	 data = {
	// 		products : array
	// 	};
	//
	// 	await Api.post('carts', data);
	//
	//
	// 	if(Api.response){
	// 		if(Api.response.status === 1){
	// 			if(Api.response.not_added[0]){
	// 				window.localStorage.setItem("notAddedProducts", JSON.stringify(Api.response.not_added));
	// 				Api.response.cart['not_added'] = Api.response.not_added;
	// 			}
	// 			console.log(Api.response.cart);
	// 			Helpers.show_toast_msg('Продуктите са добавени в количката.', 'success');
	// 			Api.response.cart['structured'] = await Shop.cart_stuctured2(Api.response.cart);
	//
	// 				Api.response.cart['productsCount'] = Api.response.cart.products.length;
	// 			Alpine.updateDataFull('cartUpdater', {'Shop':Api.response.cart});
	// 			Alpine.updateDataPartially('cartDropdownBtn', {'Shop':Api.response.cart});
	// 			Alpine.updateDataPartially('cartDropdownBtn2', {'Shop':Api.response.cart});
	// 		}else {
	// 			Helpers.show_errors(Api.response);
	// 		}
	// 	}
	//
	//
	// },
	//
	//

	//

	//
	// post_orders: async function(element,parameters){
	// 	let data = Helpers.get_form_data(element.closest("form"));
	//
	// 		Helpers.old_errors_remove();
	//
	// 	if(data.terms != true){
	// 		let termError = `<span class="msg-error text-danger">Моля, маркирайте полето за съгласие с условията.</span>`;
	// 		let spanElement = document.getElementsByName("terms")[0].parentElement.parentElement;
	// 		spanElement.innerHTML = spanElement.innerHTML + termError;
	// 		return;
	// 	}
	//
	// 	// IF TRUE ADD Account if not logged
	// 	if (data.addAccount == true && LOGGED_IN == '' ){
	// 		await User.post_users_noRedirect(data);
	// 		let accountAdd = Api.response;
	// 		if(accountAdd.status != 1){
	// 			return;
	// 		}
	// 	}
	//  // END ADD ACOUNT
	//  //
	//  // IF TRUE ADD newsletter if have REAL EMAIL
	//  if(data.newsletter == true ){
	// 	 Newsletter.post_newsletter( '', '', data);
	// 	 let nw = Api.response;
	//  }
	//  // END NEWSLETTTER
	//  //
	//
	//  if (data.payment_method == 5) {
	// 	 if(data.code_card == false && data.code_cash == false && data.code_voucher == false){
	// 		 Helpers.show_toast_msg('Изберете вид на плащането при доставка.', 'error');
	// 		 return;
	// 	 }
	//
	//
	//
	//  }
	//
	//
	//  await Api.get('carts', false);
	//  let cart = Api.response;
	//  data['timeSlot_id'] = cart.selected_timeslot.id;
	//  data['timeSlot_date']= cart.selected_timeslot.date;
	//
	// 	if(localStorage.getItem('cart_options') != null){
	// 		let options =  localStorage.getItem('cart_options').split(',');
	// 		data.options = options;
	// 	}
	//
	// 	if(localStorage.getItem('parent_order_id') != null){
	// 		data.parent_order_id = localStorage.getItem('parent_order_id');
	// 	}
	//
	// 	if(localStorage.getItem('PROMOPOINTS') != null ){
	// 		data.tokens = localStorage.getItem('PROMOPOINTS');
	// 	}
	//
	// 	data.city = localStorage.getItem('deliveryCity');
	// 	data.state = localStorage.getItem('deliveryArea');
	// 	data.post_code = localStorage.getItem('deliveryPostCode');
	//
	//
	// 	await Api.post('orders', data);
	//
	// 	if(Api.response){
	// 		let orderResponse = Api.response;
	// 		if(orderResponse.status === 1){
	//
	// 			//START Записване адреса на доставка на потребителя
	// 			if(data.saveAddress){
	//
	// 				let city = localStorage.getItem('deliveryCity');
	// 				let area = localStorage.getItem('deliveryArea');
	// 				let post_code = localStorage.getItem('deliveryPostCode');
	// 				let address = localStorage.getItem('deliveryAddress');
	// 				if( (city != undefined && city != '') && (area != undefined && area != '') && (post_code != undefined && post_code != '')&& (address != undefined && address != '') ){
	// 					let address = {
	// 						city:city ,
	// 						state: area,
	// 						shipping_address: address,
	// 						post_code: post_code,
	// 						shipping_address2: "a",
	// 						country_id: 1,
	// 					}
	// 						await Api.post('user_address', address);
	// 				}
	// 			}
	// 			//END Записване адреса на доставка на потребителя
	//
	// 			localStorage.removeItem('cart_options');
	// 			localStorage.removeItem('parent_order_id');
	// 			localStorage.removeItem('PROMOPOINTS');
	// 			//Releva.post('order',Api.response.order_ids[0] ,warehouse_id, Api.response);
	//
	// 			if(orderResponse.url){
	// 				location.href = orderResponse.url;
	// 			}else if(orderResponse.order_ids[0]){
	// 					location.href = "/thank_you/"+ orderResponse.order_ids[0] + '/';
	// 			}
	// 		}else {
	// 			if(orderResponse.url){
	// 				location.href = orderResponse.url;
	// 			}
	// 			if(!orderResponse.errors.hasOwnProperty('[name="quantity"]')){
	// 				Helpers.show_errors(orderResponse);
	// 			}else {
	// 				await Api.get('cart', false , '' , false);
	// 				if(Api.response){
	//
	// 					Api.response['structured'] = await Shop.cart_stuctured2(Api.response);
	//
	// 					Api.response.cart['productsCount'] = Api.response.cart.products.length;
	// 					Alpine.updateDataFull('cartUpdater', {'Shop':Api.response, 'open':true });
	// 				}
	// 				Helpers.show_toast_msg('Някой от продуктите не е наличен в желаното количество!', 'error');
	// 			}
	//
	//
	// 		}
	// 	}
	// },
	//
	// post_wishlist: async function(element,parameters){
	// 	let data = Helpers.get_form_data(element.closest("form"));
	//
	// 	if(data.releva_info != undefined){
	// 		 Releva.post('product', data.id ,warehouse_id, '', data.releva_info);
	// 	}
	//
	// 	if(data.isWishlist == 1){
	// 		await Api.delete('wishlist/'+data.product_id , false);
	// 	}else {
	// 		await Api.post('wishlist', data);
	// 	}
	//
	// },
	//
	//
	//
	//
	//
	//
	// put_cartsFull: async function(element,parameters){
	// 	let data = Helpers.get_form_data(element.closest("form"));
	//
	// 	await Api.put('carts', data);
	//
	// 	if(Api.response){
	// 		if(Api.response.status === 1){
	// 			Alpine.updateDataPartially('cartDropdownBtn', {'Shop':Api.response.cart});
	// 			Alpine.updateDataPartially('cartDropdownBtn2', {'Shop':Api.response.cart});
	// 			Api.response.cart['structured'] = await Shop.cart_stuctured2(Api.response.cart);
	//
	//
	// 		}else {
	// 			Helpers.show_errors(Api.response);
	//
	// 		}
	//
	// 			return Api.response;
	//
	//
	// 	}
	// },
	//
	//
	// delete_cartAllProducts: async function(element,parameters){
	// 	// let data = Helpers.get_form_data(element.closest("form"));
	//
	// 	await Api.delete('cartAllProducts');
	//
	// 	if(Api.response){
	//
	// 		console.log(Api.response);
	// 	}
	// },
	//
	//
	//
	//
	// delete_cart: async function(element,parameters){
	// 	let data = Helpers.get_form_data(element.closest("form"));
	//
	// 	await Api.delete('carts/'+data.id, data);
	//
	// 	if(Api.response){
	// 		if(Api.response.status === 1){
	// 			let deleteElements = document.querySelectorAll('[data-id="cartProduct-'+data.id+'"]');
	// 			Api.response.cart['productsCount'] = Api.response.cart.products.length;
	//
	// 			Alpine.updateDataPartially('cartDropdownBtn', {'Shop':Api.response.cart});
	// 			Alpine.updateDataPartially('cartDropdownBtn2', {'Shop':Api.response.cart});
	// 			Api.response.cart['structured'] = await Shop.cart_stuctured2(Api.response.cart);
	//
	//       for(const e of deleteElements){
	// 				e.remove();
	// 			}
	//
	// 			return Api.response;
	// 		}else {
	// 			Helpers.show_errors(Api.response);
	// 		}
	// 	}
	// },
	//
	// changeModalUrl: async function(element, parameters){
	//
	// 	let url = window.location.href.split('?product=');
	// 	if(url.length > 1){
	// 		let newUrl = url[0];
	// 	}else {
	// 		url = window.location.href.split('&product=');
	// 		let newUrl = url[0];
	// 	}
	// 	window.history.pushState("", "", newUrl);
	// },
	//
	// open_modal_product: async function(element, parameters, data ){
	//
	// 	if(data == undefined){
	// 		 data = Helpers.get_form_data(element.closest("form"));
	//
	// 		 let url = window.location.href.split('?product');
	//
	// 		 if(url.length > 1){
	// 			 if(data.releva_info != undefined){
	// 				 let newUrl = url[0] + '?product=' + data.id;
	// 			 }else {
	// 				 let newUrl = window.location.href + '&product=' + data.id;
	// 			 }
	// 		 }else {
	// 			 let newUrl = window.location.href + '?product=' + data.id ;
	// 		 }
	// 		 let newUrlSet = 1;
	// 	}
	//
	// 	await Api.get('product/'+data.id, false);
	//  	let productData = Api.response;
	//
	// 	if(newUrlSet === 1){
	// 		newUrl += '&'+ productData.slug;
	// 		window.history.pushState("", "", newUrl);
	// 	}
	//
	// 		Alpine.updateDataFull('modal_product', {'Shop':productData , 'open': true});
	// 	if(data.releva_info != undefined){
	// 		let releva_response = await Releva.post('product', data.id ,warehouse_id, productData, data.releva_info);
	// 	}else {
	// 			let releva_response = await Releva.post('product',data.id ,warehouse_id, Api.response);
	// 	}
	//
	//
	//
	// 	if(releva_response !== undefined){
	// 		for(const type of releva_response){
	//
	// 			if (type.name == 'Подобни'  && type.products.result.lenght != 0){
	// 				let i = 0;
	// 				let htmlButtons ='';
	//
	// 				for(const product of type.products.result){
	// 					let productSliderTemplate = document.getElementById('productRelevaSliderTemplate').content;
	// 					let json_data = JSON.stringify(product);
	// 					productSliderTemplate.querySelector('#productRelevaItemData').setAttribute('x-data', json_data);
	// 					let copy_html = document.importNode(productSliderTemplate, true);
	// 					document.getElementById('productSliderSimilar').appendChild(copy_html);
	// 					 htmlButtons +=`<button class="glide__bullet main_bullet" data-glide-dir="=`+i+`"></button>`;
	// 					i++;
	// 				}
	//
	// 				document.getElementById('sliderSimilarButtons').innerHTML = htmlButtons;
	// 				if(i > 0 ){
	// 					new Glide('#similarSlider', {
	// 						type: 'carousel',
	// 						startAt: 0,
	// 						perView: 3,
	// 						autoplay:false,
	// 						focus:'center',
	// 						breakpoints:{
	// 							1000: {
	// 					 		perView: 2,
	// 				 			},
	// 							550: {
	// 							perView: 1,
	// 							},
	// 						}
	// 					}).mount();
	// 				}
	//
	//
	// 			}
	//
	// 			if (type.name == 'Промо'  && type.products.result.lenght != 0){
	// 				let i = 0;
	// 				let htmlButtons ='';
	//
	// 				for(const product of type.products.result){
	// 					let productSliderTemplate = document.getElementById('productRelevaSliderTemplate').content;
	// 					let json_data = JSON.stringify(product);
	// 					productSliderTemplate.querySelector('#productRelevaItemData').setAttribute('x-data', json_data);
	// 					let copy_html = document.importNode(productSliderTemplate, true);
	// 					document.getElementById('productSliderPromo').appendChild(copy_html);
	// 					 htmlButtons +=`<button class="glide__bullet main_bullet" data-glide-dir="=`+i+`"></button>`;
	// 					i++;
	// 				}
	//
	// 				document.getElementById('sliderPromoButtons').innerHTML = htmlButtons;
	// 				if(i > 0 ){
	//
	// 										new Glide('#PromoSlider', {
	// 											type: 'carousel',
	// 											startAt: 0,
	// 											perView: 3,
	// 											autoplay:false,
	// 											focus:'center',
	// 											breakpoints:{
	// 												1000: {
	// 										 		perView: 2,
	// 									 			},
	// 												550: {
	// 												perView: 1,
	// 												},
	// 											}
	// 										}).mount();
	// 				}
	//
	//
	// 			}
	//
	// 			if (type.name == 'Други'  && type.products.result.lenght != 0){
	// 				let i = 0;
	// 				let htmlButtons ='';
	//
	// 				for(const product of type.products.result){
	// 					let productSliderTemplate = document.getElementById('productRelevaSliderTemplate').content;
	// 					let json_data = JSON.stringify(product);
	// 					productSliderTemplate.querySelector('#productRelevaItemData').setAttribute('x-data', json_data);
	// 					let copy_html = document.importNode(productSliderTemplate, true);
	// 					document.getElementById('productSliderOthers').appendChild(copy_html);
	// 					 htmlButtons +=`<button class="glide__bullet main_bullet" data-glide-dir="=`+i+`"></button>`;
	// 					i++;
	// 				}
	//
	// 				document.getElementById('sliderOthersButtons').innerHTML = htmlButtons;
	// 				if(i > 0 ){
	// 					new Glide('#OthersSlider', {
	// 						type: 'carousel',
	// 						startAt: 0,
	// 						perView: 3,
	// 						autoplay:false,
	// 						focus:'center',
	// 						breakpoints:{
	// 							1000: {
	// 					 		perView: 2,
	// 				 			},
	// 							550: {
	// 							perView: 1,
	// 							},
	// 						}
	// 					}).mount();
	// 				}
	//
	//
	//
	// 			}
	//
	// 		}
	// 	}
	// 	let divAbsolute = document.querySelectorAll('.divAbsolute');
	// 	for(const div of divAbsolute){
	// 		div.classList.add("absolute");
	// 	}
	//
	//
	//
	// },
	//

	//
	//
	//
	// setFilters: async function(data){
	//
	//
	// 	Alpine.updateDataFull('filterPagination', {'Pagination':data.pagination} );
	// 	Alpine.updateDataFull('filterPriceRange', {'PriceRange':data.price_range});
	//
	// 	// if(data.filters.tags.length > 0 ){
	// 		Alpine.updateDataFull('filterTags', {'Tags':data.filters.tags});
	// 	// }
	// 	// if(data.filters.countries.length > 0 ){
	// 		Alpine.updateDataFull('filterCountries', {'Countries':data.filters.countries});
	// 	// }
	//
	//
	//
	// },
	//
	// getFilters: async function(){
	// 	let element = document.getElementById('filters');
	// 	let data = Helpers.get_form_data(element.closest("form"));
	// 	data['limit'] = localStorage.getItem('LIMITPRODUCTS');
	// 	data['sort'] = localStorage.getItem('SORTPRODUCTS');
	//
	// 	return data;
	// },
	//
	//
	// aplyFilters: async function(){
	// 	window.scrollTo({top: 0, behavior: 'smooth'});
	// 	let filters = await Shop.getFilters();
	//
	// 	let endpoint = Helpers.combineRequest('products' , JSON.stringify(filters));
	// 	await Api.get(endpoint, false);
	// 	let data = Api.response;
	// 	data['result'] = await Shop.productInCart(data.result);
	//
	// 	Alpine.updateDataFull('products', {'Shop':data});
	// 	Alpine.updateDataFull('filterPagination', {'Pagination':data.pagination});
	//
	// },
	//
	//
	//
	// aplyFiltersWishlist: async function(){
	// 	window.scrollTo({top: 0, behavior: 'smooth'});
	// 	let filters = await Shop.getFilters();
	//
	// 	let endpoint = Helpers.combineRequest('wishlist' , JSON.stringify(filters));
	// 	// data['limit'] = localStorage.getItem('LIMITPRODUCTS');
	// 	// data['sort'] = localStorage.getItem('SORTPRODUCTS');
	// 	await Api.get(endpoint, false);
	// 	let data = Api.response;
	// 		data['result'] = await Shop.productInCart(data.result);
	// 	Alpine.updateDataFull('products', {'Shop':data});
	//
	// },
	//
	//
	// // ДА СЕ ОПРАВЯТ СТРАНИЦИТЕ ЗА WISHLIST
	//
	// change_pageWishlist: async function(element, data){
	// 	window.scrollTo({top: 0, behavior: 'smooth'});
	// 	let data = Helpers.get_form_data(element.closest("form"));
	//
	// 	// IF NEGATIVE PAGE DONT GET PRODUCTS
	// 	if( parseInt(data.current_page) == 1 && parseInt(data.page) <= 1) return;
	//
	// 	// IF	PAGE DONT EXIST DONT GET PRODUCTS
	// 	if( parseInt(data.page) > parseInt(data.total_pages) ) return;
	//
	// 	const filters = await Shop.getFilters();
	// 	filters['page'] = data.page;
	//
	// 	const filtersJSON = JSON.stringify(filters);
	// 	let endpoint = Helpers.combineRequest('wishlist' , filtersJSON);
	//
	// 	await Api.get(endpoint, false);
	// 	let data = Api.response;
	// 		data['result'] = await Shop.productInCart(data.result);
	// 	Alpine.updateDataFull('products', {'Shop':data});
	// 	Alpine.updateDataFull('filterPagination', {'Pagination':data.pagination});
	//
	// },
	//
	// change_page: async function(element, data){
	// 	window.scrollTo({top: 0, behavior: 'smooth'});
	// 	let data = Helpers.get_form_data(element.closest("form"));
	//
	// 	// IF NEGATIVE PAGE DONT GET PRODUCTS
	// 	if( parseInt(data.current_page) == 1 && parseInt(data.page) <= 1) return;
	//
	// 	// IF	PAGE DONT EXIST DONT GET PRODUCTS
	// 	if( parseInt(data.page) > parseInt(data.total_pages) ) return;
	//
	// 	const filters = await Shop.getFilters();
	// 	filters['page'] = data.page;
	//
	// 	const filtersJSON = JSON.stringify(filters);
	// 	let endpoint = Helpers.combineRequest('products' , filtersJSON);
	//
	// 	await Api.get(endpoint, false);
	// 	let data = Api.response;
	// 		data['result'] = await Shop.productInCart(data.result);
	// 	Alpine.updateDataFull('products', {'Shop':data});
	// 	Alpine.updateDataFull('filterPagination', {'Pagination':data.pagination});
	//
	// },
	//
	// changeCategory: async function (element, parameters){
	// 		let category_id = element.getAttribute('data-id');
	// 		let current_category = element.getAttribute('data-current-id');
	// 		let parent_category = element.getAttribute('data-parent-id');
	//
	// 		let data = {
	// 			"dropList" : 1
	// 		};
	//
	// 		if(category_id != current_category){
	// 			data['id'] = parseInt(category_id);
	// 		}
	//
	// 		if(category_id == current_category){
	// 				data['id'] = parseInt(parent_category);
	// 		}
	//
	//
	// 		data = JSON.stringify(data);
	// 		let endpoint = Helpers.combineRequest('category' , data);
	// 		await Api.get(endpoint, false);
	//
	//
	// 		if(Api.response){
	// 			let data = Api.response;
	// 			window.history.pushState("", "", data.url);
	// 			let objectData = await Shop.structured_category(Api.response);
	//
	// 			Alpine.updateDataFull('breadcrumb', {'Shop':data});
	// 			Alpine.updateDataFull('mainCategory', {'Shop':objectData});
	// 			Alpine.updateDataFull('subCategory', {'Shop':objectData});
	// 			console.log(objectData);
	// 			let limit = localStorage.getItem('LIMITPRODUCTS');
	// 			await Api.get('products?category_id='+data.id+'&subcategories=1&limit='+limit, false);
	// 			if(Api.response){
	// 				let productsResponse = Api.response;
	// 			}
	// 			let releletesponse = await Releva.post('category', data.id ,warehouse_id, productsResponse);
	//
	//
	// 			if(releletesponse[0]){
	// 				Shop.relevaSlider(releletesponse[0]['products']['result']);
	// 			}
	//
	//
	// 				// productsResponse['relevaProducts'] = releletesponse[0]['products']['result'];
	// 				// console.log(productsResponse);
	// 			Alpine.updateDataFull('products', {'Shop':productsResponse});
	// 			Shop.setFilters(productsResponse);
	// 		}
	//
	//
	//
	// },
	// relevaSlider: async function (products){
	// 	//
	// 	if(typeof glideSlider != 'undefined'){
	// 		try {
	// 			glideSlider.destroy();
	// 		} catch (error) {
	// 			// console.error(error);
	// 		}
	//
	//
	// 	}
	//
	// 	 document.getElementById('productCategoryRelevaSlider').innerHTML = "";
	// 	 document.getElementById('productCategoryRelevaButtons').innerHTML = "";
	// 	 document.getElementById('productCategoryRelevaButtons').classList.remove("glide__bullets");
	// 	 //
	// 	if(!products[0]){
	//
	// 		return;
	// 	}
	//
	// 	let i = 0;
	// 	let htmlButtons ='';
	// 	//
	// 	for(const product of products){
	// 		let productSliderTemplate = document.getElementById('productRelevaSliderTemplate').content;
	// 		let json_data = JSON.stringify(product);
	// 		productSliderTemplate.querySelector('#productRelevaItemData').setAttribute('x-data', json_data);
	//
	// 		let copy_html = document.importNode(productSliderTemplate, true);
	//
	// 		document.getElementById('productCategoryRelevaSlider').appendChild(copy_html);
	// 		 htmlButtons +=`<button class="glide__bullet main_bullet" data-glide-dir="=`+i+`"></button>`;
	// 		i++;
	// 	}
	//
	// 	document.getElementById('productCategoryRelevaButtons').innerHTML = htmlButtons;
	// 	document.getElementById('productCategoryRelevaButtons').classList.add("glide__bullets");
	// 	if(i > 0 ){
	//
	// 		window.glideSlider = new Glide('#categoryRelevaSlider', {
	// 		  type: 'carousel',
	// 		  startAt: 0,
	// 		  perView: 6,
	// 		  autoplay:false,
	// 		  focus:'center',
	// 		  breakpoints:{
	// 		    1000: {
	// 		    perView: 3,
	// 		    },
	// 		    550: {
	// 		    perView: 2,
	// 		    },
	// 		  }
	// 		});
	// 		glideSlider.mount();
	// 	}
	//
	//
	//
	// },
	//
	// structured_category: async function(data){
	// 		const objectData= {};
	// 		if(data.parent_id != 0){
	//
	// 			for(const parent of data.parents){
	// 				if(parent.parent_id == 0){
	// 					await Api.get('category/'+ parent.id, false);
	// 					let mainCategory = Api.response;
	// 					objectData['mainCategory'] = mainCategory;
	// 				}else {
	// 					await Api.get('category/'+ parent.id, false);
	// 					let subCategory = Api.response;
	// 					objectData['subCategory'] = subCategory;
	// 				}
	// 			}
	// 			if(!objectData['subCategory']){
	// 				data['all'] = true;
	// 				data['allUrl'] = data.url;
	// 				objectData['subCategory'] = data;
	// 			}
	// 		}else {
	// 			objectData['mainCategory'] = data;
	// 		}
	//
	// 		return objectData;
	// },
	//
	// get_category_structured: async function(parameters){
	//   const objectData= {};
	// 	let endpoint = Helpers.combineRequest('category' , parameters);
	// 		await Api.get(endpoint, false);
	//
	// 			if(Api.response){
	// 				let data = Api.response;
	// 				if(data.parent_id != 0){
	//
	// 					for(const parent of data.parents){
	// 						if(parent.parent_id == 0){
	// 							await Api.get('category/'+ parent.id, false);
	// 							let mainCategory = Api.response;
	// 							objectData['mainCategory'] = mainCategory;
	// 						}else {
	// 							await Api.get('category/'+ parent.id, false);
	// 							let subCategory = Api.response;
	// 							objectData['subCategory'] = subCategory;
	// 						}
	// 					}
	// 					if(!objectData['subCategory']){
	// 						data['all'] = true;
	// 						data['allUrl'] = data.url;
	// 						objectData['subCategory'] = data;
	// 					}
	// 				}else {
	// 					objectData['mainCategory'] = data;
	// 				}
	//
	// 				return objectData;
	// 			}
	// },
	//

	//
	//
	// // CUSTOM FUNCTION ONLY FOR PARKMART PROJECT
	// cart_stuctured: async function(products){
	//
	// 	if(	typeof products === 'undefined') return [];
	// 		const categories = {};
	// 		for(const product of products){
	// 				for(const cat of product.categories){
	// 						if(categories[cat.id] === undefined){
	// 							categories[cat.id] = {'id': cat.id, 'title': cat.title, 'products': []};
	// 						}
	// 						categories[cat.id].products.push(product);
	// 				}
	// 		}
	// 		return categories;
	// },
	// // CUSTOM FUNCTION ONLY FOR PARKMART PROJECT
	// // CUSTOM FUNCTION ONLY FOR PARKMART PROJECT
	// cart_stuctured2: async function(data){
	//
	// 		const categories = {};
	// 		const products = {};
	// 		let categories_map = data.categories_map_2;
	//
	// 		for (const product of data.products){
	// 			products[product.id] = product;
	// 		}
	//
	// 		for (const [key, value] of Object.entries(categories_map)) {
	// 			// categories[key]['title'] = categories_map.key.title;
	// 				// categories_map[key]['products2'] = [];
	// 				let products_array = [];
	// 				for(const productInCategory of value.products){
	// 						// categories_map[key]['products2'].push(products[productInCategory]);
	// 						products_array.push(products[productInCategory]);
	// 				}
	//
	// 					categories[key] = {'id': categories_map[key].id, 'title': categories_map[key].title, 'products': products_array};
	// 				// categories_map[key]['products'] = categories_map[key]['products2'];
	//
	// 		}
	//
	//
	// 		return categories;
	// },
	// RelevaCart: async function(data){
	// 	let releletesponse = await Shop.releva_open_cart(data);
	//   Shop.relevaSlider(releletesponse[0]['products']['result']);
	// 	//
	// },
	//
	// // CUSTOM FUNCTION ONLY FOR PARKMART PROJECT
	// releva_open_cart: async function(data){
	// 	let releletesponse = await Releva.post('cart',0 ,warehouse_id);
	// 	return releletesponse;
	// },
	// releva_open_homepage: async function(data){
	//
	// 	if(LOGGED_IN != 0 || localStorage.getItem('LOCATED') != undefined){
	// 		Releva.post('homepage',0 ,warehouse_id);
	// 	}
	// },




};



window.Shop = Shop;
