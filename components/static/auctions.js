

export let Auctions = {


	get_auctions: async function (data, options) {
		let response = [];

		response['keyName'] = 'auctions';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		let endpoint = Helpers.combineRequest('auctions', data);
		let api = new ApiClass();

		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Auctions.get_auctions';

		response['obj'] = api.response;

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},



};
window.Auctions = Auctions;
