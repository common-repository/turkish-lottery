<?php
/*
Plugin Name: Turkish Lottery
Plugin URI:  https://developer.wordpress.org/plugins/the-basics/
Description: Basic WordPress Plugin Header Comment
Version:     20160911
Author:      Data Process
Author URI:  http://dataprocess.com.tr/en/
Text Domain: Lottery
License:     GPL2
 
{Lottery} is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
{Lottery} is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with {Lottery}. If not, see {http://www.gnu.org/licenses/gpl-2.0.html}.
*/

class kenni_turkish_lottery extends WP_Widget {
    
    // constructor
    function __construct(){
        $widget_ops = array('classname' => 'my_widget_class', 'description' => __('Turkish lottery plugin shows lottery results in turkey', 'lottery_plugin'));
	    $control_ops = array('width' => 400, 'height' => 300);
	    parent::WP_Widget(false, $name = __('Turkish Lottery Widget', 'lottery_plugin'), $widget_ops, $control_ops );
    }
    
    function form($instance){
            if( $instance) {
            $title = esc_attr($instance['title']);
            $color = esc_attr($instance['color']);
            $bcolor = esc_attr($instance['bcolor']);
            $fontsize = esc_attr($instance['fontsize']);
            } else {
            $title = '';
            $color = '';
            $bcolor = '';
            $fontsize = '';
            }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'lottery_plugin'); ?></label><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p> 
        <p>
            <label for="<?php echo $this->get_field_id('color'); ?>"><?php _e('Color:', 'lottery_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('color'); ?>" name="<?php echo $this->get_field_name('color'); ?>" type="text" value="<?php echo $color; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('bcolor'); ?>"><?php _e('Background Color:', 'lottery_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('bcolor'); ?>" name="<?php echo $this->get_field_name('bcolor'); ?>" type="text" value="<?php echo $bcolor; ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('fontsize'); ?>"><?php _e('Font Size:', 'lottery_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('fontsize'); ?>" name="<?php echo $this->get_field_name('fontsize'); ?>" type="text" value="<?php echo $fontsize; ?>" />
        </p>
    <?php }
    // update widget
    function update($new_instance, $old_instance) {
      $instance = $old_instance;
      // Fields
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['color'] = strip_tags($new_instance['color']);
      $instance['bcolor'] = strip_tags($new_instance['bcolor']);
      $instance['fontsize'] = strip_tags($new_instance['fontsize']);
      return $instance;
    }
    // display widget
    function widget($args, $instance) {
        extract( $args );
        // these are the widget options
        $title = apply_filters('widget_title', $instance['title']);
        $text = $instance['text'];
        $color = $instance['color'];
        $bcolor = $instance['bcolor'];
        $fontsize = $instance['fontsize'];
        echo $before_widget;
        // Display the widget
        
        echo "<br/>";
        //parsing data from a "www.thelotter.com" website.
        $section = file_get_contents('https://www.thelotter.com/lottery-results/');
        $myArray = explode("'LotteryName': 'Super Lotto 6/54',",$section);
        $struct= explode("WinningNumbersAdditionalResults",$myArray[1]);
        $result1=$struct[0];
        $pattern = "/\<span class=\"results-number iconMini\"(.*?)\<\/span\>/is";
        preg_match_all($pattern, $result1, $results);
        $variable=$results[0];
        $str="";
        foreach ($variable as $value) {
            $str.=" $value";
        }
        
        echo '<div class="widget-text wp_widget_plugin_box">';
        echo $after_widget;
        // Check if title is set
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }
        //change the color, backgound-color and font-size of output data
        $style="<style>";
        echo '<div id="change">';
          if ( $color ) {
               $style.="#change{color:$color}";
               $style .= "#change{margin-top:12px}";
           }
           if ( $bcolor ) {
               $style .= "#change{background-color:$bcolor}";
           }
           if ( $fontsize) {
               $style .= "#change{font-size:$fontsize}";
           }
           $style .= "#change{font-size:$fontsize}";
         $style .= "</style>";
         echo $str;
         echo $style;
         echo '</div>';
    }
}
    function kenni_addcssfile()
    {
        wp_enqueue_style( 'lotto', plugin_dir_url( __FILE__).'lotto.css');
    }
add_action( 'wp_enqueue_scripts', 'kenni_addcssfile' );
add_action('widgets_init', function(){
    register_widget('kenni_turkish_lottery');
});

?>
