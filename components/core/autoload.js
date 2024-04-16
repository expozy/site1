// Старт функция 
async function callBackMain() {
  await import(`./helpers.js?v=${JS_VERSION}`);
  await import(`./classes/page.js?v=${JS_VERSION}`);
  await import(`./api/api.js?v=${JS_VERSION}`);
  await import(`./handler.js?v=${JS_VERSION}`);

  replaceImages();

  if (updatedMain == true) return;
  callApiData();
  updatedMain = true;

}

window.callBackMain = callBackMain;




let updatedMain = false;

// GLOBAL DATA OBJECT
let dataBody = {
  corePage: PAGEINIT,
  user: USER,
  pageUrl: URL_PARAMETERS,
  settings: { logo: LOGO_URL, social: SOCIAL_NETWORKS },
  openCart: false,
  openMobileMenu: false,
  screenWidth: window.screen.width,
  scrollPosition: window.pageYOffset,
  openLogin: false,
  openRegistration: false,
  openProduct: false,
  openForgotten: false,
  openGeolocation: false,
  darkMode: false,
  location: [],
};



const dataProxy = new Proxy(dataBody, {
  set(target, property, value) {
    if (property == 'corePage') {
      updatedMain = false;
      value.html += '<div x-init="callBackMain()"></div>';
    }

    target[property] = value;
    document.getElementById('body').dispatchEvent(new CustomEvent('update', { detail: { value: value, property: property }, bubbles: true }));
    return true;

  }

});


window.dataProxy = dataProxy;





/* START INIT BODY DATA METHOD

   Body data is equal of Global variable "dataBody"

   We can call updatedata method to refresh the page with new data.*/

document.addEventListener('alpine:init', () => {
  window.dataset = function () {
    return {
      data: dataBody,
      updatedata(data, keyName) {
        if (data['property']) {
          this.data[data['property']] = {};
          this.data[data['property']] = data.value;
        } else {
          this.data = {};
          this.data = dataBody;
        }
      },
    };

  };


  // Alpine.nextTick(() => {
  //   console.log('Промените по дизайна са свършени.');
  // });


});

// END INIT BODY DATA METHOD





function callApiData() {
  let apiDataElements = document.querySelectorAll('[apiData]');

  for (const element of apiDataElements) {
    let functionCall = element.getAttribute('apiData');
    let keyName = element.getAttribute('keyName');

    let data = getDataAttributes(element, 'data');
    let options = getDataAttributes(element, 'options');

    options['keyName'] = keyName;
    options['initial'] = true;




    let importFile = functionCall.split(".")[0];
    importFile = importFile.charAt(0).toLowerCase() + importFile.slice(1);

    import(`../static/${importFile}.js?v=${JS_VERSION}`)
      .then(module => {
        try {
          eval(functionCall + '(data, options)');
        } catch (error) {
          console.error('Failed to get Data' + error);
        }
      })
      .catch(error => console.error(`File in ../static/${importFile}.js was not found !  /n  ${functionCall}`));


  }

}

window.callApiData = callApiData;

/*

  START ALPINE CLICK EVENT LISTENER
  @click="alpineListeners('Shop.change_page' , $event.target)"
  2 parameters needed. Method and Element who trigger event.
  Call the method and set data response to body.
  Can extract json data from closest form or attributes wich start with "data-"
*/

