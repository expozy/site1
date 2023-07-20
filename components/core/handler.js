export let Handler = {

   responseHandler :function(response){
    // IF WE DONT HAVE KEYNAME CHECK IN RESPONSE
    if (!("keyName" in response) || response.keyName == '' || response.keyName == null) return console.error(`Get Method without keyName ` );
    dataProxy[response.keyName] = response.obj;

    return true;

  }
};

window.Handler = Handler;
