<?php
/**
 * Header
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <!-- Set up Meta -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta charset="<?php bloginfo('charset'); ?>">

    <!-- Set the viewport width to device width for mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <!-- Remove Microsoft Edge's & Safari phone-email styling -->
    <meta name="format-detection" content="telephone=no,email=no,url=no">

    <!-- Add external fonts below (GoogleFonts / Typekit) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,700;1,400&display=swap"
          rel="stylesheet">

    <?php wp_head(); ?>
</head>

<body <?php body_class('no-outline'); ?>>
<?php wp_body_open(); ?>

<header>
    <?php $phone = get_theme_mod('pre_header_phone'); ?>

    <div class="header-wrapper">
    <?php if (isset($phone) && !empty($phone)) : ?>
    <div class="header-phone">
        Phone: <a href="tel:<?php echo sanitize_number($phone); ?>"><?php echo $phone; ?></a>
    </div>
    <?php endif; ?>
    <div class="header-logo">
        <a class="header__branding" href="<?php echo get_home_url(); ?>">
        <?php
        $custom_logo_id = get_theme_mod('custom_logo');
        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
        if (has_custom_logo()) {
            echo '<img src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '">';
        } else {
            echo '<h1>' . get_bloginfo('name') . '</h1>';
        }
        ?>
        </a>
    </div>
    </div>
</header>