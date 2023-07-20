import {ApiClass} from '../core/api/api.js';
import {Helpers} from '../core/helpers.js';


export let Blog = {

	get_blogPosts: async function(data, options){

		var endpoint = Helpers.combineRequest('blogPosts' , data);

		let api = new ApiClass();
		await api.get(endpoint, false);

		if(!api.response) return response['internalError'] = 'No response from api for Blog.get_blogPosts';


					let response = [];
					api.response['endpoint'] = endpoint;
					response['obj'] = api.response;
					response['keyName'] = 'blogPosts';

					if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];
					if("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

					// ChANGE THE PAGE DATA AND URL IF WE ARE ON CATEGORY PAGE
					// if(PAGE_TYPE === 'blog' && ("category_id" in data && typeof(data.category_id) !== "undefined" && data.category_id != dataBody.corePage.id) ){
						// await Blog.get_blogCategories({id:data.category_id});
						// if(Api.response){
						// 	let categoryResponse = Api.response;
						// 	window.history.pushState("", "", categoryResponse.url);
						// 	dataBody.corePage.title = categoryResponse.title;
						// 	dataBody.corePage.url = categoryResponse.url;
						// 	dataBody.corePage.id = categoryResponse.id;
						// 	dataBody.corePage.slug = categoryResponse.slug;

						// }
					// }

					return response;


	},



	get_blogCategories: async function(data, options){

		var endpoint = Helpers.combineRequest('blogCategories' , data);
		let api = new ApiClass();

		await api.get(endpoint, false);

        if(api.response){

					let response = [];
					api.response['endpoint'] = endpoint;
					response['obj'] = api.response;
					response['keyName'] = 'blogCategories';

					if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];
					if("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

					return response;

        }
	},

	change_page:async function(data, options){
		//
	if (!("page" in data) && typeof(data.page) === "undefined") return {internalError: 0, msg:`No page for Blog.change_page`};

	// CHECK DO WE HAVE ENDPOINT ELSE RETURN ERROR
	// if (!("endpoint" in data) && typeof(data.endpoint) === "undefined") return {internalError: 0, msg:`No endpoint is set for Blog.change_page`};


	// REMOVE LAST PAGE IF WE HAVE
	// let endpoint = data['endpoint'].replace(/([&?])page=\d+/g, '');

	let endpoint = window.location.href.replace(/([&?])page=\d+/g, '');

	// ADD THE NEW PAGE
	endpoint +=`&page=${data.page}`;

	let api = new ApiClass();

	await api.get(endpoint , false);

	if(api.response){
		let response = [];

		api.response['endpoint'] = endpoint;
		response['keyName'] = 'blogPosts';
		response['obj'] = api.response;
		document.getElementById('main').scrollIntoView(true);
		return response;
	}
},


};
window.Blog = Blog;
