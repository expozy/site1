<?php
if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }

/** =========================================================
 * Class Core
 * ========================================================== */
class Template
{
		public const DIR_PATH = BASEPATH.'static/';
		public $file = false;
		public $page_type = '';
		public $page_slug = '';

		public function __construct(string $type, string $slug='') {
			global $lang;

			$this->page_type = $type;
			$this->page_slug = $slug;

			if($this->page_type=='footer' || $this->page_type == 'header'){
				$slug = '';
			}

			$this->file = self::DIR_PATH.$this->page_type.'~'.$slug.'~'.$lang->language. '.html';


		}


		public function get_html(){
			$html = '';

			if(file_exists($this->file)){


				//d(file_get_contents($this->file));
				$html = file_get_contents($this->file);
			}

			return $html;
		}

		public function save_html($html){


			if($this->page_type != 'index' && $this->page_type != 'header' && $this->page_type != 'footer' && $this->page_type != 'post' && $this->page_type != 'product'){
				return false;
			}
			if($this->page_type == 'index'){
				$page = Api::data(['slug'=> $this->page_slug])->get()->pages();
				if(!isset($page['id']) || $page['id'] == 0){
					return false;
				}
			}

			return file_put_contents($this->file, $html);
		}



}
