import {Api} from './api/api.js';
import {Helpers} from './helpers.js';


let warehouse_id;
localStorage.getItem('WAREHOUSE_ID') ?  warehouse_id = parseInt(localStorage.getItem('WAREHOUSE_ID'))    :    warehouse_id = 1;

export let Warehouse = {
  get_warehouses: async function(parameters){
		var endpoint = Helpers.combineRequest('warehouses' , parameters);
      await Api.get(endpoint, true);
        if(Api.response){
          var data = {};
          data['warehouses'] = Api.response;
          for(const wh of Api.response){
            if(wh.id == warehouse_id){
              data['title'] = wh.title;
            }
          }
          return data;
        }
	},
  get_checkPostCode: async function(post_code, warehouse_id){
    //- post_code   //required
    // - warehouse_id //optional
    var data = {
      post_code: post_code,
    };

    if(warehouse_id != ''){
      data['warehouse_id'] = warehouse_id;
    }

    data = JSON.stringify(data);

    var endpoint = Helpers.combineRequest('checkPostCode' , data);
  	await Api.get(endpoint, true);

    if(Api.response){
      return Api.response;
    }

  },
  verifyAddress: async function(element,parameters){

    	var data = Helpers.get_form_data(element.closest("form"));

      const allReq = Object.values(data).every(x =>  x !== '');
  		if(allReq == false){
  			Helpers.show_toast_msg('Моля, попълнете всички полета', 'error');
  			return;
  		}

     await Warehouse.get_checkPostCode(data.post_code);
     if(Api.response){
       if(Api.response.status == 0){
         return Api.response;
       }

       if(Api.response.status == 1){
         localStorage.setItem('LOCATED', true);
         localStorage.setItem('deliveryAddress', data.shipping_address);
         localStorage.setItem('deliveryPostCode', data.post_code);

         if(data.city != ''){
           localStorage.setItem('deliveryCity', data.city);
         }
         if(data.area != ''){
           localStorage.setItem('deliveryArea', data.area);
         }
          return Api.response;
       }
       if(Api.response.status == 2){
         localStorage.setItem('LOCATED', true);
         localStorage.setItem('deliveryAddress', data.shipping_address);
         localStorage.setItem('WAREHOUSE_ID', Api.response.warehouse.id);
          localStorage.setItem('deliveryPostCode', data.post_code);
          if(data.city != ''){
            localStorage.setItem('deliveryCity', data.city);
          }
          if(data.area != ''){
            localStorage.setItem('deliveryArea', data.area);
          }

         Shop.delete_cartAllProducts();
         location.href = "/";
       }
     }
  },
  checkDeliveryByCode: async function(element,parameters){
		var data = Helpers.get_form_data(element.closest("form"));
    localStorage.removeItem('tempAddress');
    localStorage.removeItem('deliveryPostCode');
    localStorage.removeItem('deliveryCity');
    localStorage.removeItem('deliveryArea');

    if(data.address != ''){
      localStorage.setItem('tempAddress', data.address);
    }
    if(data.post_code != ''){
      localStorage.setItem('deliveryPostCode', data.post_code);
    }

    if(data.city != ''){
      localStorage.setItem('deliveryCity', data.city);
    }

    if(data.area != ''){
      localStorage.setItem('deliveryArea', data.area);
    }

    	Alpine.updateDataFull('addressVerification', {'open': true});

    var status = 1;
    return status;


	},
  get_warehouseByCode: async function(element,parameters){

		var data = Helpers.get_form_data(element.closest("form"));

     await Warehouse.get_checkPostCode(data.post_code);

     if(Api.response){
       if(Api.response.status != 0){
         localStorage.setItem('deliveryAddress', data.address);
       }
     }

    var endpoint = Helpers.combineRequest('warehouseByCode' , JSON.stringify(data));
		await Api.get(endpoint, true);

		if(Api.response){

      if(Api.response.id){
        localStorage.setItem('LOCATED', true);
        localStorage.setItem('WAREHOUSE_ID', Api.response.id);
        location.href = "/";
      }
		}

	},

  change_warehouse: async function(element,parameters){

    var data = Helpers.get_form_data(element.closest("form"));
    await Api.get('cart', false, '' , false);
    var cart = Api.response;

    if(cart.products.length != 0 && warehouse_id != data.warehouse_id){
      Alpine.updateDataFull('warehouseNotify', {'showWarehouseNotifyModal': true , 'warehouseID': data.warehouse_id});

    }else {
      localStorage.removeItem('deliveryAddress');
      localStorage.removeItem('deliveryArea');
      localStorage.removeItem('deliveryCity');
      localStorage.removeItem('deliveryPostCode');
      localStorage.setItem('WAREHOUSE_ID', parseInt(data.warehouse_id));
      localStorage.setItem('LOCATED', true);

      location.reload();
    }

	},

  change_warehouse_full : async function(element,parameters){
      var data = Helpers.get_form_data(element.closest("form"));
      Shop.delete_cartAllProducts();
      localStorage.removeItem('deliveryAddress');
      localStorage.removeItem('deliveryArea');
      localStorage.removeItem('deliveryCity');
      localStorage.removeItem('deliveryPostCode');
      localStorage.setItem('WAREHOUSE_ID', parseInt(data.warehouse_id));
      localStorage.setItem('LOCATED', true);
      location.reload();
  }



};

window.Warehouse = Warehouse;
