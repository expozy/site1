


export let Newsletter = {


	post_newsletter: async function (data, options) {

		let response = [];

		data['newsletter-checkbox'] = true;

		let api = new ApiClass();
		await api.post('subscribe', data);

		if (!api.response) return response['internalError'] = 'No response from api for Newsletter.post_newsletter';

		response = api.response;

		return response;
	},

	delete_subscribe: async function (data) {
		let response = [];
		// CHECK DO WE HAVE data.id ELSE RETURN ERROR
		if (!("id" in data) && typeof (data.id) === "undefined") return { internalError: 0, msg: `No id is set for Newsletter.delete_subscribe` };

		let api = new ApiClass();
		await api.delete('subscribe/' + data.id, data);

		if (!api.response) return response['internalError'] = 'No response from api for Newsletter.delete_subscribe';


		response = api.response;

		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		return response;
	},





};
window.Newsletter = Newsletter;
