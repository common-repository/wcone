<div class="step-cart mini-cart-content">
	<h3><?php echo esc_html('Your Cart: ', 'wcone' ); ?></h3>
	<div class="cart_table">
		<!-- Cart table Header -->
        <div class="cart_table_header rb_product_details_form">
            <img src="<?php echo esc_url( WCONE_DIR_URL.'assets/img/icon/title_icon.png' ); ?>" alt="">
            <h4>
            <?php
            //
            echo esc_html( 'My Orders: ', 'wcone' );
            ?>
        	</h4>
        </div>
    	<!-- End Cart table Header -->
		<div class="mini-cart-content-inner">
			<div class="woocommerce-mini-cart-content widget_shopping_cart_content">
                <?php woocommerce_mini_cart(); ?>
            </div>
		</div>
	</div>
</div>