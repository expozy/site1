<?php


/**** get repo if folder is empty ****/
if(file_exists('.htaccess') === false){
	$git_clone = "git clone https://github.com/expozy/frontend.expozy.git tmp && mv tmp/.git . && rm -rf tmp && git reset --hard";
	$output = shell_exec($git_clone);
	header('Location: /gitops.php');
	die();
}

define( "_VALID_PHP", true);
require_once( "core/autoload.php");
require_once(BASEPATH.'core/classes/class.gitops.php');

/************ Login **********/
if(post('login')){
	$res = $user->login(post('email'), post('password'));
	header('Location: /gitops.php');
	die();
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

/******** Change saas key ************/
if(post('saas_key')){
	GitOps::change_saas_key(post('saas_key'));
	
	$user->logout();
	header('Location: /gitops.php');
	die();
}
 

/*********** Change github token *************/
if(post('github_token')){
	$verify = Api::data(['github_token'=> post('github_token'), 'github_route'=>'verify'])->get()->git();	
	
	if($verify['status'] != 1){
		header('Location: /gitops.php');
		die();
	}
		
	$_SESSION['github_token'] = post('github_token');

}

$github_token = isset($_SESSION['github_token']) && !empty($_SESSION['github_token']) ? $_SESSION['github_token'] : false;
$default_project = $core->site_name === 'frontend' ? true : false;

if(post('upload_repo')){
	//check if repo exist
	$res = Api::data(['github_token'=> $github_token,'create_repo' => 1])->post()->git();
	
	if($res['status'] ==1){
		GitOps::upload_repo($github_token);

		header('Location: /gitops.php');
		die(); 
	}
	
}

$hide_table = $default_project === true || $github_token === false ? true : false;


$repos = Api::data(['github_token'=> $github_token, 'github_route'=>'repos'])->get()->git();


if(post('download') && !empty(post('repo'))){
	GitOps::download_other_repo(post('repo'));
	header('Location: /gitops.php');
	die();	
}  


if(post('git_pull')){
	shell_exec("git pull");
}


$repo_name = GitOps::get_current_repo_name();


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
						<td><form method="post"><?php  echo $repo_name; ?> <input type="hidden" name="git_pull" value='1'><button>Download</button>
						</td>
					</tr>
					
					<tr>
						<td>Saas Key: </td>
						<td><form method="post"><input type="text" name="saas_key" value="<?php  echo SAAS_KEY ?>" ><button>SAVE</button></form></td>
					</tr>
					<tr>
						<td>Github token: </td>
						<td><form method="post"><input type="text" name="github_token" value="<?php  echo $github_token ? $github_token : '' ?>" ><button>SAVE</button></form></td>
					</tr>
				</table>
			
			 <br /><br/>
			 <br /><br/>
			 
			 <div class="red">
				<div><?php if($default_project) echo "Please, change SAAS KEY"; ?></div>
				<div><?php if(!$github_token) echo "Please, enter github token: <a href='https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens' target='_blank'>Help</a>"; ?></div>
			 </div>
			 <div style="<?php if($hide_table) echo "display:none;"; ?>">
					<table class='table'>
						<tr>
						   <td>Repo visability: </td>
						   <td><form method="post">
							   <?php echo "<i>{$repo_name}:</i> ";
										$repo = GitOps::get_repo($github_token, $repo_name);
										$vis = $repo['visibility'] ?? ''; ?>
							   
									<select name='visibility'>
										<option <?php if($vis == 'public') echo 'selected'; ?>>Public</option>
										<option <?php if($vis == 'private') echo 'selected'; ?>>Private</option>
									</select>
									<button <?php if($repo_name == 'frontend.expozy.git') echo 'disabled'; ?> >Change</button>
								</form>
						   </td>
						</tr>
					   
						<tr>
						   <td>Push to repo: <br/><i>*you can push only in project repo</i></td>
						   <td><form method="post">
									<input type="hidden" name="upload_repo"  value="1" />
									<input type="text" placeholder="GitHub username" value="<?php echo $core->site_name; ?>" disabled/>	
									<button>PUSH</button>
								</form>
						   </td>
						</tr>
					   <tr>
						   <td>Fetch other repo from GitHub:</td>
						   <td><form method="post">
									<input type="hidden" name='download' value='1' />
									<select name='repo'>
										<?php foreach($repos as $repo) { ?>
										<option value='<?php echo $repo['clone_url']; ?>' <?php if($repo['name'] == $core->site_name) echo 'selected'; ?>><?php echo $repo['name']; ?></option>
										<?php } ?>
									</select>
									<input type="text" name="" placeholder="GitHub username" value="<?php echo $core->site_name; ?>" disabled/>
									<button>DOWNLOAD</button>
								</form>
						   </td>
						</tr>
					   
					</table>
				 </br>
					
				   
				   <br/>
				   <br/>
				   
				   
			 </div>
		</div>
	</body>
</html>  
