


export let Contacts = {

	post_contacts: async function (data, options) {
		let response = [];

		let api = new ApiClass();

		await api.post('contacts', data);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';

		response = api.response;

		// IF STATUS IS 1 -  CLEAR THE FORM AND SCROLL TO MAIN
		if (api.response.status == 1) response['clearForm'] = true;



		return response;

	},

};
window.Contacts = Contacts;
