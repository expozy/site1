<?php
$files = scandir(__DIR__);

/**** get repo if folder is empty ****/
if(count($files) == 3){
	$git_clone = "git clone https://github.com/expozy/frontend.expozy.git tmp && mv tmp/.git . && rm -rf tmp && git reset --hard";
	$output = shell_exec($git_clone);
	header('Location: /gitops.php');
	die();
}


define( "_VALID_PHP", true);

require_once( "core/autoload.php");


if(post('login')){
	
	$res = $user->login(post('email'), post('password'));
	header('Location: /gitops.php');
}


/**** Check access level ****/
if($user->is_admin() === false){
	
	echo '	<form method="post">
					   <input type="hidden" name="login"  value="1" />
					   <input type="text" placeholder="Email" name="email"/>
					   <input type="password" placeholder="password" name="password"/>
					   <button>LOGIN</button>
			</form>';
	die('Please login first!');
}


$output = shell_exec("git remote get-url origin");
$curent_repo = trim(basename($output));





if(post('saas_key')){
	$file = __DIR__."/core/saas_key.php";

	// get file content
	$content = file_get_contents($file);

	// replace key
	$newContent = preg_replace('/define\("SAAS_KEY",".*?"\);/', 'define("SAAS_KEY","' . post('saas_key') . '");', $content, 1);

	// save new content
	file_put_contents($file, $newContent);
	
	$user->logout();
	header('Location: /gitops.php');
	die();
}
 
if(post('github_token')){
	$_SESSION['github_token'] = post('github_token');
}

$github_token = isset($_SESSION['github_token']) && !empty($_SESSION['github_token']) ? $_SESSION['github_token'] : false;
$frontend_project = $core->site_name === 'frontend' ? true : false;

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

$hide_table = $frontend_project === true || $github_token === false ? true : false;


$repos = Api::data(['github_token'=> $github_token, 'github_route'=>'repos'])->get()->git();


if(post('download')){
	
	
	//delete all files
	deleteFiles('.');
	$git_clone = "git clone ".post('repo')." tmp && mv tmp/.git . && rm -rf tmp && git reset --hard";
	$output = shell_exec($git_clone);
	header('Location: /gitops.php');
	die();	
}  

function deleteFiles($target) {
	
	$files = scandir($target);
	foreach($files as $file){
		if(is_dir($file)){
			 shell_exec("rm -r " . escapeshellarg($file));
		} elseif(is_file($file)) {
			 unlink($file);
		}
	}
}

if(post('git_pull')){
	shell_exec("git pull");
}

?>


<!DOCTYPE html>
<html>
	<head>
		<title>GitOps</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<style>.red{color: red;}.table td{padding: 10px}</style>
	</head>
	<body>
		
		
		<div>
			
				<table class='table'>
					
					<tr>
						<td>Project Name: </td>
						<td><?php  echo $core->site_name ?></td>
					</tr>
					<tr>
						<td>Current Repo: </td>
						<td><form method="post"><?php  echo $curent_repo ?> <input type="hidden" name="git_pull" value='1'><button>Git Pull</button>
							<i>*get latest update</i></form>
						</td>
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
			 
			 <div class="red">
				<div><?php if($frontend_project) echo "Please, change SAAS KEY"; ?></div>
				<div><?php if(!$github_token) echo "Please, enter github token: <a href='https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens' target='_blank'>Help</a>"; ?></div>
			 </div>
			 <div style="<?php if($hide_table) echo "display:none;"; ?>">
					Push to repo: <br/><i>*you can push only in project repo</i>
				   <form method="post">
					   <input type="hidden" name="upload_repo"  value="1" />
					   <input type="text" placeholder="GitHub username" value="<?php echo $core->site_name; ?>" disabled/>	
					   <button>PUSH</button>
				   </form>
				   <br/>
				   <br/>
				   Fetch other repo from GitHub:
				   <form method="post">
					   <input type="hidden" name='download' value='1' />
					   <select name='repo'>
						   <?php foreach($repos as $repo) { ?>
						   <option value='<?php echo $repo['clone_url']; ?>' <?php if($repo['name'] == $core->site_name) echo 'selected'; ?>><?php echo $repo['name']; ?></option>
						   <?php } ?>
					   </select>
					   <input type="text" name="" placeholder="GitHub username" value="<?php echo $core->site_name; ?>" disabled/>



					   <button>DOWNLOAD</button>
				   </form>
			 </div>
		</div>
	</body>
</html>  
