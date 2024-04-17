<?php
$files = scandir(__DIR__);

/**** get repo if folder is empty ****/
if(count($files) == 3){
	$git_clone = "git clone https://github.com/expozy/frontend.expozy.git tmp && mv tmp/.git . && rm -rf tmp && git reset --hard";
	$output = shell_exec($git_clone);
	header('Location: /en/login');
	die($output);
}


define( "_VALID_PHP", true);

require_once( "core/autoload.php");

/**** Check access level ****/
if($user->is_admin() === false){
	die('Please login!');
}



$output = shell_exec("git remote get-url origin");
$curent_repo = trim(basename($output));

$frontend_project = $core->site_name === 'frontend' ? true : false;



if(post('saas_key')){
	$file = __DIR__."/core/saas_key.php";

	// get file content
	$content = file_get_contents($file);

	// replace key
	$newContent = preg_replace('/define\("SAAS_KEY",".*?"\);/', 'define("SAAS_KEY","' . post('saas_key') . '");', $content, 1);

	// save new content
	file_put_contents($file, $newContent);
	header('Location: /en/login');
	die();
}
 
if(post('github_token')){
	$_SESSION['github_token'] = post('github_token');
}

$github_token = isset($_SESSION['github_token']) && !empty($_SESSION['github_token']) ? $_SESSION['github_token'] : false;

if(post('upload_repo')){
	//check if repo exist
	$res = Api::data(['github_token'=> $github_token,'create_repo' => 1])->post()->git();
	
	if($res['status'] ==1){
		shell_exec("git add .");
		shell_exec('git commit -m "new commit"');
		shell_exec("git remote set-url origin https://{$github_token}@github.com/expozy/{$core->site_name}.git");
		shell_exec('git push -u origin main');

		
		header('Location: /gitops.php');
		die($output);
	}
	
}


?>


<!DOCTYPE html>
<html>
	<head>
		<title>GitOps</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style>.red{color: red;}</style>
	</head>
	<body>
		
		
		<div>
			
				<table>
					<tr>
						<td>Repo: </td>
						<td><?php  echo $curent_repo ?></td>
					</tr>
					<tr>
						<td>Project Name: </td>
						<td><?php  echo $core->site_name ?></td>
					</tr>
					<tr>
						<td>Saas Key: </td>
						<td><form method="post"><input type="text" name="saas_key" value="<?php  echo SAAS_KEY ?>" ><button>UPDATE</button></form></td>
					</tr>
					<tr>
						<td>Github token: </td>
						<td><form method="post"><input type="text" name="github_token" value="<?php  echo $github_token ? $github_token : '' ?>" ><button>UPDATE</button></form></td>
					</tr>
				</table>
			
			 <br /><br/>
			 <br /><br/>
			 
			 <div class="red"><?php if(!$github_token) echo "Please, enter github token: <a href='https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens' target='_blank'>Help</a>"; ?></div>
			 <div style="<?php if(!$github_token) echo "display:none;"; ?>">
					Push to repo: <br/><i>*you can push only in repo of project</i>
					<?php if($frontend_project) echo "<br/><br/><div class='red'>YOU CAN NOT PUSH TO THIS REPO, PLEASE CHANGE SAAS KEY</div><br/>"; ?> 
				   <form method="post">
					   <input type="hidden" name="upload_repo"  value="1" />
					   <input type="text" placeholder="GitHub username" value="<?php echo $core->site_name; ?>" disabled/>	
					   <button <?php if($frontend_project) echo "disabled" ?>>PUSH</button>
				   </form>
				   <br/>
				   <br/>
				   Fetch from GitHub:
				   <form>
					   <select>
						   <option></option>
						   <option></option>
					   </select>
					   <input type="text" name="" placeholder="GitHub username" value="<?php echo $core->site_name; ?>" disabled/>



					   <button>DOWNLOAD</button>
				   </form>
			 </div>
		</div>
	</body>
</html>
