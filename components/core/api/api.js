(async function () {
	await import(`./cache.js?v=${JS_VERSION}`);
})();


export const lang = LANG;
let warehouse_id = 1;
export const currency = localStorage.getItem('currency');



export class ApiClass {
	constructor() {
		this.response = null;
		this.age = null;
	}

	async get(endpoint, cachable) {

		let conn;
		let tmp = endpoint.split('?');
		let url;
		if (currency != undefined) {
			url = COREURL + tmp[0] + '?lang=' + lang + '&currency=' + currency;
		} else {
			url = COREURL + tmp[0] + '?lang=' + lang;
		}


		if (tmp[1] !== undefined) {
			url += '&' + tmp[1];
		}



		this.response = null;
		this.statusCode = null;

		const data = await cacheGet(url);

		//debugger;
		if (cachable === true && typeof data === 'object' && data !== null && Object.keys(data).length > 0) {


			this.response = data;

			conn = Promise;
		} else {

			var sesid = /SESS\w*ID=([^;]+)/i.test(document.cookie) ? RegExp.$1 : false;
			conn = fetch(url, {
				method: 'GET', // *GET, POST, PUT, DELETE, etc.
				mode: 'cors', // no-cors, *cors, same-origin
				cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
				headers: {
					'Content-Type': 'application/json',
					'authentication': 'basic ' + SAAS_KEY,
					'authorization': localStorage.getItem('token') ? 'bearer ' + localStorage.getItem('token') : 'session ' + sesid,
				},
			}).then(response => response.clone().json().then(data => {
				this.response = data;
				this.statusCode = response.status;
				cacheSet(url, this.response);

				if (data.redirect) {
					location.href = data.redirect;
				}




			}));


		}

		return conn;
	}

	delete(endpoint, data) {
		this.response = null;
		this.statusCode = null;
		// data['warehouse_id'] = warehouse_id;

		var sesid = /SESS\w*ID=([^;]+)/i.test(document.cookie) ? RegExp.$1 : false;

		const conn = fetch(COREURL + endpoint, {
			method: 'DELETE', // *GET, POST, PUT, DELETE, etc.
			mode: 'cors', // no-cors, *cors, same-origin
			cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
			headers: {
				'Content-Type': 'application/json',
				'authentication': 'basic ' + SAAS_KEY,
				'authorization': localStorage.getItem('token') ? 'bearer ' + localStorage.getItem('token') : 'session ' + sesid,
			},
			body: JSON.stringify(data)
		})
			.then(response => response.json().then(data => {
				this.response = data;
				this.statusCode = response.status;
				if (data.redirect) {
					location.href = data.redirect;
				}
				if (this.response.errors) {
					let errors = this.response.errors;
					for (var e in errors) {
						console.log("Error: " + errors[e] + " : " + e);
					}
				}
				if (this.response.msg) {
					console.log("Error: " + this.response.msg);
				}
			}));



		return conn;
	}

	post(endpoint, data) {
		this.response = null;
		this.statusCode = null;

		let tmp = endpoint.split('?');
		let url = COREURL + tmp[0] + '?lang=' + lang;
		// let url = COREURL +tmp[0]+'?lang='+lang+ '&warehouse_id='+warehouse_id;
		if (tmp[1] !== undefined) {
			url += '&' + tmp[1];
		}
		// data['warehouse_id'] = warehouse_id;
		var sesid = /SESS\w*ID=([^;]+)/i.test(document.cookie) ? RegExp.$1 : false;

		var formData = new FormData;
		if (data instanceof FormData) {
			formData = data;
		} else if (typeof data === 'object') {
			for (var key in data) {


				if (Array.isArray(data[key])) {
					if (data[key][0].constructor.name === 'File') {
						for (const file of data[key]) {
							formData.append(key + '[]', file);
						}
					}
				} else {

					formData.append(key, data[key]);
				}

			}
		} else {
			formData = JSON.stringify(data);
			headers['Content-Type'] = 'application/json';
		}

		const conn = fetch(url, {
			method: 'POST', // *GET, POST, PUT, DELETE, etc.
			mode: 'cors', // no-cors, *cors, same-origin
			cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
			headers: {
				'authentication': 'basic ' + SAAS_KEY,
				'authorization': localStorage.getItem('token') ? 'bearer ' + localStorage.getItem('token') : 'session ' + sesid,
			},
			body: formData
		})
			.then(response => response.json().then(data => {
				this.response = data;
				this.statusCode = response.status;
				if (data.redirect) {
					location.href = data.redirect;
				}
				if (this.response.errors) {
					let errors = this.response.errors;
					for (var e in errors) {
						console.log("Error: " + errors[e] + " : " + e);
					}
				}
				if (this.response.msg) {
					console.log("Error: " + this.response.msg);
				}
			}));



		return conn;
	}


	async put(endpoint, data) {
		this.response = null;
		this.statusCode = null;

		var sesid = /SESS\w*ID=([^;]+)/i.test(document.cookie) ? RegExp.$1 : false;
		data['warehouse_id'] = warehouse_id;

		var formData = new FormData;
		if (data instanceof FormData) {
			formData = data;
		} else if (typeof data === 'object') {
			for (var key in data) {


				if (Array.isArray(data[key])) {
					if (data[key][0].constructor.name === 'File') {
						for (const file of data[key]) {
							formData.append(key + '[]', file);
						}
					}
				} else {

					formData.append(key, data[key]);
				}

			}
		} else {
			formData = JSON.stringify(data);
			headers['Content-Type'] = 'application/json';
		}

		const conn = fetch(COREURL + endpoint, {
			method: 'PUT', // *GET, POST, PUT, DELETE, etc.
			mode: 'cors', // no-cors, *cors, same-origin
			cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
			headers: {
				'authentication': 'basic ' + SAAS_KEY,
				'authorization': localStorage.getItem('token') ? 'bearer ' + localStorage.getItem('token') : 'session ' + sesid,
			},
			body: JSON.stringify(data)
		})
			.then(response => response.json().then(data => {
				this.response = data;
				this.statusCode = response.status;
				if (data.redirect) {
					location.href = data.redirect;
				}
				if (this.response.errors) {
					let errors = this.response.errors;
					for (var e in errors) {
						console.log("Error: " + errors[e] + " : " + e);
					}
				}
				if (this.response.msg) {
					console.log("Error: " + this.response.msg);
				}
			}));



		return conn;
	}

};

// let Api = new ApiClass();

window.api = new ApiClass();
window.ApiClass = ApiClass;
// window.api = Api; 
