<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */

/*
Plugin Name: Automatically publish highlights of any website, directly to your Blog
Plugin URI: http://roohit.com/site/urWidget.php
Description: Highlight as you surf => Directly posts your selection to your web-page/blog. <strong>Very Cool</strong>. Look/feel customizable. 
Version: 4.5.3.1
Author: RoohIt Team
Author URI: http://roohit.com
*/

/* 
* +--------------------------------------------------------------------------+
* | Copyright (c) 2006 RoohIt                   (email : support@roohit.com) |
* +--------------------------------------------------------------------------+
* | This program is free software; you can redistribute it and/or modify     |
* | it under the terms of the GNU General Public License as published by     |
* | the Free Software Foundation; either version 2 of the License, or        |
* | (at your option) any later version.                                      |
* |                                                                          |
* | This program is distributed in the hope that it will be useful,          |
* | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
* | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
* | GNU General Public License for more details.                             |
* |                                                                          |
* | You should have received a copy of the GNU General Public License        |
* | along with this program; if not, write to the Free Software              |
* | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA |
* +--------------------------------------------------------------------------+
*/

if (!defined('MyHighlights_INIT')) 
	define('MyHighlights_INIT', 1);
else 
	return;

$DOMAIN_NAME = 'http://roohit.com' ;
// Define a constant indicating that Auto Pub. is enabled
define("AUTO_PUB_PLUGIN","Y", true);

// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

include_once ('roohUtilsDup.php') ;
$ROOH_WDGT = 1 ;

$myhighlightspluginpath = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';

$AUTOPUB_SIG_CMNT_BGN = "<!-- Begin My Highlights Widget Plugin by RoohIt -->" ;
$AUTOPUB_SIG_CMNT_END = "<!-- End My Highlights Widget Plugin by RoohIt -->" ;

add_action("wp_footer", "showPoweredByWidget" );     // I have placed this function in an external file
//add_action("wp_footer", "alertNewUser2") ;

$myhighlights_settings = array(	
							array('enctickerid', '')
							, array('tickerwidth', '')
							, array('tickerheight', '')
							, array('language', 'en')
						);

$myhighlights_languages = array('zh'=>'Chinese', 'da'=>'Danish', 'nl'=>'Dutch', 'en'=>'English', 'fi'=>'Finnish', 'fr'=>'French', 'de'=>'German', 'he'=>'Hebrew', 'it'=>'Italian', 'ja'=>'Japanese', 'ko'=>'Korean', 'no'=>'Norwegian', 'pl'=>'Polish', 'pt'=>'Portugese', 'ru'=>'Russian', 'es'=>'Spanish', 'sv'=>'Swedish');

