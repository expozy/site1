<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php 
define( "_VALID_PHP", true);
require_once '../autoload.php';
header('Content-type: application/xml');

//$products = [];
$page = 1;
$total_pages = 0;
do{
	
	$result = Api::data(['page' => $page ])->get()->blogPosts();
	$total_pages = $result['pagination']['total_pages'];
	//$products = array_merge($products,  $result['result']);
	
	$page++;
	foreach($result['result'] as $row){
		print " <url>
				<loc>{$row['url']}</loc>
				
				</url>";
	}
	

}
while($page <= $total_pages );
?>
</urlset>