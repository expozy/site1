
async function cacheSet(url, data) {
  const cache = await caches.open('expozy');
  const response = new Response(JSON.stringify(data));
  await cache.put(url, response);
}

async function cacheGet(url) {
  const cache = await caches.open('expozy');
  const response = await cache.match(url);

  if (response) {
    const data = await response.json();
    return data;
  }

  return null; // или върнете друга стойност по избор
}

export { cacheSet, cacheGet };
