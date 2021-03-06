<?php
session_start();
$id_alt = $_SESSION['id'];
$id_neu = $id_alt;
require_once('codebird.php');
require_once('../config.php');
\Codebird\Codebird::setConsumerKey($key, $key_secret);
$cb = \Codebird\Codebird::getInstance();
//$cb->setToken($token, $token_secret);
$cb->setToken($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
$cb->setReturnFormat(CODEBIRD_RETURNFORMAT_ARRAY);
$data = $search . '&result_type=recent&count=30&include_entities=true&since_id=' . $id_alt;
$reply = (array)$cb->search_tweets($data);

function str_url_edit($string)
{
    $regex = '/(http|https).\/\/\w.\w*\/\w*/';
    preg_match_all($regex, $string, $matches);
    if ($matches) {
        foreach ($matches[0] as $match) {
            $hypertext = "<a href=\"" . $match . "\">" . $match . "</a>";
            $string = str_replace($match, $hypertext, $string);
        }
    }
    return $string;
}

foreach ($reply as $single) {
    if (is_array($single)) {
        foreach ($single as $tweet) {
            if (isset($tweet['text']) && $tweet['id_str'] > $id_alt) {

                if ($tweet['id_str'] > $id_neu) $id_neu = $tweet['id_str'];
                $rt = FALSE;
                if (isset($tweet['retweeted_status'])) $rt = TRUE;
                setlocale(LC_ALL, 'de_DE');

                $tweettime = strftime("%H:%M", strtotime($tweet['created_at']));
                $tweetdate = strftime("%m.%d", strtotime($tweet['created_at']));
                $tweetdate_display = strftime("%d.%m.%y", strtotime($tweet['created_at']));

                if ($rt == TRUE) {
                    $tweettime_time = strtotime($tweet['retweeted_status']['created_at']);
                    $tweettime = strftime("%H:%M", $tweettime_time);
                    $tweetdate = strftime("%m.%d", $tweettime_time);
                    $tweetdate_display = strftime("%d.%m.%y", $tweettime_time);
                }

                if ($tweetdate < date("m.d")) $date = TRUE;

                $text = $tweet['text'];
                if ($rt == TRUE) {
                    $text = $tweet['retweeted_status']['text'];
                }


                if ($rt == TRUE) {
                    foreach ($tweet['retweeted_status']['entities']['hashtags'] as $hashtag) {
                        $replacement = '#<a href="https://twitter.com/search?q=%23' . $hashtag['text'] . '" target="_blank">' . $hashtag['text'] . '</a>';
                        $text = str_replace("#" . $hashtag['text'], $replacement, $text);
                    }
                } else {
                    foreach ($tweet['entities']['hashtags'] as $hashtag) {
                        $replacement = '#<a href="https://twitter.com/search?q=%23' . $hashtag['text'] . '" target="_blank">' . $hashtag['text'] . '</a>';
                        $text = str_replace("#" . $hashtag['text'], $replacement, $text);
                    }
                }

                if ($rt == TRUE) {
                    foreach ($tweet['retweeted_status']['entities']['user_mentions'] as $mention) {
                        $replacement = '@<a href="https://twitter.com/' . $mention['screen_name'] . '" target="_blank">' . $mention['screen_name'] . '</a>';
                        $text = str_replace("@" . $mention['screen_name'], $replacement, $text);
                    }
                } else {
                    foreach ($tweet['entities']['user_mentions'] as $mention) {
                        $replacement = '@<a href="https://twitter.com/' . $mention['screen_name'] . '" target="_blank">' . $mention['screen_name'] . '</a>';
                        $text = str_replace("@" . $mention['screen_name'], $replacement, $text);
                    }
                }

                if ($rt == TRUE) {
                    foreach ($tweet['retweeted_status']['entities']['urls'] as $url) {
                        $replacement = '<a href="' . $url['expanded_url'] . '" target="_blank">' . $url['display_url'] . '</a>';
                        $text = str_replace($url['url'], $replacement, $text);
                    }
                } else {
                    foreach ($tweet['entities']['urls'] as $url) {
                        $replacement = '<a href="' . $url['expanded_url'] . '" target="_blank">' . $url['display_url'] . '</a>';
                        $text = str_replace($url['url'], $replacement, $text);
                    }
                }

                if ($rt == TRUE) {
                    if (isset($tweet['retweeted_status']['entities']['media'])) {
                        foreach ($tweet['retweeted_status']['entities']['media'] as $url) {
                            $replacement = '<a href="' . $url['expanded_url'] . '" target="_blank"><em>Foto</em></a>';
                            $text = str_replace($url['url'], $replacement, $text);
                        }
                    }
                } else {
                    if (isset($tweet['entities']['media'])) {
                        foreach ($tweet['entities']['media'] as $url) {
                            $replacement = '<a href="' . $url['expanded_url'] . '" target="_blank"><em>Foto</em></a>';
                            $text = str_replace($url['url'], $replacement, $text);
                        }
                    }
                }


                if ($rt == TRUE) {
                    $tweet_id = $tweet['retweeted_status']['id_str'];
                } else {
                    $tweet_id = $tweet['id_str'];
                }


                if ($rt == TRUE) {
                    $name = $tweet['retweeted_status']['user']['name'];
                    $screen_name = $tweet['retweeted_status']['user']['screen_name'];
                    $rt_name = $tweet['user']['screen_name'];
                } else {
                    $name = $tweet['user']['name'];
                    $screen_name = $tweet['user']['screen_name'];
                }

                if ($rt == TRUE) {
                    $profil_image = $tweet['retweeted_status']['user']['profile_image_url'];
                } else {
                    $profil_image = $tweet['user']['profile_image_url'];
                }


                echo "<div ";
                echo "class=\"neu panel panel-default";
                if ($rt==TRUE)echo" isrt";
                echo"\" id=\"$tweet_id\"><div class=\"panel-heading\">";
                echo "$name  (@<a href=\"https://twitter.com/$screen_name\" target=\"_blank\">$screen_name</a>) ";
                if ($tweetdate < date("m.d")) echo "| $tweetdate_display ";
                echo "| <a href=\"https://twitter.com/$screen_name/status/$tweet_id\" target=\"_blank\">$tweettime</a> ";
                if ($rt == TRUE) {
                    echo "| <i class=\"fa fa-retweet  fa-lg\"></i> <a href=\"https://twitter.com/$rt_name\" target=\"_blank\">$rt_name</a>";
                }
                echo "</div><div class=\"panel-body\"><img src=\"$profil_image\"></img><p class=\"text\">$text</p></div></div>";


            };
        };
    };

};

$_SESSION['id'] = $id_neu;


?>