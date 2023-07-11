const cachMin = 0.1; //minutes

//const cacheSet = (url, data) => localStorage.setItem(url, JSON.stringify(data));
//const cacheGet = (url) => function (){let result = JSON.parse(localStorage.getItem(url)); return result;} || null;

function cacheSet(url, data){
    let expire = + new Date;
    expire += cachMin * 60000  ;

   localStorage.setItem(url, JSON.stringify({expire: expire, data: data}));
}

function cacheGet(url){
    let result = JSON.parse(localStorage.getItem(url));

   if(result !== null && result.expire > + new Date){
       return result.data.data;}
   else return null;
}

export { cacheSet, cacheGet };
