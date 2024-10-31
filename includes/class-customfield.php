<?php
namespace PISOL\MMQ\ADMIN;

class CustomFields{

    static $instance = null;

    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    } 

    function __construct()
    {
        add_action('pisol_custom_field_mmq_textarea', array($this,'editor'), 10, 2);
    }

    function editor($setting, $saved_value){
        $body = wp_kses_post( \WC_Admin_Settings::get_option( $setting['field'], ($setting['default'] ?? '') ) );
        $settings = array (
            'tinymce'       => array(
                'toolbar1'      => 'bold,italic,underline,separator,link,unlink,undo,redo',
                'toolbar2'      => '',
                'toolbar3'      => '',
            ),
            'textarea_rows' => 6,
            'media_buttons' => false,
            'wpautop' => true        
        );

        if(isset($setting['shortcode']) && is_array($setting['shortcode'])){
            $short_code_fields = $setting['shortcode'];
        }
        
        $label = isset($setting['label']) ? $setting['label'] : '';
        $desc = isset($setting['desc']) ? $setting['desc'] : '';
        $links = isset($setting['links']) ? $setting['links'] : '';
        $free = isset($setting['pro']) ? 'free-version' : '';
        ?>
        <div id="row_<?php echo esc_attr($setting['field']); ?>"  class="row py-4 border-bottom align-items-center <?php echo !empty($setting['class']) ? esc_attr($setting['class']) : ''; ?> <?php esc_attr_e($free); ?>">
            <div class="col-12 col-md-3">
            <label class="h6 mb-0" for="<?php echo esc_attr($setting['field']); ?>"><?php echo wp_kses_post($label); ?></label>
            <?php echo wp_kses_post($desc != "" ? '<br><small>'.$desc.'</small><br>': ""); ?>
            <?php echo wp_kses_post($links != "" ? $links: ""); ?>
            </div>
            <div class="col-12 col-md-9">
            <?php wp_editor( $body, esc_attr( $setting['field'] ), $settings ); ?>
            <?php if(isset($short_code_fields) && !empty($short_code_fields)): ?>
                <div class="mt-1">
                    <p>Short codes:</p>
                        <?php foreach($short_code_fields as $field => $desc): ?>
                            <span style="display:inline-block; margin-top:5px;" ><kbd title="<?php esc_attr_e($desc); ?>" class="pi-selectable"><?php echo $field; ?></kbd></span>
                        <?php endforeach; ?>
                </div>
            <?php endif; ?>
            </div>
        </div>
        <?php
    }

}

CustomFields::get_instance();