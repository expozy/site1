import {ApiClass} from '../core/api/api.js';
import {Helpers} from '../core/helpers.js';

export let Reviews = {


	post_product_comments: async function(data, options){

		let response = [];
		let endpoint = Helpers.combineRequest('product_comments' , data);

		let api = new ApiClass();
		await api.post(endpoint, data);

		response = api.response;

		return response;

	},


			get_product_comments: async function(data, options){
				let response = [];
				let endpoint = Helpers.combineRequest('product_comments' , data);
				let api = new ApiClass();

				await api.get(endpoint, false);

				if(!api.response) return response['internalError'] = 'No response from api for Reviews.product_comments';

				response['keyName'] = 'productReviews';
				response['obj'] = api.response;

				if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

				if("initial" in options && options['initial'] == true){
					// console.log(Handler.responseHandler(response));
					return Handler.responseHandler(response);
				}
		
				return response;


			},

};


window.Reviews = Reviews;
