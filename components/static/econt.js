

export let Econt = {


	get_offices: async function (data, options) {
		let response = [];

		data['offices'] = true;

		let endpoint = Helpers.combineRequest('econt', data);

		let api = new ApiClass();
		await api.get(endpoint, true);


		if (!api.response) return response['internalError'] = 'No response from api for Econt.get_offices';


		response['keyName'] = 'econt_offices';
		response['obj'] = api.response;

		if (options !== undefined) {
			if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

			if ("initial" in options && options['initial'] == true) {
				return Handler.responseHandler(response);
			}
		}

		return response;

	},
}

window.Econt = Econt;
