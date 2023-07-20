
export let Helpers = {


	get_form_data: function(form){

    const object= {};


    for ( var i = 0; i < form.elements.length; i++ ) {

			var e = form.elements[i];

			if(e.name != ''){

				if(e.name.endsWith('[]')){
					if(object[e.name] !== undefined){
						if(e.value != ''){
							object[e.name].push(e.value);
						}
					}else {
						if(e.value != ''){
							object[e.name] = [e.value];
						}
					}
				}else if (e.type == 'radio'){
					if(e.checked == true){

						object[e.name] = e.getAttribute('value');
					}
				}else if (e.type == 'checkbox'){
					object[e.name] = e.checked;
				}else {
					if(e.value != 'dontSelect'){
						if(e.value != ''){
							object[e.name] = e.value;
						}
					}
				}

			}


		}
    return object;
  },

	clear_form_data: function(form){
		for ( var i = 0; i < form.elements.length; i++ ) {
			var e = form.elements[i];
			e.value = '';
		}
			document.getElementById('main').scrollIntoView(true);
		return true;
	},

	show_errors: function(request){

			Helpers.old_errors_remove();
			if(request.status == 1  &&  request.msg){
				Helpers.show_toast_msg(request.msg, 'success');
			}
			if(request.status == 0  &&  request.errors){
	      for(const e in  request.errors){

					var elements =  document.querySelectorAll(e);
					for(const element of elements){
						element.parentNode.innerHTML += '<span class="msg-error text-red-600 font-normal">'+`${request.errors[e]}`+'</span>';

					}
	      }
	    }else if(request.status == 0  &&  request.error_fields){
	      for(const e in  request.error_fields){

					var dataElement = document.querySelectorAll(e);
					if(dataElement[0]){
						dataElement[0].parentNode.innerHTML += '<span class="msg-error text-danger">'+`${request.error_fields[e]}`+'</span>';
					}

	      }
	    }else if(request.status == 0 && request.error){
				Helpers.show_toast_msg(request.error, 'error');
			}else if (request.status == 0 && request.msg) {
				Helpers.show_toast_msg(request.msg, 'error');
			}
		},

		old_errors_remove: function(){

			const msg_errors = document.querySelectorAll('.msg-error');
			msg_errors.forEach(msg => {
				msg.remove();
			});

		},

		show_toast_msg: function(msg, type, time){
			document.getElementById('notification').dispatchEvent(new CustomEvent('notice', { detail: { text: msg , type: type }, bubbles: true }));
		},

		combineRequest: function(functionCall, parameters){
			// debugger;
			if (functionCall.includes('?')) {
					var url_parameters = '&';
				}else {
							var url_parameters = '?';
				}

			var endpoint = functionCall;

			const objParameters = parameters;
			for (const key in objParameters) {
				if (objParameters.hasOwnProperty(key)) {
					if(key === 'id'){
						endpoint += '/' + objParameters[key];
					}else {

						// CHECK DO WE HAVE OLD KEYS IN ENDPOINT AND REPLACE THEM
						const regex = new RegExp("[&?]" + key + "=\\w+", "g");

						endpoint = endpoint.replace(regex, '');

						if(key.endsWith('[]')){
							for(const value of objParameters[key]){
								url_parameters += key + '=' + value + '&';
							}
						}else {
							// debugger;
							if(objParameters[key] != 'empty' && objParameters[key] != ''){
								url_parameters += key + '=' + objParameters[key] + '&';
							}
						}
					}
				}
			}

			endpoint += url_parameters;

			if(endpoint.substr(endpoint.length - 1) === '&' || endpoint.substr(endpoint.length - 1) === '?'){
				endpoint = endpoint.slice(0, -1);
			}
			return endpoint;
		},

		pagination: function (selectedPage, totalPages) {
			var current = selectedPage,
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
