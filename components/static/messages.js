

export let Messages = {

  get_messages_rooms: async function (data, options) {
    let response = [];

    let endpoint = Helpers.combineRequest('messages_rooms', data);

    let api = new ApiClass();
    await api.get(endpoint, false);

    if (!api.response) return response['internalError'] = 'No response from api for Messages.get_messages_rooms';

    response['obj'] = api.response;
    response['keyName'] = 'messages';

    if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

    if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

    return response;
  },

  get_messages: async function (data, options) {
    let response = [];

    let endpoint = Helpers.combineRequest('messages', data);

    let api = new ApiClass();
    await api.get(endpoint, true);

    if (!api.response) return response['internalError'] = 'No response from api for Messages.get_messages';

    response['obj'] = api.response;
    response['keyName'] = 'messages';

    if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

    if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

    return response;
  },
  post_messages: async function (data, options) {
    let response = [];

    if (data['text'] == undefined || data['text'] == '') return 0;

    let endpoint = Helpers.combineRequest('messages', data);

    let api = new ApiClass();
    await api.post(endpoint, data);

    if (!api.response) return response['internalError'] = 'No response from api for Messages.post_messages';

    response = api.response;
    response['obj'] = response['rooms'];
    response['keyName'] = 'messages';

    if ("keyName" in options && options['keyName'] != '' && options['keyName'] != null) response.keyName = options['keyName'];

    if ("initial" in options && options['initial'] == true) return Handler.responseHandler(response);

    return response;
  },



}

window.Messages = Messages;
