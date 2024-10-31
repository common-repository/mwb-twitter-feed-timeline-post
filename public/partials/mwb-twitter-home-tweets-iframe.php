<?php 
/**
* Function for showing Home timeline tweets in Iframe widget on front-end.
*/
if( !defined('ABSPATH')){
    exit;
}
$counter = 1;
$mwb_twitter_link = __('click here','mwb_twitter_for_wordpress');
if(is_array($mwb_twitter_hometweet_response) && !empty($mwb_twitter_hometweet_response)){   
    ?>
    <div class="mwb_twitter_home_timeline_tweets">
        <ul>
            <?php
            foreach($mwb_twitter_hometweet_response as $home_tweet_key => $home_tweet_value){    
                if($counter <= (count($mwb_twitter_hometweet_response)-1)){
                    ?>
                    <li>
                        <img src="<?php echo $home_tweet_value['user']['profile_image_url']; ?>">
                        <div class="mwb_twitter_user_data">
                            <?php
                            if(isset($home_tweet_value['text']) && !empty($home_tweet_value['text']))
                            {
                                $mwb_tweet_regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?).*$)@";
                                $mwb_tweet_data = preg_replace($mwb_tweet_regex, ' ', $home_tweet_value['text']);
                                echo $mwb_tweet_data;
                            }
                            ?>
                        </div>
                        <?php 
                        echo $home_tweet_value['source'];
                        ?>
                    </li>
                    <?php
                }
                $counter++;
            }
            ?>
        </ul>
    </div>
    <?php
}
?>