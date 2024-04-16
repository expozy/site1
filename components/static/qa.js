

export let Qa = {


	get_qa: async function (data, options) {
		let response = [];

		response['keyName'] = 'qa';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		let endpoint = Helpers.combineRequest('qa', data);
		let api = new ApiClass();

		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for QA.get_qa';

		response['obj'] = api.response;

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},

	post_qa: async function (data, options) {
		let response = [];

		response['keyName'] = 'qa';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		let endpoint = Helpers.combineRequest('qa', data);
		let api = new ApiClass();

		await api.post(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for QA.post_qa';

		response = api.response;

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},


};
window.Qa = Qa;
