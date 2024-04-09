

export let Memberships = {

	get_memberships: async function (data, options) {
		let response = [];

		let endpoint = Helpers.combineRequest('memberships', data);

		let api = new ApiClass();
		await api.get(endpoint, true);

		if (!api.response) return response['internalError'] = 'No response from api for Memberships.get_memberships';

		response['obj'] = api.response;
		response['keyName'] = 'memberships';

		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},



}

window.LoyaltyCard = LoyaltyCard;
