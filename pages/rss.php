<?php
define( "_VALID_PHP", true);
require_once( "../core/autoload.php");



$xml = new DOMDocument('1.0', 'utf-8');

$rss = $xml->createElement('rss');
$rss->setAttribute('version', '2.0');
$xml->appendChild($rss);

$channel = $xml->createElement('channel');
$rss->appendChild($channel);

$title = $xml->createElement('title', $core->site_name);
$channel->appendChild($title);

$description = $xml->createElement('description', 'Crypto Blog');
$channel->appendChild($description);

$link = $xml->createElement('link', $core->site_url);
$channel->appendChild($link);



$posts = Api::cache(false)->limit(20)->sort('newest')->get()->blogPosts();

if(!isset($posts['result'])) die();

foreach($posts['result'] as $post){
		$article = $xml->createElement('item');
		$channel->appendChild($article);

		$title = $xml->createElement('title', $post['title']);
		$article->appendChild($title);

		$description = $xml->createElement('description', $post['description_short']);
		$article->appendChild($description);

		$link = $xml->createElement('link', $post['url']);
		$article->appendChild($link);

		$pubDate = $xml->createElement('pubDate', date($post['date_publish'] ?? $post['date_created']));
		$article->appendChild($pubDate);
		
		if(isset($post['images'][0]['url'])){
				$enclosure1 = $xml->createElement('enclosure');
				$enclosure1->setAttribute('url', $post['images'][0]['url']);
				$enclosure1->setAttribute('type', 'image/png');
				$article->appendChild($enclosure1);
		}
		
}

header('Content-type: application/xml');

echo $xml->saveXML();
?>