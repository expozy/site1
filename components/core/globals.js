localStorage.setItem('lang', LANG);
localStorage.setItem('SITEURL', SITEURL);
localStorage.setItem('SAAS_KEY', SAAS_KEY);
localStorage.setItem('COREURL', COREURL);
// localStorage.setItem('RELEVAID', RELEVAID);

if(localStorage.getItem('LIMITPRODUCTS') === null){
  localStorage.setItem('LIMITPRODUCTS', 10);
}

if(localStorage.getItem('SORTPRODUCTS') === null){
  localStorage.setItem('SORTPRODUCTS', 'priceMin');
}

if(localStorage.getItem('LIMITSEARCH') === null){
  localStorage.setItem('LIMITSEARCH', 10);
}

if(localStorage.getItem('SORTSEARCH') === null){
  localStorage.setItem('SORTSEARCH', 'priceMin');
}

if(localStorage.getItem('LIMITBLOG') === null){
  localStorage.setItem('LIMITBLOG', 10);
}

if(localStorage.getItem('SORTBLOG') === null){
  localStorage.setItem('SORTBLOG', 'newest');
}
