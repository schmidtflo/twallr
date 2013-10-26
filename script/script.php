<?php
session_start();
$id_alt = $_SESSION['id'];
$id_neu = $id_alt;
require_once ('codebird.php');
require_once ('../config.php');
\Codebird\Codebird::setConsumerKey($key, $key_secret); 
$cb = \Codebird\Codebird::getInstance();
$cb->setToken($token, $token_secret);
$cb->setReturnFormat(CODEBIRD_RETURNFORMAT_ARRAY);
$data = $search.'&result_type=recent&count=20&include_entities=false&since_id='.$id_alt;
$reply = (array) $cb->search_tweets($data);

function str_url_edit($string) {
	$regex = '/(http|https).\/\/\w.\w*\/\w*/';
	preg_match_all($regex, $string, $matches);
	if($matches) {
		foreach($matches[0] as $match) {
			$hypertext = "<a href=\"".$match."\">".$match."</a>";
			$string = str_replace($match, $hypertext, $string);
		}
	}
	return $string;
}

foreach ($reply as $single){
	if (is_array($single)){
	foreach ($single as $tweet) {
		if (isset($tweet['text'])&&$tweet['id_str']>$id_alt) {
			setlocale(LC_ALL, 'de_DE');
			$tweetdate_time = strtotime($tweet['created_at']);
			$tweetdate = strftime("%H:%M", $tweetdate_time);
			$text = str_url_edit($tweet['text']);
			$tweet_id=$tweet['id_str'];
			$rt=FALSE;
			if(isset($tweet['retweeted_status'])) $rt=TRUE;
			
			
			if ($tweet_id > $id_neu) $id_neu = $tweet_id; 
			
			if ($rt==FALSE){
			echo"<div ";
			echo "class=\"neu panel panel-default\" id=\"$tweet_id\"><div class=\"panel-heading\">";
			print_r($tweet['user']['name']);
			echo"  (@";
			print_r($tweet['user']['screen_name']);
			echo") | $tweetdate</div><div class=\"panel-body\"><img src=\"";
			echo $tweet['user']['profile_image_url'];
			echo"\"></img><p class=\"text\">$text</p></div></div>";
			};
			
			
			
			if ($rt==TRUE){
			$text = str_url_edit($tweet['retweeted_status']['text']);
			$tweetdate_time = strtotime($tweet['retweeted_status']['created_at']);
			$tweetdate = strftime("%H:%M", $tweetdate_time);
			echo"<div ";
			echo "class=\"neu panel panel-default\" id=\"$tweet_id\"><div class=\"panel-heading\">";
			print_r($tweet['retweeted_status']['user']['name']);
			echo"  (@";
			print_r($tweet['retweeted_status']['user']['screen_name']);
			echo") | $tweetdate | <span class=\"glyphicon glyphicon-retweet\"></span>  ";
			echo" @";
			print_r($tweet['user']['screen_name']);
			echo"</div><div class=\"panel-body\"><img src=\"";
			echo $tweet['retweeted_status']['user']['profile_image_url'];
			echo"\"></img><p class=\"text\">$text</p></div></div>";
			};

		};
	};
	};

};

$_SESSION['id']=$id_neu;


?>