<?php
/**
 * @package OpenAI Modules for Beaver Builder
 */

class BotterFly_TemplatesPage_Module extends FLBuilderModule {

    public function __construct() {
        parent::__construct( array(
            'name' => __( 'BotterFly Menu', 'botterfly-custom-bb-modules' ),
            'description' => __( 'BotterFly Module', 'botterfly-custom-bb-modules' ),
            'category' => __( 'OpenAI Modules', 'botterfly-custom-bb-modules' ),
            'dir' => botterfly_custom_bb_modules_PATH . 'modules/templates-page/',
            'url' => botterfly_custom_bb_modules_URL . 'modules/templates-page/',
            'partial_refresh' => true,
        ) );
        $this->add_css( 'bt-css', '//stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css' );
        $this->add_js( 'bt-js', '//stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array( 'jquery' ), '4.5.2', true );
    }

}

FLBuilder::register_module( 'BotterFly_TemplatesPage_Module', array() );

?>
