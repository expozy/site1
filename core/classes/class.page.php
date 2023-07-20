<?php
if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }

/** =========================================================
 * Class Core
 * ========================================================== */
class Page
{

	//public $lang = false;
	public $type = 'index';
	public int $id = 0;
	public int $target_id = 0;
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

	public string $seo_image = '';



	function __construct(){
			global $core;

			$pathinfo = pathinfo($_SERVER['REQUEST_URI']);

			$tmp = explode('/', $pathinfo['dirname']);


			if(!isset($tmp[2])){
				$this->slug = explode("?", $pathinfo['basename'])[0] ;

			} else {
				$this->slug = $tmp[2];
			}

			if(empty($this->slug)){
				$this->id = 1;
			}

			$tmp2 = explode('-', $pathinfo['filename']);

			if(isset($tmp2[1])){
				$this->target_id = (int)$tmp2[0];
			}




			if($this->slug == 'post'){
				$this->type = 'post';
			} else if ($this->slug == 'product'){
				$this->type = 'product';
			}



			//d($this);die();

	}

	function load_page(bool $cache = true){
		global $lang, $core;


		$header = new Template('header');

		$this->header = $header->get_html();

		$footer = new Template('footer');


		$this->footer = $footer->get_html();



			//blog
		if($this->type == 'post' || $this->type == 'index'	|| $this->type == 'product' ||
			$this->type == 'category' || $this->type == 'blog'	){

			$cache = false;


			if($this->slug === $lang->language){
				$this->id = 1;
				$this->slug= 'homepage';
			}



			if($this->id){
				global $core;
				$core->id = $this->id;
				$row = Api::cache($cache)->id($this->id)->data(['resolution' => '10x10'])->get()->pages();
			} else {
				$row = Api::cache($cache)->data(['slug' => $this->slug, 'resolution' => '10x10'])->get()->pages();
			}




			if(!$row) $this->error404 = true;

			$this->tempCategory = $row['tempCategory'] ??'';
			$this->combination = $row['combination'] ??'';
			$this->html = $row['description'] ??'';
			$this->title = $row['title'] ??'';
			$this->seo_title = $row['seo_title'] ??'';
			$this->seo_description = $row['seo_description'] ??'';
			$this->url = $row['url']??'';
			$this->css = $row['css']??'';
			$this->id = $row['id']??0;

			//for editor
			if(isset($row['slug']))	$this->slug = $row['slug'];

			if($this->type == 'post'){

					global $core;

					$this->id = 16;

					$target = Api::cache($cache)->id($this->target_id)->data(['resolution' => '10x10'])->get()->blogPosts();

					$this->seo_title = $target['seo_title'] ??'';
					$this->seo_description = $target['seo_description'] ??'';
					$this->seo_image = $target['images'][0]['url'] ?? '';

					$this->error404 = $target ? false : true;

			} else if($this->type == 'product'){
					global $core;

					$this->id = 13;


					$target = Api::cache($cache)->id($this->target_id)->data(['resolution' => '10x10'])->get()->products();

					$this->seo_title = $target['seo_title'] ??'';
					$this->seo_description = $target['seo_description'] ??'';
					$this->seo_tags = $target['seo_tags'] ?? '';

					$this->error404 = $target ? false : true;
			}




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


		if(isset($core->devMode) && $core->devMode){

			//d($this);
			$tmpl = new Template($this->type, $this->slug);

			if(!file_exists($tmpl->file)){
				$tmpl->save_html($this->html);
			} else{

				$this->html = $tmpl->get_html();
			}

			//d($this);
			return $this->_devMode();
		}


		if($this->error404) redirect_to('404');

	}

	private function _devMode(){
		//d($this) ;
	}


	function load_template($type){

			$template = new Template($type);
			//var_dump($template);
			return $template->get_html();

	}

	public static function html_res_change(string $html, string $res):string{
		    $pattern = '/https:\/\/storage\.de-fra1\.upcloudobjects\.com\/expozy\/frontend\/contbuilder\/(.*?)\/(.*?)\.webp/';
			$replacement = 'https://storage.de-fra1.upcloudobjects.com/expozy/frontend/contbuilder/$1/'.$res.'/$2.webp';


			return preg_replace($pattern, $replacement, $html);
	}


}
