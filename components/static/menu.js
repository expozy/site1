


export let Menu = {



	get_menu: async function (data, options) {

		var endpoint = Helpers.combineRequest('menu', data);
		let api = new ApiClass();
		await api.get(endpoint, true);
		let response = [];

		response['obj'] = api.response;
		response['keyName'] = 'menu';

		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];
		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	}




};


window.Menu = Menu;
