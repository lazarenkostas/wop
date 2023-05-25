<?php
add_theme_support('custom-logo');
add_action('admin_enqueue_scripts', 'mytheme_backend_scripts');


if (!function_exists('mytheme_backend_scripts')) {
    function mytheme_backend_scripts($hook)
    {
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script('custom_admin_script', get_template_directory_uri() . '/assets/js/jquery.admin.js', array('jquery'), '1.0.0', false);
    }
}

add_action('wp_enqueue_scripts', 'init_scripts');

add_action('after_setup_theme', 'myMenu');

function myMenu()
{
    register_nav_menu('top', 'menu_header');
}

function init_scripts()
{
    /* SCRIPTS */
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'https://code.jquery.com/jquery-3.6.4.min.js');

    wp_enqueue_script('jquery');
    wp_enqueue_script('js-main', get_template_directory_uri() . '/assets/js/jquery.main.js', array('jquery'), '1.0.0', false);

    if (is_admin()) {
        wp_enqueue_script('custom_admin_script', get_template_directory_uri() . '/assets/js/jquery.admin.js', array('jquery'), '1.0.0', false);
    }
    /* STYLES */
    wp_enqueue_style('reset.min', get_template_directory_uri() . '/assets/css/reset.css');
    wp_enqueue_style('main.min', get_template_directory_uri() . '/assets/css/main.css');
}

;

add_theme_support('post-thumbnails');


// Product Custom Post Type
function product_init()
{
    // set up product labels
    $labels = array(
        'name' => 'Cars',
        'singular_name' => 'Car',
        'add_new' => 'Add New Car',
        'add_new_item' => 'Add New Car',
        'edit_item' => 'Edit Car',
        'new_item' => 'New Car',
        'all_items' => 'All Cars',
        'view_item' => 'View Car',
        'search_items' => 'Search Cars',
        'not_found' => 'No Cars Found',
        'not_found_in_trash' => 'No Cars found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Cars',
    );

    // register post type
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'car'),
        'query_var' => true,
        'menu_icon' => 'dashicons-car',
        'supports' => array('title', 'thumbnail'),
    );
    register_post_type('car', $args);

    // register taxonomy
    register_taxonomy('car_type', 'car', array('hierarchical' => true, 'label' => 'Car Type', 'query_var' => true, 'rewrite' => array('slug' => 'car_type')));
    register_taxonomy('car_country', 'car', array('hierarchical' => true, 'label' => 'Car Country', 'query_var' => true, 'rewrite' => array('slug' => 'car_country')));
}

add_action('init', 'product_init');


///// Custom meta fields


function car_add_custom_box()
{
    $screens = ['car'];
    foreach ($screens as $screen) {
        add_meta_box(
            'fuel_box_id',                 // Unique ID
            'Fuel type',      // Box title
            'fuel_custom_box_html',  // Content callback, must be of type callable
            $screen                            // Post type
        );
        add_meta_box(
            'price_box_id',                 // Unique ID
            'Price',      // Box title
            'price_custom_box_html',  // Content callback, must be of type callable
            $screen                            // Post type
        );
        add_meta_box(
            'car_color_id',
            'Car color',
            'color_custom_box_html',
            $screen
        );
        add_meta_box(
            'car_power_id',
            'Car power',
            'power_custom_box_html',
            $screen
        );
    }
}

add_action('add_meta_boxes', 'car_add_custom_box');


function fuel_custom_box_html($post)
{
    $value = get_post_meta($post->ID, '_fuel_meta_key', true);
    ?>
    <label for="fuel_field">Select fuel type</label>
    <select name="fuel_field" id="fuel_field" class="postbox">
        <option value="petrol"<?php selected($value, 'petrol'); ?>>Petrol</option>
        <option value="diesel" <?php selected($value, 'diesel'); ?>>Diesel</option>
        <option value="gas" <?php selected($value, 'gas'); ?>>Gas</option>
    </select>
    <?php
}

