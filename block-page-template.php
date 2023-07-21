<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php _e( 'Access Restricted', 'country-blocker' ); ?></title>
     <link rel="stylesheet" href="<?php echo plugins_url('assets/css/style.css', __FILE__); ?>">
</head>
<body>
    <div id="page" class="site">
        <div class="site-content">
            <h1 class="site-title"><?php _e( 'Access Restricted', 'country-blocker' ); ?></h1>
            <p><?php _e( 'Access to this website is restricted from your country.', 'country-blocker' ); ?></p>
        </div>
    </div>
</body>
</html>
