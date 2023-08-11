import {ApiClass} from '../core/api/api.js';
import {Helpers} from '../core/helpers.js';
import {Warehouse} from './warehouse.js';


export let ShopppingList = {




	get_shoppingListSearch: async function(parameters){

		var queryString = window.location.search;
		var urlParams = new URLSearchParams(queryString);
		var list_id;
		urlParams.get('list_id') == null ?  list_id = 0 :  list_id = urlParams.get('list_id');


		var endpoint = Helpers.combineRequest('shoppingListSearch' , parameters);

      await Api.get(endpoint, false);
        if(Api.response){
          var data = Api.response;
					Shop.setFilters(data[list_id]['products']);
					data[list_id]['products']['result'] = await Shop.productInCart(data[list_id]['products']['result']);

          return data[list_id]['products'];
        }
	},

	change_list_id: async function(){

		var queryString = window.location.search;
		var urlParams = new URLSearchParams(queryString);
		var list_id;

		var sort = localStorage.getItem('SORTSEARCH');
		urlParams.get('list_id') == null ?  list_id = 0 :  list_id = urlParams.get('list_id');

			await Api.get('shoppingListSearch?filter_parent_cat=1&sort='+sort, false);
				if(Api.response){
					var data = Api.response;
					Shop.setFilters(data[list_id]['products']);
					data[list_id]['products']['result'] = await Shop.productInCart(data[list_id]['products']['result']);

					Alpine.updateDataFull('categories', {'ShopppingList':data[list_id]['products']});
					Alpine.updateDataFull('products', {'ShopppingList':data[list_id]['products']});
					Alpine.updateDataFull('filterPagination', {'Pagination':data[list_id]['products'].pagination});

					return data[list_id]['products'];
				}
	},
	getFilters: async function(){
		var element = document.getElementById('filters');
		var data = Helpers.get_form_data(element.closest("form"));
		data['limit'] = localStorage.getItem('LIMITPRODUCTS');
		data['sort'] = localStorage.getItem('SORTSEARCH');

		return data;
	},
	aplyFilters: async function(){
		window.scrollTo({top: 0, behavior: 'smooth'});
		var filters = await ShopppingList.getFilters();

		var endpoint = Helpers.combineRequest('shoppingListSearch' , JSON.stringify(filters));
		await Api.get(endpoint, false);
		var data = Api.response;

		var queryString = window.location.search;
		var urlParams = new URLSearchParams(queryString);
		var list_id;
		urlParams.get('list_id') == null ?  list_id = 0 :  list_id = urlParams.get('list_id');

		data[list_id]['products']['result'] = await Shop.productInCart(data[list_id]['products']['result']);

		Alpine.updateDataFull('products', {'ShopppingList':data[list_id]['products']});
		Alpine.updateDataFull('filterPagination', {'Pagination':data[list_id]['products'].pagination});

	},

	change_page: async function(element, data){
		window.scrollTo({top: 0, behavior: 'smooth'});
		var data = Helpers.get_form_data(element.closest("form"));

		// IF NEGATIVE PAGE DONT GET PRODUCTS
		if( parseInt(data.current_page) == 1 && parseInt(data.page) <= 1) return;

		// IF	PAGE DONT EXIST DONT GET PRODUCTS
		if( parseInt(data.page) > parseInt(data.total_pages) ) return;

		const filters = await ShopppingList.getFilters();
		filters['page'] = data.page;
		const filtersJSON = JSON.stringify(filters);
		var endpoint = Helpers.combineRequest('shoppingListSearch' , filtersJSON);

		await Api.get(endpoint, false);

		var queryString = window.location.search;
		var urlParams = new URLSearchParams(queryString);
		var list_id;
		urlParams.get('list_id') == null ?  list_id = 0 :  list_id = urlParams.get('list_id');

		var data = Api.response;

		Shop.setFilters(data[list_id]['products']);
		data[list_id]['products']['result'] = await Shop.productInCart(data[list_id]['products']['result']);

		Alpine.updateDataFull('products', {'ShopppingList':data[list_id]['products']});
		Alpine.updateDataFull('filterPagination', {'Pagination':data[list_id]['products'].pagination});

	},

	get_shoppingList: async function(parameters){
		// debugger;
		var endpoint = Helpers.combineRequest('shoppingList' , parameters);
      await Api.get(endpoint, false);
        if(Api.response){
          var data = Api.response;
				data['noEmpty'] = true;
				data['numberItems'] = Object.keys(data.words).length;
				data['items'] = [];

				var queryString = window.location.search;
				var urlParams = new URLSearchParams(queryString);
				var list_id;
				urlParams.get('list_id') == null ?  list_id = 0 :  list_id = urlParams.get('list_id');
				data['selected_id'] = list_id;

          return data;
        }
	},

	post_shoppingList: async function(element,parameters){

		var form = element.closest("form");

		var data = Helpers.get_form_data(element.closest("form"));

		data['words'] = data['words[]'];

		await Api.post('shoppingList', data , false);

		if(Api.response){
			if(Api.response.status === 1){
					Helpers.show_toast_msg("Вашият списък е запазен успешно", 'success');
			}else {
				Helpers.show_errors(Api.response);
			}
			return Api.response;
		}
	},




};
window.ShopppingList = ShopppingList;
