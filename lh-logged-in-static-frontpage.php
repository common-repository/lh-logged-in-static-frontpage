<?php
/*
Plugin Name: LH Logged In Static Frontpage
Plugin URI: https://lhero.org/portfolio/lh-logged-in-static-frontpage/
Description: Adds site specific content for your WordPress theme
Author: Peter Shaw
Version: 1.02
Author URI: https://shawfactor.com/
*/



if (!class_exists('LH_logged_in_static_frontpage')) {


class LH_logged_in_static_frontpage {
    
public function logged_in_show_on_front($return) {

if ( is_user_logged_in() and ( ! is_admin() ) ) {

$option = get_option('lh_logged_in_static_show_on_front');

if (isset($option) and (($option == 'posts') or ($option == 'page')) ){

return $option;

} else {

return $return;


}

} else {

return $return;

}

}    
    
    

public function logged_in_frontpage_page_id($return) {

if ( is_user_logged_in() and ( ! is_admin() ) ) {

$option = get_option('lh_logged_in_static_frontpage_page_id');

if (isset($option) and is_numeric($option) and ($page = get_page($option)) ){

return $option;

} else {

return $return;


}

} else {

return $return;

}

}

public function reading_section_options_callback($foo) { // Section Callback


    echo '';  
}

public function static_frontpage_callback($args) {  // Textbox Callback
    $option = get_option($args[0]);

$args = array(
    'selected'              => $option,
    'echo'                  => 1,
    'name'                  => $args[0],
    'show_option_none'      => __( '&mdash; Select &mdash;' ) // string
); 

wp_dropdown_pages( $args );

}

public function static_show_on_front_callback($args) {  // Textbox Callback

$option = get_option($args[0]);

?>

<p><label>
		<input name="<?php echo $args[0];  ?>" value="posts" class="tog" type="radio" <?php if (isset($option) and ($option == 'posts')){ ?> checked="checked" <?php } ?> >
		Your latest posts	</label>
	</p>
	<p><label>
		<input name="<?php echo $args[0];  ?>" value="page" class="tog" type="radio" <?php if (isset($option) and ($option == 'page')){ ?> checked="checked" <?php } ?> >
		A <a href="edit.php?post_type=page">static page</a> (select below)	</label>
	</p>


<?php

}


public function validate_show_on_front_settings( $input ) {

if (($input == 'posts') or ($input == 'page')){


return $input;

} else {

return false;

}
 
}


public function validate_static_frontpage_settings( $input ) {

if ($page = get_page($input)){


return $input;

} else {

return false;

}
 
}


public function static_frontpage_add_reading_section() {  
    add_settings_section(  
        'lh_logged_in_static_frontpage_settings_section', // Section ID 
        'Logged In Frontpage Settings', // Section Title
        array($this,"reading_section_options_callback"), // Callback
        'reading' // What Page?  This makes the section show up on the Reading Settings Page
    );
    
    
            add_settings_field( // Option 2
        'lh_logged_in_static_show_on_front', // Option ID
        'Show on Front', // Label
        array($this,"static_show_on_front_callback"), // !important - This is where the args go!
        'reading', // Page it will be displayed (General Settings)
        'lh_logged_in_static_frontpage_settings_section', // Name of our section
        array( // The $args
            'lh_logged_in_static_show_on_front' // Should match Option ID
        )  
    ); 
    
    
        register_setting('reading','lh_logged_in_static_show_on_front', array( $this, 'validate_show_on_front_settings' ));

    add_settings_field( // Option 1
        'lh_logged_in_static_frontpage_page_id', // Option ID
        'Logged in Homepage', // Label
        array($this,"static_frontpage_callback"), // !important - This is where the args go!
        'reading', // Page it will be displayed (General Settings)
        'lh_logged_in_static_frontpage_settings_section', // Name of our section
        array( // The $args
            'lh_logged_in_static_frontpage_page_id' // Should match Option ID
        )  
    ); 
    
    
        register_setting('reading','lh_logged_in_static_frontpage_page_id', array( $this, 'validate_static_frontpage_settings' ));




}

public function __construct() {
    
add_action('pre_option_show_on_front', array($this,"logged_in_show_on_front"));
    
    

add_action('pre_option_page_on_front', array($this,"logged_in_frontpage_page_id"));
add_action('admin_init', array($this,"static_frontpage_add_reading_section"));  

}


}

$lh_logged_in_static_frontpage_instance = new LH_logged_in_static_frontpage();

}

?>