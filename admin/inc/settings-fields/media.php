<?php 
namespace Wcone\Admin\Field;
 /**
  * 
  * @package    Wcone 
  * @since      3.0.0
  * @version    3.0.0
  * @author     ThemeLooks
  * @Websites:  http://themelooks.com/
  *
  */

trait MediaUpload {

	protected static $args;

	public function media( $args ) {

		$default = [
			'title' => '',
			'name'	=> '',
			'description'	=> '',
			'class'			=> '',
			'condition'		=> '',
			'is_pro'		=> false
		];

		self::$args = wp_parse_args( $args, $default );

		self::media_markup();
		
	}

	protected static function media_markup() {

		$optionName = self::$optionName;
	    $args = self::$args;
	    $getData = self::$getOptionData;
	    $fieldName  = $args['name'];
	    $value = !empty( $getData[$fieldName] ) ? $getData[$fieldName] : '';

	    $conditionData = '';
	    if( !empty( $args['condition'] ) ) {
	      $conditionData = json_encode( $args['condition'] );
	    }
		?>
		<div class="wcone-admin-field" data-condition="<?php echo esc_html($conditionData); ?>">
		    <h4><?php echo esc_html( $args['title'] ); echo !empty( $args['is_pro'] ) ? '<span class="pro-label">Pro</span>' : ''; ?></h4>
		    <div class="fb-field-group">
			    <input class="wcone_background_image" type="text" name="<?php echo esc_attr( $optionName ).'['.$fieldName.']'; ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo !empty( $args['is_pro'] ) ? 'disabled' : ''; ?>/>
			    <input type="button" class="wcone_image_upload_btn button-primary" value="<?php esc_html_e( 'Upload', 'wcone' ) ?>" <?php echo !empty( $args['is_pro'] ) ? 'disabled' : ''; ?> />
			    <?php 
				if( !empty( $args['description'] ) ) {
					echo '<p>'.esc_html( $args['description'] ).'</p>';
				}
				?>
			</div>
		</div>
		<?php
	}
}
