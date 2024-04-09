

export let Marketplace = {

	post_marketplace_orders: async function (data, options) {
		let response = [];

		let api = new ApiClass();

		await api.post('marketplace_orders', data);

		if (!api.response) return response['internalError'] = 'No response from api for Marketplace.post_marketplace_orders';
		response = api.response;

		response['keyName'] = 'marketplace_orders';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		return response;
	},


}

window.Marketplace = Marketplace;