async function alpineListeners(method, element) {

  try {
    element.preventDefault();
  } catch (e) {

  }

  if (element.currentTarget !== null && element.currentTarget !== undefined) {
    if (element.target.tagName == 'SELECT') {
      element = element.target.options[element.target.selectedIndex];
    } else {
      element = element.currentTarget;
    }
  } else if (element.srcElement !== null && element.srcElement !== undefined) {
    element = element.srcElement;
  }

  let data = {};
  let options = {};
  let keyName = '';

  // WE DONT HAVE A METHOD SET IN EVENT TARGET
  if (method == null) return console.error(`Method is not set`);
  if (element != undefined) {
    let formData;
    let attributesData;
    // GET DATA FROM CLOSEST FORM IF HAVE
    if (element.closest("form") != null) {
      formData = Helpers.get_form_data(element.closest("form"));
      data = Object.assign(data, formData);
    }

    // GET ALL ELEMENT ATTRIBUTES WICH START WITH DATA-
    attributesData = getDataAttributes(element, 'data');
    data = Object.assign(data, attributesData);
    // keyName = element.getAttribute('keyName');

    options = getDataAttributes(element, 'options');

    // Get keyName if we have
    if (element.getAttribute('keyName') != undefined) {
      keyName = element.getAttribute('keyName');

      if (keyName != undefined) options['keyName'] = keyName;

      // If we dont have endpoint in event target get endpoint from dataBody if we have.
      if (data['endpoint'] === undefined && (keyName in dataBody && dataBody[keyName]['endpoint'] != undefined)) {
        if (dataBody[keyName]) {
          options['endpoint'] = dataBody[keyName]['endpoint'];
        }
      }


    }

  }



  /*
    Import js functnions file
  */
  // let response ={};

  let importFile = method.split(".")[0];
  importFile = importFile.charAt(0).toLowerCase() + importFile.slice(1);

  await import(`../static/${importFile}.js?v=${JS_VERSION}`)
    // .then( module =>  {
    //
    // })
    .catch(error => console.error(`File in ../static/${importFile}.js was not found !  /n  ${method}`));


  // console.log('request');
  let response = await eval(method + '(data, options)');



  if (response != undefined) {

    //PREDIFENED ERRORS WITH LOGIC IN METHODS
    if ("internalError" in response && typeof (response.internalError) !== "undefined") return console.error(`InternalError \n alpineListeners : ${method} \n Error : ${response.msg}`);

    // IF WE HAVE STATUS SHOW SUCCESS OR ERRORS MSGS
    if ("status" in response) Helpers.show_errors(response);

    // Clear form an scroll to main
    if ("clearForm" in response && element.closest("form") != undefined) Helpers.clear_form_data(element.closest("form"));

    // IF WE DONT HAVE ELEMENT KEYNAME CHECK  KEYNAME IN RESPONSE.
    if (("keyName" in response && typeof (response.keyName) !== "") && response.keyName != '') keyName = response.keyName;

    // IF WE HAVE KEYNAME AT END SET RESPONSE TO GLOBAL DATA AND UPDATE
    if (keyName != '') {
      dataProxy[keyName] = response.obj;
    }


    // IF WE HAVE REDIRECT URL
    if ("url" in response) return forceChange(response.url);

    return response['status'];

  } else {
    console.error(`no response for alpineListeners /n ${method}`);
  }

}

window.alpineListeners = alpineListeners;

// END  ALPINE CLICK EVENT LISTENER

async function forceChange(url) {
  history.pushState(null, null, url);
  dataProxy['pageUrl'] = [];
  Page.load();
  document.getElementById('main').scrollIntoView(true);
}

window.forceChange = forceChange;

// GET ALL ATTRIBUTES OF ELEMENT WICH START WITH " DATA- "

function getDataAttributes(element, prefix) {

  const dataAttrs = element.getAttributeNames().reduce((obj, name) => {
    if (name.startsWith(prefix + '-')) {
      return { ...obj, [name.slice(name.indexOf('-') + 1)]: element.getAttribute(name) };
    }
    return obj;
  }, {});
  return dataAttrs;
}

// END GET ALL ATTRIBUTES OF ELEMENT WICH START WITH " DATA- "



