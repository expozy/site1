<?php
if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }

/** =========================================================
 * Class Core
 * ========================================================== */
class Template
{
		public const DIR_PATH = BASEPATH.'static/';
		public $file = false;

		public function __construct(string $type, string $slug='') {
			global $lang;

			$this->file = self::DIR_PATH.$type.'~'.$slug.'~'.$lang->language. '.html';


		}


		public function get_html(){
			$html = '';

			if(file_exists($this->file)){
				//d($this->file);
				//d(file_get_contents($this->file));
				$html = file_get_contents($this->file);
			}
			
			return $html;
		}

		public function save_html($html){
			return file_put_contents($this->file, $html);
		}
		
		

}
