import {ApiClass} from './api/api.js';
import {Helpers} from './helpers.js';
import {Warehouse} from './warehouse.js';
import {Page} from './classes/page.js';


export let User = {

	login: async function(data){
		let response = [];
		let api = new ApiClass();
		await api.post('login', data);


			if(api.response.status === 1){

				api.response.user['logged_in'] = true;
				response['keyName'] = 'user';
				response['obj'] = api.response.user
				response['url'] = '/';
				localStorage.setItem('token', api.response.token);


				fetch('/editor/post.php', {
				 	method: 'POST', // *GET, POST, PUT, DELETE, etc.
				 	cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
				 	credentials: 'same-origin',
				 	headers: {
				 		'Content-Type': 'application/json',
				 	},
				 	body: JSON.stringify({login: 1, user: api.response.user, token: api.response.token})
				 }).then(res => res.json()).then(function(response2) {

				 });
				 
				 if(DEV_MODE){
					 window.location = '/';
				 }
				 
				return response;
			}else {
				return api.response;
			}

	},

	post_users: async function(data, options){
		let response = [];
		let api = new ApiClass();

		await api.post('users', data);

		if(!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';

		response = api.response;

		return response;

	},

	put_accounts: async function(data, options){
		let response = [];
		let api = new ApiClass();
		await api.put('accounts', data);

		if(!api.response) return response['internalError'] = 'No response from api for User.put_accounts';
		response['keyName'] = 'user';
		response = api.response;
		return response;
	},


	post_user_address: async function(data){

		let response = [];
		let api = new ApiClass();
		await api.post('user_address', data);

		if(!api.response) return response['internalError'] = 'No response from api for User.user_address';

		response = api.response;

		if(api.response.status == 1){
			response = await User.get_accounts();
		}

		return response;
	},

	delete_user_address: async function(data,options){
		let response = [];

		let api = new ApiClass();
		await api.delete('user_address/'+data.id, data);

			if(!api.response) return response['internalError'] = 'No response from api for Users.delete_user_address';

			response = api.response;

			if(api.response.status == 1){
				response = await User.get_accounts();
			}
			return response;
	},


	get_accounts: async function(data, options){
		let response = [];


		let api = new ApiClass();
		await api.get('accounts', false);

		if(!api.response) return response['internalError'] = 'No response from api for Users.get_accounts';

		if ("id" in api.response && (api.response.id) !== 0) api.response['logged_in'] = true;


		response['obj'] = api.response;
		response.keyName = 'user';

		if(options !== undefined){
			if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

			if("initial" in options && options['initial'] == true){
				return Handler.responseHandler(response);
			}
		}


			return response;

	},

	//
	//
	//
	// get_my_loyalty_card: async function(parameters){
	//
	// 	let endpoint = Helpers.combineRequest('my_loyalty_card' , parameters);
  //     await Api.get(endpoint, false);
  //       if(Api.response){
  //         let data = Api.response;
  //         return data;
  //       }
	// },
	//
	// post_login: async function(element,parameters){
	// 	let data = Helpers.get_form_data(element.closest("form"));
	// 	User.login(data);
	// },
	//
	// googleLogin: async function (googleUser){
	// 			let sesid = /SESS\w*ID=([^;]+)/i.test(document.cookie) ? RegExp.$1 : false;
	// 			(async () => {
	// 				const rawResponse = await fetch(COREURL+'login', {
	// 					method: 'POST',
	// 					headers: {
	// 						'Accept': 'application/json',
	// 						'Content-Type': 'application/json',
	// 						'authentication': 'basic '+SAASKEY,
	// 						'authorization' : 'session '+sesid
	// 					},
	// 					body: JSON.stringify({google: googleUser.credential})
	// 				});
	// 				const result = await rawResponse.json();
	//
	// 				if(result){
	//
	// 					localStorage.setItem('token', result.token);
	// 					localStorage.setItem('LOCATED', true);
	//
	//
	// 					let user = result;
	// 					await User.checkWhAndAddress(user.user);
	//
	// 					fetch('/editor/post.php', {
	// 						method: 'POST', // *GET, POST, PUT, DELETE, etc.
	// 						cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
	// 						credentials: 'same-origin',
	// 						headers: {
	// 							'Content-Type': 'application/json',
	//
	// 						},
	// 						body: JSON.stringify({login: 1, user: user.user, token: user.token})
	// 					}).then(res => res.json()).then(function(response) {
	// 						window.location = '/';
	// 					});
	// 				}
	//
	// 			})();
	//
	// 	},
	//
	//
	// facebookLogin: async function (){
	//
	// 		//LOGIN FUNCTION
	//
	// 		FB.login(function(response) {
	// 			if (response.authResponse) {
	//
	// 				let fbtoken = response.authResponse.accessToken;
	// 				let sesid = /SESS\w*ID=([^;]+)/i.test(document.cookie) ? RegExp.$1 : false;
	//
	// 				(async () => {
	// 					const rawResponse = await fetch(COREURL+'login', {
	// 						method: 'POST',
	// 						headers: {
	// 						'Accept': 'application/json',
	// 						'Content-Type': 'application/json',
	// 						'authentication': 'basic '+SAASKEY,
	// 						'authorization' : 'session '+sesid,
	// 						},
	// 						body: JSON.stringify({facebook: fbtoken})
	// 					});
	// 					const result = await rawResponse.json();
	//
	// 					if(result){
	//
	// 						localStorage.setItem('token', result.token);
	// 						localStorage.setItem('LOCATED', true);
	// 						let user = result;
	// 						await User.checkWhAndAddress(user.user);
	//
	// 						fetch('/editor/post.php', {
	// 							method: 'POST', // *GET, POST, PUT, DELETE, etc.
	// 							cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
	// 							credentials: 'same-origin',
	// 							headers: {
	// 								'Content-Type': 'application/json',
	// 							},
	// 							body: JSON.stringify({login: 1, user: user.user, token: user.token})
	// 						}).then(res => res.json()).then(function(response) {
	// 							window.location = '/';
	// 						});
	// 					}
	//
	// 				})();
	//
	//
	//
	//
	// 											//console.log(response.authResponse);
	// 				//alert('Success!');
	// 			}else{
	// 				//alert('Login Failed!');
	// 			}
	// 		}, {scope: 'email'});
	//
	//
	//
	//
	// 	},
	//
	// checkWhAndAddress: async function (user){
	//
	// 	//   await Api.get('accounts', true);
	// 	//
	// 	// if(Api.response){
	// 		// let user = Api.response;
	// 		//
	// 		if(user.addresses.length > 0){
	// 			if(user.addresses[0]['post_code'] != ''){
	//
	// 				await Warehouse.get_checkPostCode(user.addresses[0]['post_code'], '');
	// 				if(Api.response){
	// 					let whCheckResponse = Api.response;
	// 					if(whCheckResponse.status == 0) return;
	//
	// 					let address = user.addresses[0]['shipping_address'] + ' '+ user.addresses[0]['post_code'] + ' ' + user.addresses[0]['city'];
	//
	// 					if(whCheckResponse.status == 1){
	// 						localStorage.setItem('deliveryAddress', address);
	// 						localStorage.setItem('deliveryPostCode', user.addresses[0]['post_code']);
	// 						localStorage.setItem('trueAddress', true);
	// 						localStorage.setItem('deliveryCity', user.addresses[0]['city']);
	// 						localStorage.setItem('deliveryArea', user.addresses[0]['state']);
	// 					}
	// 					if(whCheckResponse.status == 2){
	// 							localStorage.setItem('deliveryAddress', address);
	// 							localStorage.setItem('WAREHOUSE_ID', whCheckResponse.warehouse.id);
	// 							localStorage.setItem('deliveryPostCode', user.addresses[0]['post_code']);
	// 							localStorage.setItem('trueAddress', true);
	// 							localStorage.setItem('deliveryCity', user.addresses[0]['city']);
	// 							 localStorage.setItem('deliveryArea', user.addresses[0]['state']);
	// 					}
	// 				}
	// 			}
	// 		}
	// 	// }
	//
	// },
	//
	//
	//
	//
	//
	// post_users_noRedirect: async function(data){
	// 	await Api.post('users', data);
	//
	// 	if(Api.response){
	// 		if(Api.response.status === 1){
	// 			return Api.response;
	// 		}else {
	// 			Helpers.show_errors(Api.response);
	// 		}
	// 	}
	// },
	//
	// post_forgot_password: async function(element, parameters){
	// 	let data = Helpers.get_form_data(element.closest("form"));
	// 	await Api.post('forgot_password', data);
	//
	// 	if(Api.response){
	// 		if(Api.response.status === 1){
	// 			Helpers.show_toast_msg(Api.response.msg, 'success');
	// 		}else {
	// 			Helpers.show_errors(Api.response);
	// 		}
	// 		return Api.response;
	// 	}
	// },
	//
	//
	//
	//
	// post_new_password: async function(element, parameters){
	// 	let data = Helpers.get_form_data(element.closest("form"));
	// 	await Api.post('new_password', data);
	//
	// 	if(Api.response){
	// 		if(Api.response.status === 1){
	// 			Helpers.show_toast_msg(Api.response.msg, 'success');
	// 		}else {
	// 			Helpers.show_errors(Api.response);
	// 		}
	// 	}
	// },
	//
	//
	// post_contact: async function(element,parameters){
	//
	// 	let data = Helpers.get_form_data(element.closest("form"));
	//
	// 	if(data.agree != 1){
	// 		Helpers.show_toast_msg('Трябва да сте съгласни с общите условия. ', 'error');
	// 		return;
	// 	}
	//
	// 	await Api.post('userContact', data);
	// 		if(Api.response){
	// 			if(Api.response.status === 1){
	// 				Helpers.old_errors_remove();
	// 				Helpers.clear_form_data(element.closest("form"));
	// 				Helpers.show_toast_msg(Api.response.msg, 'success');
	// 			}else {
	// 				Helpers.show_errors(Api.response);
	// 			}
	// 		}
	//
	// },
	//

	//
	// get_newsfeed: async function(parameters){
	//
	// 	let endpoint = Helpers.combineRequest('newsfeed' , parameters);
  //     await Api.get(endpoint, false);
  //       if(Api.response){
	// 				let data = Api.response;
	//
	// 				let date_newsfeed = new Date(Api.response.date_updated);
	// 				let newsfeed_close_date = localStorage.getItem('NEWSFEEEDDATE');
	// 				if(newsfeed_close_date != undefined && date_newsfeed > new Date(newsfeed_close_date)) {
	// 					data.showNewsfeed = true;
	// 				}
	//
  //         return data;
  //       }
	// },

};
window.User = User;
// window.googleLogin = User.googleLogin;