function myHighlights_widget($args) {
	extract($args);

	echo $before_widget;
	echo $before_title . '<strong>What I\'m Reading...</strong>' . $after_title;
	echo $after_widget;

	$enctickerid = get_option('myhighlights_enctickerid');

	$tickerwidth = get_option('myhighlights_tickerwidth');
	if ($tickerwidth == '') $tickerwidth = 220 ; 

	$tickerheight = get_option('myhighlights_tickerheight');
	if ($tickerheight == '') $tickerheight = 550 ; 
	//echo 'tickerheight=', $tickerheight, '.' ;


	$linkUrl = 'wp-admin/options-general.php?page=autopublish/autoPublish.php' ;
	if ( current_user_can('edit_plugins') )	{}
	else
		$linkUrl = 'mailto:' . get_bloginfo('admin_email') . '?subject=Please configure your widget&body=Visit ' . get_bloginfo('url') .'/'. $linkUrl . '%0A to Personalize the look and feel of your AutoPublish Widget.' . '%0a%0A--%0AHighlighting technology powered for FREE using http://rooh.it' ;


	global $DOMAIN_NAME ;
    global $AUTOPUB_SIG_CMNT_BGN ;
    global $AUTOPUB_SIG_CMNT_END ;

	echo $AUTOPUB_SIG_CMNT_BGN ;
    echo '
        <span id="plsConfigure" style="display:none; height:20px; width:'.$tickerwidth.'px; color:#ffffff; background-color:#ff0000; vertical-align:middle; padding:5px; float:left; text-align:center;"><a href="'.$linkUrl.'" style="color:#ffffff;font-weight:bold;">Configure your widget</a></span>
        ' ;

	// Extra 10 pixels is being added for a cleaner display
	$tickerwidth += 10 ;
	//echo 'tickerwidth=', $tickerwidth, '.' ;

        // Call the Widget wrapper which will check for the user preference and return either Flash/HTML code
	echo file_get_contents($DOMAIN_NAME . "/site/s_widget_wrapper.php?kR7s7Gj8uTzx07=" . $enctickerid );
	/*
	echo '
		<object type="application/x-shockwave-flash" 
            id="myHighlightsWidget" 
			allowNetworking="all" 
			data="'. $DOMAIN_NAME . '/site/s_widget.swf?kR7s7Gj8uTzx07='.$enctickerid.'" 
			width="'.$tickerwidth.'"
			height="'.$tickerheight.'" 
			wmode="transparent"
		>
			<param name="wmode" value="transparent" />
			<param name="movie" value="'. $DOMAIN_NAME . '/site/s_widget.swf?kR7s7Gj8uTzx07='.$enctickerid.'" />
			<param name="FlashVars" value="gig_lt=1249027062106&gig_pt=1249027065440&gig_g=1" />
		</object>
        <br />
		' ;
	*/
?>

	<script type="text/javascript" src="<?php echo $DOMAIN_NAME ;?>/site/4wp/wp_autoPub.php"></script>
    <script type="text/javascript" language="javascript">
	//alert(enc4tickerCookie) ;
	<?php 
		if ( (false == $enctickerid) || (enctickerid == '') )
		{
		?>	
			// Ask user to configure his/her widget
			elemid = document.getElementById('plsConfigure').style ;
			elemid.display='' ;
	
			// Make the background behind our widget red to indicate error/draw user's attention to this
			elemid = document.getElementById('myHighlightsWidget').style ;
			elemid.backgroundColor='red' ;
		<?php
		}
	?>
	</script>
    
<?php
     echo '
         <!-- RoohIt Button BEGIN -->
         <div style="width:100%; text-align:center;">
             <a id="roohitBtn" href="http://go.roohit.com" title="Highlight It"><img src="http://roohit.com/images/btns/h20/01_HTP.png" alt="Highlight It"
style="border:none;"></img></a>
         </div>
         <script type="text/javascript" src="http://roohit.com/site/btn.js"></script>
         <br /><br />&nbsp;
         <!-- RoohIt Button END -->
     ' ;

	echo $AUTOPUB_SIG_CMNT_END ;

	// Lets update the DB with the fact that the widget has been displayed again
	$viewed_count = get_option('myhighlights_viewedCount') ;
	update_option('myhighlights_viewedCount', $viewed_count+1);

	// after_widget should be displayed here
	//echo $after_widget;
}

function init_myHighlights(){
	global $myhighlights_settings ;

	add_action("plugins_loaded", "init_myHighlights");
	register_sidebar_widget("My Highlights", "myHighlights_widget");

    add_filter('admin_menu', 'myHighlights_admin_menu');

    add_option('myhighlights_enctickerid');
    add_option('myhighlights_tickerwidth');
    add_option('myhighlights_tickerheight');

    if (!isset($tweetRooh_inited)) $roohWidget_trked =
        get_option('roohWidget_trk');
    if (strlen($roohWidget_trked) == 0)
        roohSetupDup() ;
    else
        $roohWidget_trked = '1';
}

add_action( 'init', 'myHighlights_admin_warnings' );

