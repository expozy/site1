<?php
if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }

/** =========================================================

 * Class Api

 * ========================================================== */

class Editor{
	public string $type = '';
	public int $id = 0;
	public string $uri = '';
	public string $slug = '';
	public string $html = '';
	public array $revisions = [] ;
	public string $title = '';
	const TEMPLATE_TYPES = ['product', 'category', 'blog','header', 'footer' ];

	//public $page;

	/**
	 *
	 * @global \Page $page
	 * @param string $base64
	 */
	public function __construct(string $base64) {
		global $page;



		if(empty($base64)) die("Parameter 'i' is missing !");

		$parameters = json_decode(base64_decode($base64), true);



		$this->type = $parameters['type'];
		$this->id = $parameters['id']??0;

		$page->type = $parameters['type'];
		$page->id = $parameters['id']??0;

		if($this->type == 'post'){
			$page->target_id = $parameters['id'];
		}
		$this->load_page();
		$this->getRevisions();


	}

	public function getRevisions(){
		global $lang;


		$revisions = Api::cache(false)->admin_api(true)->data(['type' => 'pages','object_id' => $this->id, 'lang'=>$lang->language, 'limit'=>20 , 'sort'=>''])->get()->revisions();
		$this->revisions = $revisions;

	}



	public function load_page(){
		global $page;



		$page->load_page(false);


		$this->html = $page->html;
		$this->title = $page->title;
		if($page->type == 'post'){
			$row = Api::cache(false)->id($this->id)->data(['resolution' => '10x10'])->get()->blogPosts();

			$this->html = $row['description'];

		}



	}


	public static function red_file(string $filename):string{
			$filepath = BASEPATH.'pages/'.$filename;

			$html = '';

			if(file_exists($filepath)){
				$html = file_get_contents($filepath);
			}

			return $html;

	}

	public function save(string $html, string $language, string $css='', string $revision_title){
		global $page, $lang;
		
		
		//remoce <style> from css
		$css = str_replace(['<style>', '</style>'], '', $css);


		if(empty($language)){
			$language = $lang->language;
		}
		
		$lang->set_language($language);



		if($page->type == 'post'){

			$row = Api::data(['lang'=>$language])->id($this->id)->get()->blogPosts();

			if($row){
				$row['html'] = $html;
				$row['css'] = $css;
				$row['revision_title'] = $revision_title;
				if(empty($row['title'])) $row['title'] = 'Blog-'.$page->id;

				$this->_refresh_cache('blogPosts', $page->id);

				$row['cid'] = $row['category']['id'];
				// $row['combination_id'] = $row['combination']['id'];
				$row['lang']=$language;

				$result = Api::admin_api(true)->data($row)->id($this->id)->post()->blogPosts();

				return $result;
			}
		} else if($page->type == 'index'){


			
			$row = Api::data(['lang'=>$language])->id($page->id)->get()->pages();

			if($row){

				$row['body'] = $html;
				$row['css'] = $css;
				$row['revision_title'] = $revision_title;
				if(empty($row['title'])) $row['title'] = 'Page-'.$page->id;

				$this->_refresh_cache('pages', $page->id);



				$row['lang']=$language;

				$result = Api::admin_api(true)->data($row)->id($page->id)->post()->pages();


				return $result;
			}
		}

		else if($page->type == 'header' || $page->type == 'footer'){

			$result = Api::admin_api(true)->data(['headCss' => $css])->post()->settings_web();

			$template = new Template($page->type);

			$template->save_html($html);

			return ['status' => 1];

		}
		else if (in_array ($page->type, self::TEMPLATE_TYPES)){
			$template = new Template($page->type);

			$template->save_html($html);

			return ['status' => 1];
		}


		return ['error'=>'type error: '.$page->type];


	}

	private function _refresh_cache(string $endpoint, int $id){
			$cache = new Cache($endpoint, '', '', $id);
			//d($cache);
			@unlink($cache->file);
	}

}
