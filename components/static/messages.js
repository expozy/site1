import {ApiClass} from '../core/api/api.js';
import {Helpers} from '../core/helpers.js';

export let Messages = {

		get_messages: async function (data, options){
			let response = [];

			let endpoint = Helpers.combineRequest('messages' , data);

			let api = new ApiClass();
			await api.get(endpoint, true);

			if(!api.response) return response['internalError'] = 'No response from api for Messages.get_messages';

			response['obj'] = api.response;
			response['keyName'] = 'messages';

			if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

			if("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

			return response;
		},
    post_messages: async function (data, options){
      let response = [];

      let endpoint = Helpers.combineRequest('messages' , data);

      let api = new ApiClass();
      await api.post(endpoint, true);

      if(!api.response) return response['internalError'] = 'No response from api for Messages.post_messages';

      response = api.response;
      response['keyName'] = 'messages';

      if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

      if("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

      return response;
    },



}

window.Messages = Messages;
