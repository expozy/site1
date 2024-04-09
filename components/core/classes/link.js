

document.addEventListener('click', async function (event) {
	// Проверете дали кликнатият елемент е елемент 'a'

	if (event.target.href !== undefined && event.target.href != '' && !event.target.href.includes('/logout')) {

		const link = new URL(event.target.href);

		if (link.origin === window.location.origin) {

			//prevent forwarding
			event.preventDefault();

			//change URL in address bar
			history.pushState(null, null, link.pathname);

			// Refresh pageUrl parameters
			dataProxy['pageUrl'] = [];

			await Page.load();

			if (link.hash != '') {

				let elementId = link.hash.replace("#", "");
				let element = document.getElementById(elementId);
				if (element) {
					element.scrollIntoView();
				}
			} else {
				document.getElementById('main').scrollIntoView(true);
			}
		}
	}


});


// Ако потребителя се върне назад се зарежда на ново страницата
document.addEventListener('beforeunload', function (event) {
	Page.load();
});


window.addEventListener('popstate', function (event) {
	Page.load();
});
