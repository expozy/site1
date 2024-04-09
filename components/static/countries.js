

export let Countries = {

	get_countries: async function (data, options) {
		let response = [];

		let endpoint = Helpers.combineRequest('countries', data);

		let api = new ApiClass();
		await api.get(endpoint, true);

		if (!api.response) return response['internalError'] = 'No response from api for Countries.get_countries';

		response['obj'] = api.response;
		response['keyName'] = 'countries';

		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},




}

window.Countries = Countries;
