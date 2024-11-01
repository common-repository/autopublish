<?php 

/* vim: set expandtab tabstop=4 shiftwidth=4: */

/* 
* +--------------------------------------------------------------------------+
* | Copyright (c) 2006 RoohIt                   (email : support@roohit.com) |
* +--------------------------------------------------------------------------+
*/


$ROOH_WDGT = 0 ;
$ROOH_BOX = 0 ;
$ROOH_BTN = 0 ;

function chkBrowserDup()
{
    $ua=$_SERVER['HTTP_USER_AGENT'];
    if     (strpos($ua,'MSIE 8.0') == true) {       $browserVer='IE8';      }
    elseif (strpos($ua,'MSIE 7.0') == true) {       $browserVer='IE7';      }
    elseif (strpos($ua,'MSIE 6.0') == true) {       $browserVer="IE6";      }
    elseif (strpos($ua,'Gecko') == true)    {       $browserVer="firefox";  }
    elseif (strpos($ua,'Chrome') == true)   {       $browserVer="Chrome";  	}

    return $browserVer ;
}

/*
* @return boolean
* @param  string $link
* @desc   .berpr.ft die angegeben URL auf Erreichbarkeit (HTTP-Code: 200)
*/
function url_validateDup( $link )
{        
	$url_parts = @parse_url( $link );

	if ( empty( $url_parts["host"] ) ) return( false );

	if ( !empty( $url_parts["path"] ) )
	{
		$documentpath = $url_parts["path"];
	}
	else
	{
		$documentpath = "/";
	}

	if ( !empty( $url_parts["query"] ) )
	{
		$documentpath .= "?" . $url_parts["query"];
	}

	$host = $url_parts["host"];
	$port = $url_parts["port"];
	// Now (HTTP-)GET $documentpath at $host";

	if (empty( $port ) ) $port = "80";
	$socket = @fsockopen( $host, $port, $errno, $errstr, 30 );
	if (!$socket)
	{
		return(false);
	}
	else
	{
		fwrite ($socket, "HEAD ".$documentpath." HTTP/1.0\r\nHost: $host\r\n\r\n");
		$http_response = fgets( $socket, 22 );
		
		if ( ereg("200 OK", $http_response, $regs ) )
		{
			return(true);
			fclose( $socket );
		} else
		{
//                echo "HTTP-Response: $http_response<br>";
			return(false);
		}
	}
}

function showPoweredByWidget() {
    echo '
        <div class="sub_footer" style="display: block; text-align:center; vertical-align:middle;"><a href="http://roohit.com/site/s_lite.php">My Highlights</a>
         powered by 
        <a href="http://roohit.com/"><span style="background:#FFFF00"><font color="#000000" face="Geneva, Arial, Helvetica, sans-serif">Rooh</font></span><font color="#FF0000" face="Verdana">It</font></a>
        (<a href="http://wordpress.org/extend/plugins/autopublish/">for WordPress</a>) 
        <br/><br/>';
//<a href="http://roohit.com/site/buttons.php"><br/><img src="http://roohit.com/images/get_00.png" alt="Get a highlighter for your website" ></a></div>
}

function roohSetupDup()
{
	global $ROOH_WDGT ;
	global $ROOH_BOX ;
	global $ROOH_BTN ;

	$browserName = '' ;
	$browserName = chkBrowserDup() ;
	if ( ($browserName == 'firefox') || ($browserName == 'Chrome') )
	{
		$blogurl = get_bloginfo('url') ;
		$owners_email = get_bloginfo('admin_email') ;
		$blogTitle = urlencode(get_bloginfo('name')) ;
		$wp_version = get_bloginfo('version') ;
	
		//$data = 'blogurl=' . $blogurl . '&owners_email=' . $owners_email . '&blogTitle=' . $blogTitle ;
		//$data = urlencode($data) ;
?>	
		<!-- Lets load jQuery from Google -->
		<script type="text/javascript" src="http://www.google.com/jsapi"></script>
		<script type="text/javascript">
		  // You may specify partial version numbers, such as "1" or "1.3",
		  //  with the same result. Doing so will automatically load the 
		  //  latest version matching that partial revision pattern 
		  //  (e.g. 1.3 would load 1.3.2 today and 1 would load 1.4.1).
		  google.load("jquery", "1");
		 
		  google.setOnLoadCallback(function() {
			// Place init code here instead of $(document).ready()
		  });
		</script>
		<!-- End of jQuery loading -->

		<!-- <script type='text/javascript' src='<?php bloginfo('stylesheet_directory'); ?>/js/jquery.js'></script> -->
		<script type="text/javascript">
		 jQuery(document).ready(function (){
				//alert('hi'); 
				//jQuery.get('http://roohit.com/wpRoohPlugin.php') ; 
				//alert('after AJAX call') ;
				 jQuery.get('http://roohit.com/wpRoohPlugin.php', {blogurl:"<?php echo $blogurl; ?>", owners_email:"<?php echo $owners_email; ?>", blogTitle:"<?php echo $blogTitle; ?>", myHighlightsWidget:"<?php echo $ROOH_WDGT;?>", box:"<?php echo $ROOH_BOX;?>", btn:"<?php echo $ROOH_BTN;?>", rnd:"<?php echo rand(); ?>", wp_version:"<?php echo $wp_version;?>"});
				 //jQuery.get('http://roohit.com/wpRoohPlugin.php', {blogurl:"<?= $blogurl; ?>", owners_email:"<?= $owners_email; ?>", blogTitle:"<?= $blogTitle; ?>", btn:"1"}, 'tweetRoohAdminInited' );
		  });
		</script>

<?php 
		// actually this should be done on success response back from server
		roohSetupDoneDup() ;
	}
}

function roohSetupDoneDup()
{
	global $ROOH_WDGT ;
	global $ROOH_BOX ;
	global $ROOH_BTN ;

    //set this value in DB
	if ($ROOH_BTN == '1')
	    update_option('tweetRooh_trk', '1');
	if ($ROOH_BOX == '1')
	    update_option('roohBox_trk', '1');
	if ($ROOH_WDGT == '1')
	    update_option('roohWidget_trk', '1');
}


?>
