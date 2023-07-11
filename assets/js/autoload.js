import {Helpers} from './helpers.js';
import {Blog} from './blog.js';
import {Menu} from './menu.js';
import {Shop} from './shop.js';
import {Newsletter} from './newsletter.js';
import {Reviews} from './reviews.js';
import {User} from './users.js';
import {Search} from './search.js';
import {Countries} from './countries.js';
import {Page} from './classes/page.js';
import {Api} from './api/api.js';
import {Contacts} from './contacts.js';


let updatedMain = false;

// GLOBAL DATA OBJECT
let dataBody = {
  corePage:PAGEINIT,
  user:USER,
  // settings:{logo:LOGO_URL, social:SOCIAL_NETWORKS},
  settings:{
    limitProducts : localStorage.getItem('LIMITPRODUCTS'),
    sortProducts: localStorage.getItem('SORTPRODUCTS'),
    limitSearch : localStorage.getItem('LIMITSEARCH'),
    sortSearch: localStorage.getItem('SORTSEARCH'),
    limitBlog : localStorage.getItem('LIMITBLOG'),
    sortBlog: localStorage.getItem('SORTBLOG'),
  },
  openCart:false,
  openMobileMenu:false
};


// const fn = () => console.log('we capture a change');
const dataProxy = new Proxy(dataBody, {
  set (target, property, value) {
    if(property == 'corePage'){
      updatedMain = false;
      value.html += '<div x-init="callBackMain()"></div>';
    }

    target[property] = value;
    document.getElementById('body').dispatchEvent(new CustomEvent('update', { detail: {value:value, property:property }, bubbles: true }));
    return true;

  }

});


window.dataProxy = dataProxy;

function callBackMain(){
  if(updatedMain == true) return;
    callApiData();
    updatedMain = true;

}

window.callBackMain = callBackMain;



/* START INIT BODY DATA METHOD

   Body data is equal of Global variable "dataBody"

   We can call updatedata method to refresh the page with new data.*/

document.addEventListener('alpine:init', () => {
  window.dataset =  function () {
    return {
      data:dataBody,
      lang:_LANG,
      updatedata(data, keyName){
        if(data['property']){
          this.data[data['property']] = {};
          this.data[data['property']] = data.value;
        }else {
          this.data = {};
          this.data = dataBody;
        }
      },
    };

  };


  Alpine.nextTick(() => {
    console.log('Промените по дизайна са свършени.');
    // Тук можете да добавите своя код, който да изпълни след завършването на промените по дизайна
  });

});

// END INIT BODY DATA METHOD


loadData();

 async function loadData(){
   let corePage = await Page.get();
   dataProxy['corePage'] = corePage;
}

// END INITITIAL DATA ON PAGE LOAD


function callApiData(){
    let apiDataElements = document.querySelectorAll('[apiData]');

  for(const element of apiDataElements){
    let functionCall = element.getAttribute('apiData');
    let keyName = element.getAttribute('keyName');
    let parameters = getDataAttributes(element, 'data');

    let options = [];
    options['keyName'] = keyName;
    options['initial'] = true;

      try {
       eval(functionCall + '(parameters, options)');
      } catch(error) {
        console.error('Failed to get Data' + error);
      }

  }

  setTimeout(function() {
    replaceImages();
}, 1000);

}

window.callApiData = callApiData;

/*

  START ALPINE CLICK EVENT LISTENER
  @click="alpineListeners('Shop.change_page' , $event.target)"
  2 parameters needed. Method and Element who trigger event.
  Call the method and set data response to body.
  Can extract json data from closest form or attributes wich start with "data-"
*/

