export let Shop = {

	put_carts: async function (data, options) {
		let response = [];

		let api = new ApiClass();

		await api.put('carts', data);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.put_carts';

		response = api.response;
		response['keyName'] = 'cart';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		return response;

	},
	post_carts: async function (data, options) {

		let response = [];

		let api = new ApiClass();

		await api.post('carts', data);
		if (!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';
		response = api.response;
		response['keyName'] = 'cart';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		return response;
	},
	get_cart: async function (data, options) {

		let endpoint = Helpers.combineRequest('cart', data);
		let api = new ApiClass();
		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.get_cart';


		let response = [];
		response.obj = api.response;
		response.keyName = 'cart';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;

	},
	delete_carts: async function (data, options) {
		let response = [];
		// CHECK DO WE HAVE data.id ELSE RETURN ERROR
		if (!("id" in data) && typeof (data.id) === "undefined") return { internalError: 0, msg: `No id is set for Shop.delete_cart` };

		let api = new ApiClass();
		await api.delete('carts/' + data.id, data);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.delete_cart';

		response['keyName'] = 'cart';
		response['obj'] = api.response.cart;
		if (options != undefined) {
			if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];
		}

		return response;
	},

	get_orders: async function (data, options) {
		let response = [];
		let endpoint = Helpers.combineRequest('orders', data);
		let api = new ApiClass();

		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.post_carts';

		response['keyName'] = 'orders';
		response['obj'] = api.response;

		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);


		return response;


	},


	get_combinations: async function (data, options) {

		let endpoint = Helpers.combineRequest('combinations', data);
		let api = new ApiClass();
		await api.get(endpoint, true);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.get_combinations';


		let response = [];
		response.obj = api.response;
		response.keyName = 'combinations';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},


	get_categories: async function (data, options) {

		// FIX BUG WITH CAPITAL LETTER
		if ("droplist" in data) {
			delete data['droplist'];
			data['dropList'] = true;
		}

		let endpoint = Helpers.combineRequest('categories', data);
		let api = new ApiClass();
		await api.get(endpoint, true);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.get_categories';

		let response = [];
		response['obj'] = api.response;
		response['keyName'] = 'categories';


		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},



	get_products: async function (data, options) {

		let response = [];

		response.keyName = 'products';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		if ("chnageurl" in options || (dataProxy[response.keyName] != undefined && "chnageurl" in dataProxy[response.keyName])) {
			if (!("initial" in options)) {
				delete dataProxy.pageUrl.page;

			}
			data = Object.assign({}, dataProxy.pageUrl, data);
			dataProxy.pageUrl = data;
		}


		let endpoint = Helpers.combineRequest('products', data);
		let api = new ApiClass();
		await api.get(endpoint, true);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.get_products';

		response.obj = api.response;

		if ("pagination" in response.obj) {
			response.obj.pagination['pagesArray'] = Helpers.pagination(response.obj.pagination['current_page'], response.obj.pagination['total_pages']);
		}


		if (options != undefined) {

			if ("chnageurl" in options || (dataProxy[response.keyName] != undefined && "chnageurl" in dataProxy[response.keyName])) {
				response['obj'].chnageurl = true;

				endpoint = endpoint.replace("products", "");
				// change Url With data parameters
				history.replaceState(null, null, window.location.pathname + endpoint);
			}

			if ('scroll' in options) {
				document.getElementById('main').scrollIntoView(true);
			}

			if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);
		}



		return response;

	},

	post_orders: async function (data, options) {

		let api = new ApiClass();
		await api.post('orders', data);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.post_orders';

		let response = [];

		response = api.response;

		if (api.response.status === 1) {
			response['keyName'] = 'order';
			response['url'] = '/bg/ordersummary';
		}


		return response;


	},
	post_wishlist: async function (data, options) {
		let response = [];
		let api = new ApiClass();

		await api.post('wishlist', data);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.post_wishlist';

		response = api.response;

		return response;
	},

	delete_wishlist: async function (data, options) {
		let response = [];

		// CHECK DO WE HAVE data.product_id ELSE RETURN ERROR
		if (!("product_id" in data) && typeof (data.product_id) === "undefined") return { internalError: 0, msg: `No product_id is set for Shop.delete_wishlist` };

		let api = new ApiClass();
		await api.delete('wishlist/' + data.product_id, data);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.delete_wishlist';

		response = api.response;

		return response;
	},




	get_wishlist: async function (data, options) {
		let response = [];

		response.keyName = 'wishlist';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		if ("chnageurl" in options || (dataProxy[response.keyName] != undefined && "chnageurl" in dataProxy[response.keyName])) {
			if (!("initial" in options)) {
				delete dataProxy.pageUrl.page;
			}
			data = Object.assign({}, dataProxy.pageUrl, data);
			dataProxy.pageUrl = data;
		}


		let endpoint = Helpers.combineRequest('wishlist', data);


		let api = new ApiClass();

		await api.get(endpoint, true);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.get_wishlist';

		response.obj = api.response;

		if ("pagination" in response.obj) {
			response.obj.pagination['pagesArray'] = Helpers.pagination(response.obj.pagination['current_page'], response.obj.pagination['total_pages']);
		}

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


	post_promocode: async function (data, options) {
		let response = [];
		let api = new ApiClass();

		await api.post('promocode', data);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.post_promocode';

		response = api.response;

		if (api.response.status == 1) {
			response.keyName = 'cart';
			response.obj = api.response.cart;
			if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];
		} else {
			response.keyName = 'empty';
		}


		return response;


	},


	delete_promocode: async function (data, options) {

		let response = [];

		let api = new ApiClass();
		await api.delete('promocode', data);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.delete_promocode';

		response['keyName'] = 'cart';
		response['obj'] = api.response.cart;

		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		return response;
	},

	delete_promocode: async function (data, options) {

		let response = [];

		let api = new ApiClass();
		await api.delete('promocode', data);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.delete_promocode';

		response['keyName'] = 'cart';
		response['obj'] = api.response.cart;

		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		return response;
	},

	delete_cart_combination: async function (data, options) {

		let response = [];

		let api = new ApiClass();
		await api.delete('cart_combination', data);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.delete_cart_combination';

		response['keyName'] = 'cart';
		response['obj'] = api.response.cart;

		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		return response;
	},

	put_orders: async function (data, options) {
		let response = [];
		let api = new ApiClass();

		await api.put('orders', data);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.put_orders';

		response = api.response;

		return response;
	},
	get_favourites: async function (data, options) {
		let response = [];

		response['keyName'] = 'favourites';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		let endpoint = Helpers.combineRequest('favourites', data);
		let api = new ApiClass();

		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.get_favourites';

		response['obj'] = api.response;

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},
	get_product_filters: async function (data, options) {
		let response = [];

		response['keyName'] = 'product_filters';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		let endpoint = Helpers.combineRequest('product_filters', data);
		let api = new ApiClass();

		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.get_product_filters';

		response['obj'] = api.response;

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},
	get_product_attributes: async function (data, options) {
		let response = [];

		response['keyName'] = 'product_attributes';
		if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

		let endpoint = Helpers.combineRequest('product_attributes', data);
		let api = new ApiClass();

		await api.get(endpoint, false);

		if (!api.response) return response['internalError'] = 'No response from api for Shop.get_product_attributes';

		response['obj'] = api.response;

		if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

		return response;
	},

};




window.Shop = Shop;
