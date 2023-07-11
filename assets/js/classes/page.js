import {ApiClass, Api} from './../api/api.js';

export class PageClass {

	constructor() {
		this.reset();
	}
	
	reset(){
			this.type = null;
			this.id = 0;
			this.title = null;
			this.html = null;
			this.url = null;
			this.error404 = null;
			this.lang = null;
			this.slug = null;
			this.css = null;
			this.target_id = 0;
	}

	async get(){
		this.reset();
		this.url =window.location.href;

		var pathname = new URL(this.url).pathname;

		var splitUrl = pathname.split('/');
		this.slug = splitUrl[2];
		
		if(splitUrl[2] == 'product'|| splitUrl[2]=='post'){
			this.type = splitUrl[2];
		}
		else this.type = 'index';
		
		if(this.type==='post'){
			this.id=16;
		}

		if((splitUrl.length === 2 && splitUrl[1] === '') || (splitUrl.length > 2 && splitUrl[2] === '')){
				this.slug = 'homepage';
				this.id = 1;

		} else {


			if (typeof splitUrl[3] !== 'undefined') {
					var tmp3 = splitUrl[3].split('-');
					this.target_id = parseInt(tmp3[0]);
			} else {
					this.slug = splitUrl[2];
			}
		}

		this.lang = splitUrl[1];

		
		if(!this.lang){
			this.lang = LANG;
		} 

		const Api = new ApiClass();

		if(this.id > 0){
			await Api.get('pages/'+this.id);
		} else {
			await Api.get('pages?slug='+this.slug);
		}



		let page =  Api.response;


		if(Api.statusCode != 200){
			//window.location.href = "/404";
		}


		this.html = page.description;
		this.id= page.id;
		this.title = page.title;
		this.css = page.css;
		
		


		if(DEV_MODE){
			let response = await fetch(SITEURL+"/templates/"+this.type+"~"+this.slug+"~"+this.lang+".html");
            
		
			if(response.status ==200){
				let html = await response.text(); // Returns it as Promise
				this.html = html;
			}
		}
		
		debugger;
		return this;
	}



	async load(){
		this.reset();

		//seo title

		await this.get();
		// debugger;

		if(this.type !== 'post' && "post" in dataProxy ){
			dataProxy['post'] = {description: ''};
		}

		if(this.type !== 'product' && "product" in dataProxy ){
			dataProxy['product'] = '';
		}
		if(this.type === 'products' && "products" in dataProxy ){
			delete dataProxy['products']['result'] ;
			delete dataProxy['products']['pagination'];
		}
		// debugger;
		dataProxy['corePage'] = this;


		document.title = this.title;
		document.getElementById('pageCss').innerHTML = this.css;

		document.getElementById('main').innerHTML = this.html;

		// INIT SCRIPT FROM NEW HTML
			initScripts();
	}


	gen_editor_url(token){

		let data = {	'token': token,
						'type': this.type,
						'id': this.id};

		return SITEURL+'/editor/cb/editor.php?lang='+LANG+'&i='+btoa(JSON.stringify(data));


	}
	
	saveTemplate(){
		const styles = document.getElementById('tailwindCss').getElementsByTagName('style');
		const lastElement = styles[styles.length - 1];
		const lastElementString = lastElement.outerHTML;
		console.log(lastElementString);
		
		const reqBody = {		
						description: this.html,
						css:lastElementString
					};
		Api.put('pages/'+this.id+"?lang="+this.lang, reqBody);
		
	}



};

export let Page = new  PageClass();
