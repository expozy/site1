

export let Invoices = {

	get_invoices: async function (data, options) {
		let response = [];

		let endpoint = Helpers.combineRequest('invoices', data);

		let api = new ApiClass();
		await api.get(endpoint, true);

		if (!api.response) return response['internalError'] = 'No response from api for Invoices.get_invoices';

		response['obj'] = api.response;
		response['keyName'] = 'invoices';

		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},




}

window.Invoices = Invoices;
