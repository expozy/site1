

export let Banners = {


	get_banners: async function (data, options) {
		let response = [];

		response['keyName'] = 'banners';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		let endpoint = Helpers.combineRequest('banners', data);
		let api = new ApiClass();

		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Banners.get_banners';

		response['obj'] = api.response;

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},



};
window.Banners = Banners;