function myHighlights_admin_warnings() {
    //global $myhighlights_enctickerid;
		function myHighlights_admin_warning() {
			//global $myhighlights_enctickerid;
			$myhighlights_enctickerid = get_option('myhighlights_enctickerid'); 
			if ( $myhighlights_enctickerid == '') {
				echo '<div id="myHighlights-warning" class="error fade"><p><strong>AutoPublish plugin is not configured yet: </strong>You must <a href="options-general.php?page=autopublish/autoPublish.php">Personalize it</a> for it to work.</p></div>';
			}
			$myhighlights_dragDone = get_option('myhighlights_viewedCount');
			if ($myhighlights_dragDone < 1 ) {
				echo "<div id='myHighlights-widget-warning' class='error fade'><p><strong>Drag-and-Drop</strong> <em>My Highlights</em> box <a href='widgets.php'>from Available Widgets to any Sidebar</a></p></div>";
			}
			/*
			else {
				echo "<div id='myHighlights-widget-dragged' class='error fade'><p><strong>DONE.$myhighlights_dragDone.</strong></p></div>";
			}
			*/
		}
	
		/*
		function myHighlights_admin_wrong_settings(){
		global $tweetHighlights;
		if ( substr($tweetHighlights[twitter], 0, 4) != "http" && $tweetHighlights['twitter'] != ""){
			echo '<div id="tweetHighlights-warning" class="updated fade"><p><strong>TweetHighlights plugin is not properly configured.</strong>The <a href="options-general.php?page=tweethighlights.php">Twitter URL</a> must begin with http.</p></div>';
			}
		}
		*/
add_action('admin_notices', 'myHighlights_admin_warning');
//add_action('admin_notices', 'myHighlights_admin_wrong_settings');
return;
}


function myHighlights_admin_menu()
{
    add_options_page('My Highlights Options', 'AutoPublish My Highlights', 8, __FILE__, 'myhighlights_plugin_options_php4');
}

////////////////////// Begin /////////////
// Add settings link on plugin page
function myHighlights_settings_link($links) {
  $settings_link = '<A href="options-general.php?page=autopublish/autoPublish.php">Settings</A>';
  array_unshift($links, $settings_link);
  return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'myHighlights_settings_link' );
///////////// End //////////////

