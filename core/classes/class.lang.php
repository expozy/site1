<?php
if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }

/** =========================================================
 * Class Lang
 * ========================================================== */
class Lang
{

	public $language;
	public $langlist;

	const DEF_LANG = 'bg';


	function __construct()
	{
		$this->get_language();
	}

	/** =========================================================
	 * Function : get_language()
	 * ========================================================== */
    public function get_language()
    {
        global $core;

		if(isset($_GET['lang'])){
				if(get('lang')){
						$this->language = get('lang');
						$_SESSION['LANG_'. APP_NAME] =  get('lang');
				}

        } else {
			$this->language = $core->lang??self::DEF_LANG;
		}

    }
	
	public function set_language(string $lang)
    {
        global $core;

		$this->language = $lang;
		$_SESSION['LANG_'. APP_NAME] =  $lang;
    }

	/** =========================================================
	 * Function : langList()
	 * @return array|int
	========================================================== */
	public function lang_list()
	{
		$row = Api::get()->languages();

		return ($row) ? $this->langlist = $row : [];
	}


	/** =========================================================
	 * Function : lang_as_array()
	 * @return string
	========================================================== */
	public function lang_as_array()
	{
		$lines = file($this->langdir . $this->language . ".lang.php");

		if($this->modules) {
		    foreach($this->modules as $row){

			if(file_exists($this->langdir.$this->language.'.'.$row['variable'].'.php')) {
			    $lines2 = file($this->langdir.$this->language.'.'.$row['variable'].'.php');
			    foreach($lines2 as $k=>$l) {
				array_push($lines, $l);
			    }
			}
		    }
		}
		$array = array();
		foreach($lines as $line) {
			$matches=array();
			if (preg_match('/DEFINE\(\'(.*?)\',\s*\'(.*)\'\);/i', $line, $matches)) {
				$name = $matches[1];
				$value = $matches[2];

				$array[$name] = $value;
				//$return .= $name . " = " . $value . "\n";
			}

		}

		return $array;
	}

	/** =========================================================
	 * Function : validLang()
	 *
	 * @param $var
	 *
	 * @return bool
	========================================================== */
	private function validLang($var):bool
	{
	   //d($this->lang_list());
		foreach ($this->lang_list() as $value) {
			if(in_array($var, $value))
				return true;
		}

		return false;
	}

}
?>
