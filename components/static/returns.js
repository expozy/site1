

export let Returns = {


	post_returns: async function (data, options) {
		let response = [];

		response['keyName'] = 'returns';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		let endpoint = Helpers.combineRequest('returns', data);
		let api = new ApiClass();

		await api.post(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Returns.post_returns';

		response = api.response;

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},


};
window.Returns = Returns;