document.addEventListener('alpine:init', () => {
  window.notification = function () {
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

function initScripts() {

  let scripts = document.getElementById("main").querySelectorAll("script");
  for (const script of scripts) {
    let newScript = document.createElement("script");

    let textContent = script.textContent;
    if (textContent === '') {
      let src = script.src
      newScript.setAttribute('src', script.src);
    }

    newScript.textContent = textContent;
    document.body.appendChild(newScript);
  }


  var scriptElement = document.createElement('script');
  scriptElement.src = `${SITEURL}/editor/cb/box/box-flex.js`;

  document.body.appendChild(scriptElement);
}

window.initScripts = initScripts;



/*

  ====== LAZY LOAD IMAGES =======
  OBSERVE ALL IMAGES AND BACKGROUND IMAGES.
  IF IMAGE IS ON CURRENT VIEW REPLACE SRC
  FROM 10x10px TO NEEDED WIDTH OF CONTAINER
*/


// window.replaceImages = replaceImages;


function replaceImages() {
  let images = document.querySelectorAll('img');

  let imageOptions = {};
  let observer = new IntersectionObserver((entries, observer) => {

    entries.forEach((entry, type) => {
      if (!entry.isIntersecting) return;
      if (entry.target.localName == 'img') {

        const image = entry.target;
        const originSrcUrl = image.getAttribute('src');
        let newUrl = originSrcUrl;
        if (image.clientWidth != 0) {
          if (image.clientWidth < 640) {
            // newUrl = originSrcUrl.replace('10x10', '360x240');
            newUrl = originSrcUrl.replace('10x10', '800x600');
          }
          if (image.clientWidth >= 640 && image.clientWidth < 800) {
            // newUrl = originSrcUrl.replace('10x10', '640x480');
            newUrl = originSrcUrl.replace('10x10', '800x600');
          }

          if (image.clientWidth >= 800 && image.clientWidth < 1024) {
            // newUrl = originSrcUrl.replace('10x10', '800x600');
            newUrl = originSrcUrl.replace('10x10', '1024x768');
          }
          if (image.clientWidth >= 1024) {
            // newUrl = originSrcUrl.replace('10x10', '1024x768');
            newUrl = originSrcUrl.replace('10x10', '1024x768');
          }
        }

        image.src = newUrl;
        observer.unobserve(image);
      }

      if (entry.target.localName == 'div') {
        const el = entry.target;
        const styles = window.getComputedStyle(el);
        let originSrcUrl = styles.backgroundImage;
        let newUrl = originSrcUrl;
        if (originSrcUrl != 'none') {
          if (el.clientWidth != 0) {
            if (el.clientWidth < 640) {
              // newUrl = originSrcUrl.replace('10x10', '360x240');
              newUrl = originSrcUrl.replace('10x10', '640x480');
            }

            if (el.clientWidth >= 640 && el.clientWidth < 800) {
              // newUrl = originSrcUrl.replace('10x10', '640x480');
              newUrl = originSrcUrl.replace('10x10', '800x600');
            }

            if (el.clientWidth >= 800 && el.clientWidth < 1024) {
              // newUrl = originSrcUrl.replace('10x10', '800x600');
              newUrl = originSrcUrl.replace('10x10', '1024x768');
            }

            if (el.clientWidth >= 1024) {
              newUrl = originSrcUrl.replace('10x10', '1024x768');
            }
          }
          el.style.backgroundImage = newUrl;

        }
      }
    });

  }, imageOptions);
  window.observer = observer;

  images.forEach((image) => {
    observer.observe(image);
  });


  // GET ALL DIVS .is-overlay-bg
  let overlaysElements = document.getElementsByClassName("is-overlay-bg");
  for (const container of overlaysElements) {
    const styles = window.getComputedStyle(container);
    if (styles.backgroundImage != 'none') {
      observer.observe(container);
    }

  }
}





const devSaveButton = document.getElementById("dev_save");
if (devSaveButton !== null) {

  devSaveButton.onclick = async function () {
    // dataProxy['user']['token']
    //save page
    Page.get();
    await alpineTemplatesGen();
    await classGen();
    await Page.saveCss();



  };
}

async function processTemplates(container, templates) {
  return new Promise(resolve => { // Връщаме обещание, което ще бъде резолвнато след като завършим обработката
    templates.forEach(async function (template) {
      if (!template.classList.contains("dontSelect")) {


        var templateContent = template.content;
        var div = document.createElement("div");
        div.appendChild(templateContent.cloneNode(true));

        var nestedTemplates = div.querySelectorAll("template");
        if (nestedTemplates.length > 0) {
          await processTemplates(div, nestedTemplates); // Изчакваме вложените шаблони да бъдат обработени
        }

        container.appendChild(div);
      }
    });

    resolve(); // Резолваме обещанието, когато завършим обработката
  });
}

async function alpineTemplatesGen() {
  var templates = document.querySelectorAll("template");

  var container = document.getElementById("templatesDiv");
  container.innerHTML = '';

  await processTemplates(container, templates); // Изчакваме обработката на шаблоните да завърши
}

async function classGen() {
  var elementsWithAttribute = document.querySelectorAll('[\\:class]');

  elementsWithAttribute.forEach(function (element) {

    var attributeValue = element.getAttribute(':class');
    var replacedValue = attributeValue.replace(/['"]/g, ' ');

    if (elementsWithAttribute[0].classList.length == 0) {
      elementsWithAttribute[0].setAttribute('class', replacedValue);
    } else {
      replacedValue = elementsWithAttribute[0].classList.value + ' ' + replacedValue
      elementsWithAttribute[0].setAttribute('class', replacedValue);
    }

  });


}


/*
  CHANGE CURRENT LANGUAGE
  changeLang('bg')
*/
function changeLang(lang) {
  if (dataBody.corePage.lang != undefined && dataBody.corePage.lang != '') {
    let currentLang = dataBody.corePage.lang;
    if (currentLang != lang) {
      let oldUrl = window.location.href;
      if (oldUrl.includes(`/${currentLang}/`)) {
        var newUrl = oldUrl.replace(`/${currentLang}/`, `/${lang}/`);
        window.location.href = newUrl;

      } else {
        window.location.href = window.location.origin + `/${lang}/`;
      }
    }
  } else {
    window.location.href = window.location.origin + `/${lang}/`;
  }
}

window.changeLang = changeLang;


window.addEventListener('resize', function (event) {
  dataProxy['screenWidth'] = window.screen.width;
}, true);

window.addEventListener('scroll', function (event) {
  dataProxy['scrollPosition'] = window.pageYOffset;
}, true);