async function  alpineListeners(method, element){

try {
  element.preventDefault();
} catch (e) {

}

  if(element.currentTarget !== null && element.currentTarget !== undefined){
    if(element.target.tagName == 'SELECT'){
      element = element.target.options[element.target.selectedIndex];
    }else {
      element = element.currentTarget;
    }
  }else if( element.srcElement !== null && element.srcElement !== undefined){
    element = element.srcElement;
  }









  let data= {};
  let options = {};
  let keyName = '';

  // WE DONT HAVE A METHOD SET IN EVENT TARGET
  if(method == null) return console.error(`Method is not set` );
  if(element != undefined){
    let formData;
    let attributesData;
    // GET DATA FROM CLOSEST FORM IF HAVE
    if(element.closest("form") != null){
      formData = Helpers.get_form_data(element.closest("form"));
      data = Object.assign(data, formData);
    }

    // GET ALL ELEMENT ATTRIBUTES WICH START WITH DATA-
    attributesData = getDataAttributes(element, 'data');
    data = Object.assign(data, attributesData);
    // keyName = element.getAttribute('keyName');

      options = getDataAttributes(element, 'options');

    // Get keyName if we have
    if(element.getAttribute('keyName') != undefined){
      keyName = element.getAttribute('keyName');


      // If we dont have endpoint in event target get endpoint from dataBody if we have.
      if(data['endpoint'] === undefined && (keyName in dataBody && dataBody[keyName]['endpoint'] != undefined )){

        if (dataBody[keyName]){
          options['endpoint'] = dataBody[keyName]['endpoint'];
        }

      }
    }

  }


  let response = await eval(method + '(data, options)');

  if(response != undefined){

    //PREDIFENED ERRORS WITH LOGIC IN METHODS
    if ("internalError" in response && typeof(response.internalError) !== "undefined") return console.error(`InternalError \n alpineListeners : ${method} \n Error : ${response.msg}` );

    // IF WE HAVE STATUS SHOW SUCCESS OR ERRORS MSGS
    if ("status" in response) Helpers.show_errors(response);

    // Clear form an scroll to main 
    if ("clearForm" in response && element.closest("form") != undefined) Helpers.clear_form_data(element.closest("form"));

    // IF WE DONT HAVE ELEMENT KEYNAME CHECK  KEYNAME IN RESPONSE.
    if (("keyName" in response && typeof(response.keyName) !== "") && response.keyName != '') keyName = response.keyName;

    // IF WE HAVE KEYNAME AT END SET RESPONSE TO GLOBAL DATA AND UPDATE
    if(keyName != ''  ){
      dataProxy[keyName] = response.obj;
    }


    // IF WE HAVE REDIRECT URL
    if ("url" in response) return forceChange(response.url) ;

  }else {
    console.error(`no response for alpineListeners /n ${method}`);
  }

}

window.alpineListeners = alpineListeners;

// END  ALPINE CLICK EVENT LISTENER

async function forceChange(url){
    history.pushState(null, null, url);
    Page.load();
    document.getElementById('main').scrollIntoView(true);
}

window.forceChange = forceChange;

// GET ALL ATTRIBUTES OF ELEMENT WICH START WITH " DATA- "

function getDataAttributes(element, prefix){
  const dataAttrs = element.getAttributeNames().reduce((obj, name) => {
    if (name.startsWith(prefix +'-')) {
      return  {...obj, [name.slice(name.indexOf('-') + 1)]: element.getAttribute(name)};
    }
    return obj;
  }, {});
  return dataAttrs;
}

// END GET ALL ATTRIBUTES OF ELEMENT WICH START WITH " DATA- "



document.addEventListener('alpine:init', () => {
  window.notification =  function () {
    return {
        notices: [],
        visible: [],
        add(notice) {
            notice.id = Date.now();
            notice.type = notice.type;
            this.notices.push(notice);
            this.fire(notice.id);
        },
        fire(id) {
            this.visible.push(this.notices.find(notice => notice.id == id))
            const timeShown = 3000 * this.visible.length
            setTimeout(() => {
                this.remove(id)
            }, timeShown)
        },
        remove(id) {
            const notice = this.visible.find(notice => notice.id == id)
            const index = this.visible.indexOf(notice)
            this.visible.splice(index, 1)
        },

    };

  };

});


