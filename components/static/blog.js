


export let Blog = {

	get_blogPosts: async function (data, options) {

		let response = [];

		response.keyName = 'blogPosts';

		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		if ("chnageurl" in options || (dataProxy[response.keyName] != undefined && "chnageurl" in dataProxy[response.keyName])) {
			if (!("initial" in options)) {
				delete dataProxy.pageUrl.page;
			}
			data = Object.assign({}, dataProxy.pageUrl, data);
			dataProxy.pageUrl = data;
		}


		let endpoint = Helpers.combineRequest('blogPosts', data);

		let api = new ApiClass();
		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Blog.get_blogPosts';

		response['obj'] = api.response;

		if ("pagination" in response.obj) {
			response.obj.pagination['pagesArray'] = Helpers.pagination(response.obj.pagination['current_page'], response.obj.pagination['total_pages']);
		}

		if ("chnageurl" in options || (dataProxy[response.keyName] != undefined && "chnageurl" in dataProxy[response.keyName])) {
			response['obj'].chnageurl = true;

			endpoint = endpoint.replace(response.keyName, "");
			history.replaceState(null, null, endpoint);
		}

		if ('scroll' in options) {
			document.getElementById('main').scrollIntoView(true);
		}

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},

	get_blogCategories: async function (data, options) {
		let response = [];

		response['keyName'] = 'blogCategories';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		let endpoint = Helpers.combineRequest('blogCategories', data);
		let api = new ApiClass();

		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Blog.get_blogCategories';

		response['obj'] = api.response;

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},

	get_blogPosts_filters: async function (data, options) {
		let response = [];

		response['keyName'] = 'blogPosts_filters';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		let endpoint = Helpers.combineRequest('blogPosts_filters', data);
		let api = new ApiClass();

		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Blog.blogPosts_filters';

		response['obj'] = api.response;

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},





};
window.Blog = Blog;
