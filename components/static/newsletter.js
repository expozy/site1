import {ApiClass} from '../core/api/api.js';
import {Helpers} from '../core/helpers.js';


export let Newsletter = {


  post_newsletter: async function(data, options){

    let response = [];

    data['newsletter-checkbox'] = true;

    let api = new ApiClass();
		await api.post('subscribe', data);

    if(!api.response) return response['internalError'] = 'No response from api for Newsletter.post_newsletter';

    response = api.response;



  		return response;
	},


};
window.Newsletter = Newsletter;
