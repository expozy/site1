<?php
if (!defined("_VALID_PHP")) { die('Direct access to this location is not allowed.'); }

/** =========================================================
 * Class GitOps
 * ========================================================== */
class GitOps
{
		public static function get_current_repo_name():string{
				$output = shell_exec("git remote get-url origin");
				$curent_repo = trim(basename($output));
				
				return $curent_repo;
		}
		
		
		public static function get_repo(string $token, string $repo_name){
			
				$owner = self::get_curent_repo_owner();
				$repo = Api::data(['github_token' => $token, 'github_route' => 'repo', 'repo_name'=>$repo_name ,'repo_owner' => $owner])->get()->git();
				
			
				return $repo;
		}
		 
		public static function download_other_repo(string $repo){
				//delete all files
				self::deleteFiles(BASEPATH);
				$git_clone = "git clone {$repo} tmp && mv tmp/.git . && rm -rf tmp && git reset --hard";
				shell_exec($git_clone);
		}
		
		public static function change_saas_key(string $key):void {
				$file = BASEPATH."core/saas_key.php";

				// get file content
				$content = file_get_contents($file);

				// replace key
				$newContent = preg_replace('/define\("SAAS_KEY",".*?"\);/', 'define("SAAS_KEY","' . $key . '");', $content, 1);

				// save new content
				file_put_contents($file, $newContent);
		}
		
		public static function upload_repo(string $github_token):void {
				global $core;
				
				shell_exec("git add ."); 
				shell_exec('git commit -m "new commit"');
				shell_exec("git remote set-url origin https://{$github_token}@github.com/expozy/{$core->site_name}.git");
				shell_exec('git push -u origin main');
		}
		
		private static function deleteFiles($target) {
	
			$files = scandir($target);
			foreach($files as $file){
				if(is_dir($file)){
					 shell_exec("rm -r " . escapeshellarg($file));
				} elseif(is_file($file)) {
					 unlink($file);
				}
			}
		}
		
		private static function get_curent_repo_owner(){
				$repository_url = shell_exec("git remote get-url origin");
				
				$tmp = explode("/", str_replace("https://github.com/", "", $repository_url) );
				
				return $tmp[0];
				
		}
}
?>
