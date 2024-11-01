<?php
namespace Wcone\Admin;
 /**
  * 
  * @package    Wcone 
  * @since      3.0.0
  * @version    3.0.0
  * @author     ThemeLooks
  * @Websites:  http://themelooks.com/
  *
  */
 
abstract class Settings_Fields_Base {

  public static  $optionName;

  public static $getOptionData;

  use \Wcone\Admin\Field\Checkbox;
  use \Wcone\Admin\Field\Switcher;
  use \Wcone\Admin\Field\Colorpicker;
  use \Wcone\Admin\Field\MediaUpload;
  use \Wcone\Admin\Field\Timepicker;
  use \Wcone\Admin\Field\Textarea;
  use \Wcone\Admin\Field\Text;
  use \Wcone\Admin\Field\Selectbox;
  use \Wcone\Admin\Field\Number;
  use \Wcone\Admin\Field\TimezoneSelect;
  use \Wcone\Admin\Field\Zipcode;
  use \Wcone\Admin\Field\LocationSearch;
  use \Wcone\Admin\Field\MultipleSelect;
  use \Wcone\Admin\Field\Day_Based_Time;
  use \Wcone\Admin\Field\kmFeeRepeater;
  use \Wcone\Admin\Field\ZipcodeMultiInput;
  use \Wcone\Admin\Field\Product_Visibility_Time;


  public function __construct() {

    self::$optionName = $this->get_option_name();
    self::$getOptionData = get_option(self::$optionName);

    $this->tab_setting_fields();

  }

  public function get_option_name() {}
  public function tab_setting_fields() {}

  public function start_fields_section( $args ) {

    $default = [
      'title'     => esc_html__( 'Title goes to here', 'wcone' ),
      'class'     => '',
      'icon'      => '',
      'id'        => '',
      'display'   => 'none',
    ];

    $args = wp_parse_args( $args, $default );

    ?>
    <div id="<?php echo esc_attr( $args['id'] ); ?>" data-tab="<?php echo esc_attr( $args['id'] ); ?>" class="<?php echo esc_attr( $args['class'] ); ?>" style="display: <?php echo esc_attr( $args['display'] ); ?>;">
      <div class="tab-inner-container">

        <div class="fb-title-area">
          <h3 class="fb-tab-tilte"><i class="<?php echo esc_attr( $args['icon'] ); ?>"></i><?php echo esc_html( $args['title'] ); ?></h3>
          <?php $this->saveButton(); ?>
        </div>

        <div class="dashboard-content-wrap">
    <?php
  }

  public function end_fields_section() {
    $this->saveButton('fb-bottom-save-btn');
    echo '</div></div></div>';
  }

  public function saveButton( $class = '' ) {
    echo '<div class="'.esc_attr( $class ).' fb-top-save-btn"><button type="submit" class="fb-title-save-btn">'.esc_html__( 'Save Setting', 'wcone' ).'</button></div>';
  }

}