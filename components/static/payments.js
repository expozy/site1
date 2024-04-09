

export let Payments = {



	get_payment_methods: async function (data, options) {
		let response = [];

		response['keyName'] = 'payment_methods';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		let endpoint = Helpers.combineRequest('payment_methods', data);
		let api = new ApiClass();

		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Payments.get_payment_methods';

		response['obj'] = api.response;

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},



};
window.Payments = Payments;
