
let Helpers = {


	get_form_data: function (form) {

		const object = {};


		for (let i = 0; i < form.elements.length; i++) {

			let e = form.elements[i];

			if (e.name != '') {

				if (e.name.endsWith('[]') && e.type != 'file') {
					if (object[e.name] !== undefined) {
						if (e.value != '') {
							object[e.name].push(e.value);
						}
					} else {
						if (e.value != '') {
							object[e.name] = [e.value];
						}
					}
				} else if (e.type == 'radio') {
					if (e.checked == true) {

						object[e.name] = e.getAttribute('value');
					}
				} else if (e.type == 'checkbox') {

					object[e.name] = e.checked;
				} else if (e.type == 'file') {

					if (e.files[1] != undefined) {
						// object[e.name+'[]'] = [];

						for (const file of e.files) {
							if (object[e.name] !== undefined) {
								object[e.name].push(file);
							} else {
								object[e.name] = [file];
							}
						}
					} else {
						object[e.name] = e.files[0];
					}


				} else {
					if (e.value != 'dontSelect') {
						// if(e.value != ''){
						object[e.name] = e.value;
						// }
					}
				}

			}


		}
		return object;
	},

	clear_form_data: function (form) {
		for (let i = 0; i < form.elements.length; i++) {
			let e = form.elements[i];
			e.value = '';
		}
		document.getElementById('main').scrollIntoView(true);
		return true;
	},

	show_errors: function (request) {


		Helpers.old_errors_remove();

		// Ако статус = 1 изписваме генерално съобщение
		if (request.status == 1 && request.msg) {
			Helpers.show_toast_msg(request.msg, 'success');
		}

		// При статус = 0 добавяме грешка към елемент или изписваме генерална грешка.
		if (request.status == 0 && request.errors) {

			for (const e in request.errors) {

				if (request.errors.hasOwnProperty(e)) {

					// Грешка за конкретен елемент
					if (e.includes('name=')) {
						let elements = document.querySelectorAll(e);
						for (const element of elements) {
							element.parentNode.innerHTML += '<span class="msg-error text-red-600 font-normal">' + `${request.errors[e]}` + '</span>';
						}
					}

					// Генерална грешка Тоаст
					else {
						Helpers.show_toast_msg(request.errors[e], 'error');
					}
				}

			}

		}

	},

	old_errors_remove: function () {

		const msg_errors = document.querySelectorAll('.msg-error');
		msg_errors.forEach(msg => {
			msg.remove();
		});

	},

	show_toast_msg: function (msg, type, time) {
		document.getElementById('notification').dispatchEvent(new CustomEvent('notice', { detail: { text: msg, type: type }, bubbles: true }));
	},

	combineRequest: function (functionCall, parameters) {

		let endpoint = functionCall;
		let url_parameters = '';

		const objParameters = parameters;
		for (const key in objParameters) {

			if (objParameters.hasOwnProperty(key)) {
				if (key === 'id') {
					endpoint += '/' + objParameters[key];
				} else {

					// CHECK DO WE HAVE OLD KEYS IN ENDPOINT AND REPLACE THEM
					const regex = new RegExp("[&?]" + key + "=\\w+", "g");

					endpoint = endpoint.replace(regex, '');
					if (Array.isArray(objParameters[key])) {
						let tmpKey = key;

						if (!key.endsWith('[]')) {
							tmpKey = `${key}[]`;
						}

						for (const value of objParameters[key]) {
							if (value != 'empty') {
								url_parameters += tmpKey + '=' + value + '&';
							}
						}
					} else {
						//
						if (objParameters[key] != 'empty' && objParameters[key] != '') {

							url_parameters += key + '=' + objParameters[key] + '&';
						}
					}


				}
			}
		}


		if (url_parameters != '') {
			if (!endpoint.includes('?')) {
				endpoint += '?' + url_parameters;
			} else {
				endpoint += '&' + url_parameters;
			}
		}

		if (endpoint.substr(endpoint.length - 1) === '&' || endpoint.substr(endpoint.length - 1) === '?') {
			endpoint = endpoint.slice(0, -1);
		}
		return endpoint;
	},

	pagination: function (selectedPage, totalPages) {
		let current = selectedPage,
			last = totalPages,
			delta = 2,
			left = current - delta,
			right = current + delta + 1,
			range = [],
			rangeWithDots = [],
			l;

		for (let i = 1; i <= last; i++) {
			if (i == 1 || i == last || i >= left && i < right) {
				range.push(i);
			}
		}

		for (let i of range) {
			if (l) {
				if (i - l === 2) {
					rangeWithDots.push(l + 1);
				} else if (i - l !== 1) {
					rangeWithDots.push('...');
				}
			}
			rangeWithDots.push(i);
			l = i;
		}

		return rangeWithDots;
	}





};

window.Helpers = Helpers