

export let LoyaltyCard = {

	get_loyalty_card: async function (data, options) {
		let response = [];

		let endpoint = Helpers.combineRequest('loyalty_card', data);

		let api = new ApiClass();
		await api.get(endpoint, true);

		if (!api.response) return response['internalError'] = 'No response from api for LoyaltyCard.loyalty_card';

		response['obj'] = api.response;
		response['keyName'] = 'loyalty_card';

		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},
	get_my_loyalty_card: async function (data, options) {
		let response = [];

		let endpoint = Helpers.combineRequest('my_loyalty_card', data);

		let api = new ApiClass();
		await api.get(endpoint, true);

		if (!api.response) return response['internalError'] = 'No response from api for LoyaltyCard.get_my_loyalty_card';

		response['obj'] = api.response;
		response['keyName'] = 'my_loyalty_card';

		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},




}

window.LoyaltyCard = LoyaltyCard;
