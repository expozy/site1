import {ApiClass} from '../core/api/api.js';
import {Helpers} from '../core/helpers.js';


let warehouse_id ;
// localStorage.getItem('WAREHOUSE_ID') ?  warehouse_id = parseInt(localStorage.getItem('WAREHOUSE_ID'))    :    warehouse_id = 1;


export let Countries = {

		get_countries: async function (data, options){
			let response = [];

			if("endpoint" in options && options['endpoint'] != '' && options['endpoint'] != null) apiEndpoint = options['endpoint'];
			let endpoint = Helpers.combineRequest('countries' , data);

			let api = new ApiClass();
			await api.get(endpoint, true);


			if(!api.response) return response['internalError'] = 'No response from api for Countries.countries';



			response['obj'] = api.response;



			if(options !== undefined){
				if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

				if("initial" in options && options['initial'] == true){
					return Handler.responseHandler(response);
				}
			}


				return response;

		},



}

window.Countries = Countries;