/*

  ====== REFRESHING SCRIPTS AFTER REINIT MAIN CONTENT =======

*/

function initScripts(){
  let scripts = document.getElementById("main").querySelectorAll("script");
  for(const script of scripts){
    let textContent = script.textContent;
    let newScript = document.createElement("script");
    newScript.textContent = textContent ;
    document.body.appendChild(newScript);
  }


  var scriptElement = document.createElement('script');
  scriptElement.src = 'https://y.studiowebdemo.com/editor/cb/box/box-flex.js';

  document.body.appendChild(scriptElement);
}

window.initScripts = initScripts;


/*

  ====== LAZY LOAD IMAGES =======
  OBSERVE ALL IMAGES AND BACKGROUND IMAGES.
  IF IMAGE IS ON CURRENT VIEW REPLACE SRC
  FROM 10x10px TO NEEDED WIDTH OF CONTAINER
*/

function replaceImages(){
  let images = document.querySelectorAll('img');
  let imageOptions = {};
  let observer = new IntersectionObserver((entries, observer) => {

    entries.forEach((entry, type) => {
      if(!entry.isIntersecting) return;
      if(entry.target.localName == 'img'){

        const image = entry.target;
        const originSrcUrl = image.getAttribute('src');
        let newUrl = '';
        if(image.clientWidth != 0){
          if(image.clientWidth < 640){
            newUrl = originSrcUrl.replace('10x10', '360x240');
            // newUrl = originSrcUrl.replace('10x10', '800x600');
          }
          if(image.clientWidth >= 640 && image.clientWidth < 800){
            newUrl = originSrcUrl.replace('10x10', '640x480');
            // newUrl = originSrcUrl.replace('10x10', '800x600');
          }

          if(image.clientWidth >= 800 && image.clientWidth < 1024){
            newUrl = originSrcUrl.replace('10x10', '800x600');
            // newUrl = originSrcUrl.replace('10x10', '1024x768');
          }
          if(image.clientWidth > 1024 ){
            newUrl = originSrcUrl.replace('10x10', '1024x768');
            // newUrl = originSrcUrl.replace('10x10', '1024x768');
          }
        }

        image.src = newUrl;
        observer.unobserve(image);
      }

      if(entry.target.localName == 'div'){
        const el = entry.target;
        const styles = window.getComputedStyle(el);
        let originSrcUrl = styles.backgroundImage;
        let newUrl = originSrcUrl;
        if(originSrcUrl != 'none'){
          if(el.clientWidth != 0){
            if(el.clientWidth < 640){
              newUrl = originSrcUrl.replace('10x10', '360x240');
              // newUrl = originSrcUrl.replace('10x10', '640x480');
            }

            if(el.clientWidth >= 640 && el.clientWidth < 800){
              newUrl = originSrcUrl.replace('10x10', '640x480');
                // newUrl = originSrcUrl.replace('10x10', '800x600');
            }

            if(el.clientWidth >= 800 && el.clientWidth < 1024){
              newUrl = originSrcUrl.replace('10x10', '800x600');
              // newUrl = originSrcUrl.replace('10x10', '1024x768');
            }

            if(el.clientWidth > 1024 ){
              newUrl = originSrcUrl.replace('10x10', '1024x768');
            }
          }
          el.style.backgroundImage=newUrl;

        }
      }
    });

  }, imageOptions);

  images.forEach((image) => {

    observer.observe(image);
  });

  // GET ALL DIVS .is-overlay-bg
    let overlaysElements = document.getElementsByClassName("is-overlay-bg");
      for(const container of overlaysElements){
        const styles = window.getComputedStyle(container);
        if(styles.backgroundImage != 'none'){
          observer.observe(container);
        }

      }
}

window.replaceImages = replaceImages;
window.addEventListener("load", (event) => {


});

const devSaveButton = document.getElementById("dev_save");
if (devSaveButton !== null) {
  devSaveButton.onclick = function() {
    tailwindGen();
    Page.saveTemplate();
  };
} 
