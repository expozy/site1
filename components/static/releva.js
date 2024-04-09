

const COREURL = localStorage.getItem('COREURL');
const SAASKEY = localStorage.getItem('SAAS_KEY');
const RELEVAID = localStorage.getItem('RELEVAID');

export const SITEURL = localStorage.getItem('SITEURL');
export const lang = localStorage.getItem('lang');


export let Releva = {

	post: async function (page, id, warehouse_id, response, releva_info) {


		let ids = [];

		if (page == 'search') {

			for (const product of response.products.result) {
				ids.push(product.id);
			}

		}

		else if (page == 'category') {
			for (const product of response.result) {
				ids.push(product.id);
			}

		} else {

			if (id > 0) {
				ids.push(id);
			}
		}

		var data = {
			relevaId: RELEVAID,
			page: page,
			ids: ids,
			id: id,
			warehouse_id: warehouse_id
		}


		if (releva_info != undefined) {
			data.releva_info = releva_info;
		}

		await Api.post('releva', data);
		/*var sesid = /SESS\w*ID=([^;]+)/i.test(document.cookie) ? RegExp.$1 : false;
		const conn = fetch('/core/classes/releva.php', {
					method: 'POST', // *GET, POST, PUT, DELETE, etc.
					mode: 'cors', // no-cors, *cors, same-origin
					cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
					headers: {
						'Content-Type': 'application/json',
						'authentication': 'basic '+SAASKEY,
						'authorization' : localStorage.getItem('token') ? 'bearer '+localStorage.getItem('token') : 'session '+sesid,
					},
					body: JSON.stringify(data)
					})
					.then(response => response.json().then( data => {
	
							this.response = data;
				}));
	
			return conn; */

		return Api.response;
	},
};


window.Releva = Releva;
