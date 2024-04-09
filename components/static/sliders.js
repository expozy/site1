

export let Sliders = {

	get_sliders: async function (data, options) {
		let response = [];

		response['keyName'] = 'sliders';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		let endpoint = Helpers.combineRequest('sliders', data);
		let api = new ApiClass();

		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Sliders.get_sliders';

		response['obj'] = api.response;

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},
	get_sliders_sections: async function (data, options) {
		let response = [];

		response['keyName'] = 'sliders_sections';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		let endpoint = Helpers.combineRequest('sliders_sections', data);
		let api = new ApiClass();

		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Sliders.get_sliders_sections';

		response['obj'] = api.response;

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},


}


window.Sliders = Sliders;