function myHighlights_plugin_options_php4() {
	global $DOMAIN_NAME ;
    global $myhighlights_enctickerid;
    global $myhighlights_tickerwidth;
    global $myhighlights_tickerheight;

?>
    <div class="wrap">
    <h2>RoohIt: AutoPublish My Highlights Ticker Options</h2>

    <div align="center" id="message" class="updated fade"><p style="font-weight:bold;">Please tell us <a href="mailto:support@roohit.com?subject=WordPress: My Highlights plugin Feedback">what you think</a> of this plugin.</p></div>

	<?php require_once 'roohUtils.js' ; ?>

    <!--<h3>My Highlights Ticker Options</h3>-->
	
	<div class="option_container">

<style>
a.save_changes{
	color:#FF3300; text-decoration:underline; cursor:pointer;
}
a.save_changes:hover{
	color:#21759B; text-decoration:none;
}
</style>

    <form method="post" action="options.php" name="autoPublish">
<?php 
	$enctickerid = get_option('myhighlights_enctickerid');
	$tickerwidth = get_option('myhighlights_tickerwidth');
	if ($tickerwidth == '') $tickerwidth = 210 ; 
	$tickerheight = get_option('myhighlights_tickerheight');
	if ($tickerheight == '') $tickerheight = 550 ;  
?>

    <?php wp_nonce_field('update-options'); ?>

		<div class="row text_center" style="text-align:center; line-height:20px; padding-top:10px; padding-bottom:10px; background:#FFFFFF; border:1px dashed #CCCCCC;">
		<?php
		if ($enctickerid == '') { ?>
			Please make sure to <input class="button-primary" type="submit" name="Submit" value="<?php _e('Save Changes') ?>" /></center>
		<?php } else { ?>
			If you <strong>change the Height & Width</strong> of your widget, please <strong><a class="save_changes" onClick='window.location.reload()'>SAVE YOUR CHANGES</a></strong>
			<br />(all other changes will <em>take effect immediately and automatically</em>).<br />
		<?php } ?> 		
			
			
		</div>
		
		<span class="row text_center" style="text-align:right; float:left; width:100%; line-height:20px; padding-top:5px; padding-bottom:10px; margin-top:3px;">
			<img src="http://roohit.com/images/wp/bugs.png" border="0" align="absmiddle"  alt="bugs"/>&nbsp;Please report bugs <a href="http://roohit.com/forum/viewforum.php?f=7">here</a>
		</span>
		
		

	<iframe id="RoolHits" src="<?php echo $DOMAIN_NAME; ?>/site/s_lite_wp.php" frameborder="0" scrolling="no" width="1000px"  height="720px" style="overflow:auto;"></iframe>


		<div class="row">
			<div class="option_col1 show_arrow">
				<input type="hidden" size="24" name="myhighlights_enctickerid" id="myhighlights_enctickerid" value="<?php echo $enctickerid; ?>"/>
			</div>

			<div class="option_col2 show_arrow">
                <input type="hidden" size="3" name="myhighlights_tickerwidth" id="myhighlights_tickerwidth" style='color:#999999;' value="<?php echo $tickerwidth; ?>" onClick="this.select(); this.style.color='#000000';"/>
			</div>

			<div class="option_col2 show_arrow">
                <input type="hidden" size="4" name="myhighlights_tickerheight" id="myhighlights_tickerheight" style='color:#999999;' value="<?php echo $tickerheight; ?>" onClick="this.select(); this.style.color='#000000';" />
			</div>
		</div>


			<!--<br /><span>If any value is blank:<em>first</em> <strong>Login</strong>, then <strong>Personalize</strong> (change the size, colors, font etc.) & <strong>Publish</strong> <a href='<?php echo $DOMAIN_NAME ;?>/site/s_lite.php'>directly from here</a>.<br/></span>-->

        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="myhighlights_enctickerid, myhighlights_tickerwidth, myhighlights_tickerheight "/>
    
    <!-- Get the enc4ticker cookie value and automatically set it in this installation of WP -->
    <script type="text/javascript" src="<?php echo $DOMAIN_NAME ;?>/site/4wp/wp_autoPub.php"></script>
    <script type="text/javascript" language="javascript">
    document.getElementById('myhighlights_enctickerid').value = enc4tickerCookie ;
    document.getElementById('myhighlights_tickerwidth').value = twidth ;
    document.getElementById('myhighlights_tickerheight').value = theight ;
    //alert(enc4tickerCookie) ;
    if ( (enc4tickerCookie != '<?php echo get_option('myhighlights_enctickerid') ; ?>' )  ||
		 (twidth != '<?php echo get_option('myhighlights_tickerwidth') ; ?>' ) || 
		 (theight != '<?php echo get_option('myhighlights_tickerheight') ;?>' ) 
	)
    {
        //alert(enc4tickerCookie) ;
        //alert(twidth) ;
        //alert(twidth) ;

        document.autoPublish.submit();
    }
    </script>
    
	</form>
    
    <h2>Like this plugin?</h2>
    <p>Why not do any of the following:</p>
    <ul>
        <li>&nbsp;&nbsp;&nbsp;&nbsp;&bull; Write a blog posting <a href="<?php echo $DOMAIN_NAME ;?>site/blogThis.php">about it</a>, and link to it so other folks can find out about it.</li>
        <li>&nbsp;&nbsp;&nbsp;&nbsp;&bull; <a href="http://wordpress.org/extend/plugins/autopublish/">Give it a good rating</a> on WordPress.org, so others will find it easily!</li>
        <li>&nbsp;&nbsp;&nbsp;&nbsp;&bull; <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7137089">Donate a token of your appreciation</a>.</li>
    </ul>

    <h2>Need support?</h2>
    <p>If you have any problems or good ideas, please talk about them in the <a href="http://wordpress.org/tags/autopublish">Support forums</a>.</p>
    
    <h2>About</h2>
    <p><em>Rooh</em> means <em>soul</em>. When you Rooh It you get to the soul of the page. </p>
    <p>The no-signup, no-download, highlighter was conceived and created by Rohit Chandra and has been maintained by <a href="<?php echo $DOMAIN_NAME ;?>">RoohIt</a> since the very beginning.</p>

	</div>
	</div>
	</div>    
<?php
}

function alertNewUser2() 
{
    global $DOMAIN_NAME ;
    //require_once "http://roohit.com/site/wp2/wp_2rooh.php?ap=" . AUTO_PUB_PLUGIN . "&ihl=" . INST_HL_PLUGIN;
    if (!defined('NOTIF_LOADED')) {
    	echo file_get_contents($DOMAIN_NAME . "/site/4wp/wp_2rooh.php?ap=" . AUTO_PUB_PLUGIN . "&ihl=" . INST_HL_PLUGIN);
		define('NOTIF_LOADED', 'Y', true);
    } else {
    	//echo "Constant is already defined. Code not inserted";
    }
}

init_myHighlights() ;
?>
