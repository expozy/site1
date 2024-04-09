

export let Providers = {


	get_providers: async function (data, options) {
		let response = [];

		response['keyName'] = 'providers';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		let endpoint = Helpers.combineRequest('providers', data);
		let api = new ApiClass();

		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Providers.get_providers';

		response['obj'] = api.response;

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},



};
window.Providers = Providers;