function price_custom_box_html($post)
{
    ?>
    <label for="price_field">Enter the price</label>
    <input type="number" id="price_field" name="price_field"
           value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_price_meta_key', true)); ?>">
    <?php
}

function power_custom_box_html($post)
{
    ?>
    <label for="power_field">Enter the power</label>
    <input type="range" id="power_field" name="power_field" min="1" max="10" step="0.1"
           value="<?php echo esc_attr(get_post_meta(get_the_ID(), '_power_meta_key', true)); ?>">
    <p>Power value:
        <output id="power_value"></output>
    </p>
    <?php
}

function fuel_save_postdata($post_id)
{
    if (array_key_exists('fuel_field', $_POST)) {
        update_post_meta(
            $post_id,
            '_fuel_meta_key',
            $_POST['fuel_field']
        );
    }
}


add_action('save_post', 'fuel_save_postdata');

function price_save_postdata($post_id)
{
    if (array_key_exists('price_field', $_POST)) {
        update_post_meta(
            $post_id,
            '_price_meta_key',
            $_POST['price_field']
        );
    }
}

add_action('save_post', 'price_save_postdata');

function power_save_postdata($post_id)
{
    if (array_key_exists('power_field', $_POST)) {
        update_post_meta(
            $post_id,
            '_power_meta_key',
            $_POST['power_field']
        );
    }
}

add_action('save_post', 'power_save_postdata');

if (!function_exists('color_custom_box_html')) {
    function color_custom_box_html($post)
    {
        $custom = get_post_custom($post->ID);
        $header_color = (isset($custom["car_color"][0])) ? $custom["car_color"][0] : '';
        wp_nonce_field('color_custom_box_html', 'color_custom_box_html_nonce');
        ?>
        <script>
            jQuery(document).ready(function ($) {
                $('.color_field').each(function () {
                    $(this).wpColorPicker();
                });
            });
        </script>
        <div class="pagebox">
            <p><?php esc_attr_e('Choose car color'); ?></p>
            <input class="color_field" type="hidden" name="car_color" value="<?php esc_attr_e($header_color); ?>"/>
        </div>
        <?php
    }
}

if (!function_exists('car_color_save_meta_box')) {
    function car_color_save_meta_box($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_pages')) {
            return;
        }
        if (!isset($_POST['car_color']) || !wp_verify_nonce($_POST['color_custom_box_html_nonce'], 'color_custom_box_html')) {
            return;
        }
        $header_color = (isset($_POST["car_color"]) && $_POST["car_color"] != '') ? $_POST["car_color"] : '';
        update_post_meta($post_id, "car_color", $header_color);
    }
}

add_action('save_post', 'car_color_save_meta_box');

function car_shortcode()
{       ob_start();
    get_template_part('parts/car-loop');

    return ob_get_clean();;
}

    add_shortcode('car-shortcode', 'car_shortcode');


function header_customizer_register($wp_customize){

    $wp_customize->add_panel('header_custom_panel', array(
        'title' => 'Header Settings',
        'priority' => 1,
        'capability' => 'edit_theme_options',
    ));

    /* Pre-header */

    $wp_customize->add_section('pre_header_custom_section', array(
        'title' => 'Pre Header section',
        'description' => __('Here you can custom the Pre header content.'),
        'panel' => 'header_custom_panel',
    ));

    $wp_customize->add_section('header_custom_section', array(
        'title' => 'Header section',
        'description' => __('Here you can custom the header content.'),
        'panel' => 'header_custom_panel',
    ));


    $wp_customize->add_setting('pre_header_phone', array(
        'default' => __(''),
    ));
    $wp_customize->add_control( 'pre_header_phone', array(
        'label' => 'phone',
        'section' => 'pre_header_custom_section',
    ) );


}

add_action( 'customize_register', 'header_customizer_register' );


function sanitize_number( $string ) {
    return preg_replace( '/[^+\d]+/', '', $string );
}

add_filter( 'upload_mimes', 'support_for_upload_svg_files' );

function support_for_upload_svg_files( $mimes = array() ) {
    // allow SVG file upload
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';

    return $mimes;
}
