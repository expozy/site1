<?php

if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }

class Inline {
	
	public static function buttonEdit():string{
		global $user;
		
		$html = '';
		if($user->is_admin()){
			$html = '<div class="inline_cont" ><a href="'.self::gen_url().'" class="circle inline_off"><span><i class="fas fa-pencil-alt"></i></a></span></div>';
		}
		
		return $html;
	}
	
	/**
	 * 
	 * @global \Page $page
	 * @return string
	 */	
	public static function buttonExit():string{
		global $page;
		
		$page->load_page();
		
		$html = '<div class="inline_cont" ><a href="'.(!empty($page->url)? $page->url :'#').'" class="circle inline_on"><span><i class="fa fa-times"></i></a></span></div>';

		return $html;
	}
	
	
	public function toggle(){
		global $user;
		
		if($user->is_admin()){
				if(isset($_SESSION['inline_mode'])){
					$_SESSION['inline_mode'] = $_SESSION['inline_mode'] ? false : true;
				} else {
					$_SESSION['inline_mode'] = true;
				}
		}
	}
	
		
	public static function gen_url():string {
		
			global $user, $lang, $page;			
			$data = [	'token'=>$user->token,
						'type' => $page->type,
						'id' => $page->id];
						
			
			
			return SITEURL.'/editor/cb/editor.php?lang='.$lang->language.'&i='.base64_encode(json_encode($data));
	}
	
	
}
