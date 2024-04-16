<?php
$files = scandir(__DIR__);

if(count($files) == 3){
	$git_clone = "git clone https://github.com/expozy/frontend.expozy.git tmp && mv tmp/.git . && rm -rf tmp && git reset --hard";
	$output = shell_exec($git_clone);
	
	die($output);
}

define( "_VALID_PHP", true);

require_once( "core/autoload.php");

if($user->is_superAdmin() === false){
	die();
}




//create new repo
//$com = "git remote set-url origin https://github.com/expozy/test1.git";
//shell_exec($com);

?>


<!DOCTYPE html>
<html>
	<head>
		<title>GitOps</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
		<div>
			Git pull
			<form>
				<input type="text" name="name" placeholder="BranchName"/>
				<input type="text" name="" placeholder="GitHub username" />
				<input type="password" name="" placeholder="GitHub password" />
				
				<button>OK</button>
			</form>
		</div>
	</body>
</html>
