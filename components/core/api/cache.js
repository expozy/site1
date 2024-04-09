async function cacheSet(url, data, ttl = 600) { //600 sec
    const cache = await caches.open('expozy');
    const headers = new Headers({'Cache-Control': 'public, max-age=' + ttl});
    const response = new Response(JSON.stringify(data), {headers: headers});
    await cache.put(url, response);
}

async function cacheGet(url) {
    const cache = await caches.open('expozy');
    const response = await cache.match(url);

    if (response && response.headers) {
        let cacheControl = response.headers.get('Cache-Control');
        let maxAge = cacheControl && /max-age=(\d+)/.test(cacheControl) 
                     ? parseInt(RegExp.$1, 10) 
                     : 0;

        if (maxAge) {
            let dateHeader = response.headers.get('date');
            let cachedAt = dateHeader ? new Date(dateHeader).getTime() : Date.now();

 
            if (Date.now() > cachedAt + maxAge * 1000) {
                return null; // кешът е изтекъл
            }

        }
    }

    if (response) {
        const data = await response.json();
        return data;
    }

    return null;
}

export { cacheSet, cacheGet };

window.cacheSet = cacheSet;
window.cacheGet = cacheGet;