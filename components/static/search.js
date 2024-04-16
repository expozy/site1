

export let Search = {

	get_search: async function (data, options) {

		let response = [];

		response.keyName = 'search';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];


		if ("chnageurl" in options || (dataProxy[response.keyName] != undefined && "chnageurl" in dataProxy[response.keyName])) {
			if (!("initial" in options)) {
				delete dataProxy.pageUrl.page;
			}
			data = Object.assign({}, dataProxy.pageUrl, data);
			dataProxy.pageUrl = data;
		}

		let endpoint = Helpers.combineRequest('search', data);
		let api = new ApiClass();
		await api.get(endpoint, true);
		if (!api.response) return response['internalError'] = 'No response from api for Search.get_search';


		if ("pagination" in api.response.products) {
			api.response.products.pagination['pagesArray'] = Helpers.pagination(api.response.products.pagination['current_page'], api.response.products.pagination['total_pages']);
		}

		response['obj'] = api.response;

		if ("chnageurl" in options || (dataProxy[response.keyName] != undefined && "chnageurl" in dataProxy[response.keyName])) {
			response['obj'].chnageurl = true;
			// change Url With data parameters
			history.replaceState(null, null, endpoint);
		}

		if ('scroll' in options) {
			document.getElementById('main').scrollIntoView(true);
		}

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);


		return response;

	},


}

window.Search = Search;
