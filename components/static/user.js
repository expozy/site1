

export let User = {

	login: async function (data, options) {

		let response = [];
		let api = new ApiClass();
		await api.post('login', data);


		if (api.response.status === 1) {

			api.response.user['logged_in'] = true;
			response['keyName'] = 'user';
			response['obj'] = api.response.user
			response['url'] = '/';
			localStorage.setItem('token', api.response.token);


			fetch('/pages/editorLogin.php', {
				method: 'POST',
				cache: 'no-cache',
				credentials: 'same-origin',
				headers: {
					'Content-Type': 'application/json',
				},
				body: JSON.stringify({ login: 1, user: api.response.user, token: api.response.token })
			}).then(res => res.json()).then(function (response2) {

			});



			return response;
		} else {
			return api.response;
		}

	},

	post_users: async function (data, options) {
		let response = [];
		let api = new ApiClass();

		await api.post('users', data);

		if (!api.response) return response['internalError'] = 'No response from api for Users.post_users';

		response = api.response;

		return response;

	},

	put_accounts: async function (data, options) {
		let response = [];
		let api = new ApiClass();
		await api.put('accounts', data);

		if (!api.response) return response['internalError'] = 'No response from api for User.put_accounts';
		response['keyName'] = 'user';
		response = api.response;
		return response;
	},


	post_user_address: async function (data, options) {

		let response = [];
		let api = new ApiClass();
		await api.post('user_address', data);

		if (!api.response) return response['internalError'] = 'No response from api for User.post_user_address';

		response = api.response;

		if (api.response.status == 1) {
			response = await User.get_accounts();
		}

		return response;
	},

	delete_user_address: async function (data, options) {
		let response = [];

		// CHECK DO WE HAVE data.id ELSE RETURN ERROR
		if (!("id" in data) && typeof (data.id) === "undefined") return { internalError: 0, msg: `No id is set for Users.delete_user_address` };

		let api = new ApiClass();
		await api.delete('user_address/' + data.id, data);

		if (!api.response) return response['internalError'] = 'No response from api for Users.delete_user_address';

		response = api.response;

		if (api.response.status == 1) {
			response = await User.get_accounts();
		}
		return response;
	},


	get_accounts: async function (data, options) {
		let response = [];


		let api = new ApiClass();
		await api.get('accounts', false);

		if (!api.response) return response['internalError'] = 'No response from api for Users.get_accounts';

		if ("id" in api.response && (api.response.id) !== 0) api.response['logged_in'] = true;


		response['obj'] = api.response;
		response.keyName = 'user';

		// if("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];
		if (options != undefined) {
			if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);
		}


		return response;
	},

	post_forgot_password: async function (data, options) {

		let response = [];
		let api = new ApiClass();
		await api.post('forgot_password', data);

		if (!api.response) return response['internalError'] = 'No response from api for User.post_forgot_password';

		response = api.response;

		return response;
	},



};
window.User = User;
