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
	
	public $url = '';
	public $seo_title = '';
	public $seo_description = '';
	public $seo_tags = '';
	public string $seo_image = '';
		
	public $error404 = false;

	public string $header = '';
	public string $footer = '';
	public string $css = '';
	public string $headCss = '';
	public $html = '';


	const ID_HEADER = 100;
	const ID_FOOTER = 101;

	function __construct(){
			global $core;

			$pathinfo = pathinfo($_SERVER['REQUEST_URI']);

			$tmp = explode('/', $pathinfo['dirname']);


			if(!isset($tmp[2])){
				$this->slug = $pathinfo['basename'];
			} else {
				$this->slug = $tmp[2];
			}

			$this->slug = parse_url($this->slug, PHP_URL_PATH);

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

			//Get head css
			$head_template = new Template('index', 'header');
			$this->headCss = $head_template->get_css();


			
			

	}

	function load_page(bool $cache = true){
		global $lang, $core;


		$header = new Template('header', 'header');
		$this->header = $header->get_html();

		$footer = new Template('footer', 'footer');
		$this->footer = $footer->get_html();



			//blog
		if($this->type == 'post' || $this->type == 'index'	|| $this->type == 'product' || $this->type == 'category' || $this->type == 'blog'	){
			$cache = false;


			if($this->slug === $lang->language){
				$this->id = 1;
				$this->slug= 'homepage';
			}



			if($this->id){
				
				$core->id = $this->id;
				$row = Api::cache($cache)->id($this->id)->data(['resolution' => '10x10'])->get()->pages();
			} else {
				$row = Api::cache($cache)->data(['slug' => $this->slug, 'resolution' => '10x10'])->get()->pages();
			}




			if(!$row) $this->error404 = true;

			$this->tempCategory = $row['tempCategory'] ??'';
			$this->combination = $row['combination'] ??'';
			//$this->html = $row['description'] ??'';
			$this->title = $row['title'] ??'';
			$this->seo_title = $row['seo_title'] ??'';
			$this->seo_description = $row['seo_description'] ??'';
			$this->url = $row['url']??'';
			//$this->css = $row['css']??'';
			$this->id = $row['id']??0;
			//for editor
			if(isset($row['slug']))	$this->slug = $row['slug'];
			
			$template = new Template($this->type, $this->slug);
				
			$this->html = $template->get_html();
			$this->css = $template->get_css();

		
			

			if($this->type == 'post'){
					$this->id = 16;

					$target = Api::cache($cache)->id($this->target_id)->data(['resolution' => '10x10'])->get()->blogPosts();

					$this->title = $target['title'];
					$this->seo_title = $target['seo_title'] ??'';
					$this->seo_description = $target['seo_description'] ??'';
					$this->seo_image = $target['images'][0]['url'] ?? '';
					$this->seo_tags = $target['seo_tags'] ?? '';

					$this->error404 = $target ? false : true;

			} else if($this->type == 'product'){
					$this->id = 13;


					$target = Api::cache($cache)->id($this->target_id)->data(['resolution' => '10x10'])->get()->products();

					$this->title = $target['title'];
					$this->seo_title = $target['seo_title'] ??'';
					$this->seo_description = $target['seo_description'] ??'';
					$this->seo_image = $target['images'][0]['url'] ?? '';
					$this->seo_tags = $target['seo_tags'] ?? '';

					$this->error404 = $target ? false : true;
			}

		}  else  if($this->type == 'header'){
			$this->html = $this->header;
		}
		else if($this->type == 'footer'){
			$this->html = $this->footer;
		}

		if(empty($this->seo_title)){
			
			$this->seo_title = $this->title." - ".$core->site_name;
		}
		if(empty($this->seo_description)){
			$this->seo_description = $this->title." - ".$core->site_name;
		}
		if(empty($this->seo_tags)){
			$this->seo_tags = $this->title.",".$core->site_name;
		}
		if(empty($this->seo_image)){
			$this->seo_image = $core->web['logo'];
		}
		//d($this);
	
		if($this->error404) redirect_to('/404');


	}






	public static function html_res_change(string $html, string $res):string{
			global $core;
	
		    $pattern = '/https:\/\/r2\.expozy\.com\/'.$core->site_name.'\/contbuilder\/(.*?)\/(.*?)\.webp/';
			$replacement = 'https://r2.expozy.com/'.$core->site_name.'/contbuilder/$1/'.$res.'/$2.webp';


			return preg_replace($pattern, $replacement, $html);
	}
	
	public static function downloadPages(){
			global $lang;
			
			
			$language = Api::cache(false)->get()->languages();
			$orig_i = get('i');
			$parameters = json_decode(base64_decode($orig_i), true);
			$parameters['type'] = 'index';
			
			
			foreach($language as $lng){
					
					$lang->language = $lng['lang'];
					
					$pages = Api::cache(false)->data(['lang'=>$lng['lang']])->get()->pages();
			
					foreach($pages['result'] as $page){
						
						
						$parameters['id'] = $page['id'];
						
						
						$new_parameters = base64_encode(json_encode($parameters));
						$editor = new Editor($new_parameters);
						$rev = $editor->revisions[0]['object_desc'] ?? '';
						
						
						
						$template = new Template('index', $page['slug']);
						
						if(file_exists($template->get_fileName()) === false){
							$template->save_html($rev);
						}

					}
			}
			
		
			
	}


}
