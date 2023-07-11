<?php
if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }

/** =========================================================
 * Class Core
 * ========================================================== */
class Page
{

	//public $lang = false;
	public $type = false;
	public $id = 0;
	public $slug = false;
	public $title = '';
	public $html = '';
	public $url = '';
	public $seo_title = '';
	public $seo_description = '';
	public $error404 = false;
	public string $header = '';
	public string $footer = '';
	public string $css = '';




	function __construct(){
			global $core;

			$pathinfo = pathinfo($_SERVER['REQUEST_URI']);

			$tmp = explode('/', $pathinfo['dirname']);


			$this->type = $tmp[2] ?? 'custom';



			$tmp2 = explode('-', $pathinfo['filename']);

			if(!isset($tmp2[0]) || !is_numeric($tmp2[0])){
				$this->id = 1;
				$this->slug = 'home';
			} else {
				$this->id = $tmp2[0];
			}
			//sd($tmp2);
			if($this->id) $core->id = $this->id;

			$this->slug = $tmp2[1] ?? '';

			if($pathinfo['basename'] == 'blog'){
				$this->type = 'blog';
				$this->id = 2;

			}

			


	}

	function load_page(bool $cache = true){

		$header = new Template('header');
		$this->header = $header->get_html();

		$footer = new Template('footer');


		$this->footer = $footer->get_html();

			//blog
		if($this->type == 'post'){
			$row = Api::cache($cache)->id($this->id)->get()->blogPosts();
			
			if(!$row) $this->error404 = true;

			$this->html = $row['description'] ??'';
			$this->title = $row['title'] ??'';
			$this->seo_title = $row['seo_title'] ??'';
			$this->seo_description = $row['seo_description'] ??'';
			$this->url = $row['url'];

			$this->date_created = $row['date_created'];
			$this->images = $row['images'];
			$this->category = $row['category'];
			$this->css = $row['css'];
			if($this->category['id'] == 7){
				$this->time = $row['time'];
				$this->portions = $row['portions'];
				$this->difficulty = $row['difficulty'];
			}
		}
				//Pages
		else if($this->type == 'index'){

			$row = Api::cache($cache)->id($this->id)->get()->pages();

		
			
			if(!$row) $this->error404 = true;

			$this->tempCategory = $row['tempCategory'] ??'';
			$this->combination = $row['combination'] ??'';
			$this->html = $row['description'] ??'';
			$this->title = $row['title'] ??'';
			$this->seo_title = $row['seo_title'] ??'';
			$this->seo_description = $row['seo_description'] ??'';
			$this->url = $row['url'];
			$this->css = $row['css'];
		}

				//Products
		else if($this->type == 'product'){

			$row = Api::cache($cache)->id($this->id)->data(['full_info'=>1])->get()->products();

			//if(!$row) $this->error404 = true;
			$releva = Api::data(['id'=> $this->id])->post()->releva();

			$this->html = $this->load_template($this->type);
			$this->title = $row['title'] ??'';
			$this->seo_title = $row['seo_title'] ??'';
			$this->seo_description = $row['seo_description'] ??'';
		}
		else if($this->type == 'category'){


			$row = Api::cache($cache)->id($this->id)->get()->category();

			$this->html = $this->load_template($this->type);
			$this->title = $row['title'] ??'';
			$this->seo_title = $row['seo_title'] ??'';
			$this->seo_description = $row['seo_description'] ??'';
			$this->url = $row['url']??'';
		}

		//Blog category
		else if($this->type == 'blog'){

			$row = Api::cache($cache)->id($this->id)->get()->blogCategories();
			//d($row);
			//if(!$row) $this->error404 = true;

			$this->html = $this->load_template($this->type);
			$this->title = $row['title'] ??'';
			$this->seo_title = $row['seo_title'] ??'';
			$this->seo_description = $row['seo_description'] ??'';
			$this->url = $row['url']??'';
			//$this->css = $row['css'];

			//d($row);

		}  else  if($this->type == 'header'){

			$this->html = $this->load_template($this->type);
			$this->header = '';
			$this->footer = '';
		}

		else if($this->type == 'footer'){

			$this->html = $this->load_template($this->type);
			$this->header = '';
			$this->footer = '';
		}


		var_dump($this);
		if($this->error404) redirect_to('404');

	}


	function load_template($type){

			$template = new Template($type);
			//var_dump($template);
			return $template->get_html();

	}


}
