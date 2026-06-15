<?php
if (!defined('ABSPATH')){
    exit(__( 'No Access!', 'fci' ));
}

include( FCI_VIEW_PATH . 'header.php' );


?>
    <div class="view-5 container">
        <table class="shop-detail fci-res-table fci-fix-table">
            <tbody>
            <tr>
				<?php if ( $data['order_id_html'] || $data['logo'] ): ?>
                    <td>
						<?php echo $data['order_id_html']; ?>
						<?php echo  $data['logo']; ?>
                    </td>
				<?php endif; ?>
				<?php if ( $data['title'] || $data['sender'] || $data['postcode'] || $data['print_date'] ||
                    $data['transmission_date'] || $data['phone'] || $data['economical'] || $data['reg'] ): ?>
                    <td>
                        <table class="fci-fix-table">
                            <tbody>
                            <tr>
								<?php if ( $data['title'] ||  $data['sender'] || $data['postcode']  ): ?>
                                    <td>
										<?php echo $data['title']; ?>
										<?php echo  $data['sender']; ?>
										<?php echo $data['postcode'] ; ?>
                                    </td>
								<?php endif; ?>
								<?php if ( $data['print_date'] ||  $data['transmission_date'] || $data['phone'] ||
                                    $data['economical'] || $data['reg'] ): ?>
                                    <td>
										<?php echo $data['print_date']; ?>
										<?php echo  $data['transmission_date']; ?>
										<?php echo $data['phone']; ?>
										<?php echo $data['economical']; ?>
										<?php echo $data['reg']; ?>
                                    </td>
								<?php endif; ?>
                            </tr>
                            </tbody>
                        </table>
                    </td>
				<?php endif; ?>
            </tr>
            </tbody>
        </table>
	    <?php if ( $data['products_table'] ): ?>
		    <?php echo $data['products_table']; ?>
	    <?php endif; ?>
	    <?php if ( $data['total_table'] ): ?>
		    <?php echo $data['total_table']; ?>
	    <?php endif; ?>
		<?php if ( $data['recipient '] ||  $data['full_name'] ||  $data['r_postcode'] ||  $data['r_phone'] ||
		   $data['r_email'] || $data['order_date'] |  $data['pay_method'] ||  $data['trans_id'] ||
		   $data['national_id'] || $data['shipping'] || $data['user_meta'] || $data['order_meta'] ): ?>

            <table class="customer-detail fci-fix-table">
                <tbody>
				<?php if ( $data['recipient '] ): ?>
                    <tr>
                        <td><?php echo str_replace( ':', '', $labels->get_label( 'recipient' ) ); ?></td>
                        <td><?php echo $data['recipient ']; ?></td>
                    </tr>
				<?php endif; ?>
				<?php if ( $data['full_name'] ): ?>
                    <tr>
                        <td><?php echo str_replace( ':', '', $labels->get_label( 'full-name' ) ); ?></td>
                        <td><?php echo $data['full_name']; ?></td>
                    </tr>
				<?php endif; ?>
				<?php if (  $data['r_postcode'] ): ?>
                    <tr>
                        <td><?php echo str_replace( ':', '', $labels->get_label( 'postcode' ) ); ?></td>
                        <td><?php echo  $data['r_postcode']; ?></td>
                    </tr>
				<?php endif; ?>
				<?php if ( $data['r_phone'] ): ?>
                    <tr>
                        <td><?php echo str_replace( ':', '', $labels->get_label( 'phone' ) ); ?></td>
                        <td><?php echo $data['r_phone']; ?></td>
                    </tr>
				<?php endif; ?>
				<?php if (  $data['r_email'] ): ?>
                    <tr>
                        <td><?php echo str_replace( ':', '', $labels->get_label( 'email' ) ); ?></td>
                        <td><?php echo  $data['r_email']; ?></td>
                    </tr>
				<?php endif; ?>
				<?php if ( $data['order_date'] ): ?>
                    <tr>
                        <td><?php echo str_replace( ':', '', $labels->get_label( 'order-date' ) ); ?></td>
                        <td><?php echo $data['order_date']; ?></td>
                    </tr>
				<?php endif; ?>
				<?php if ( $data['pay_method'] ): ?>
                    <tr>
                        <td><?php echo str_replace( ':', '', $labels->get_label( 'payment-method' ) ); ?></td>
                        <td><?php echo $data['pay_method']; ?></td>
                    </tr>
				<?php endif; ?>
				<?php if ( $data['trans_id'] ): ?>
                    <tr>
                        <td><?php echo str_replace( ':', '', $labels->get_label( 'transaction-id' ) ); ?></td>
                        <td><?php echo $data['trans_id']; ?></td>
                    </tr>
				<?php endif; ?>
				<?php if (  $data['national_id'] ): ?>
                    <tr>
                        <td><?php echo str_replace( ':', '', $labels->get_label( 'meli-code' ) ); ?></td>
                        <td><?php echo  $data['national_id']; ?></td>
                    </tr>
				<?php endif; ?>
				<?php if ( $data['shipping'] ): ?>
                    <tr>
                        <td><?php echo str_replace( ':', '', $labels->get_label( 'shipping-method' ) ); ?></td>
                        <td><?php echo $data['shipping']; ?></td>
                    </tr>
				<?php endif; ?>
				<?php if (  $data['user_meta'] ): ?>
                    <tr>
                        <td colspan="2"><?php echo  $data['user_meta']; ?></td>
                    </tr>
				<?php endif; ?>
				<?php if ( $data['order_meta'] ): ?>
                    <tr>
                        <td colspan="2"><?php echo $data['order_meta']; ?></td>
                    </tr>
				<?php endif; ?>
                </tbody>
            </table>
		<?php endif; ?>
        <div class="footer">
			<?php if ( $data['customer_note'] ): ?>
				<?php echo $data['customer_note']; ?>
			<?php endif; ?>
			<?php if ( $data['order_note'] ): ?>
				<?php echo $data['order_note']; ?>
			<?php endif; ?>
	        <?php if ( $data['shop_sign'] ): ?>
                <?php echo $data['shop_sign']; ?>
	        <?php endif; ?>
	        <?php if ( $data['deliver_date'] ): ?>
                <?php echo $data['deliver_date']; ?>
	        <?php endif; ?>
	        <?php if ( $data['deliver_time'] ): ?>
                <?php echo $data['deliver_time']; ?>
	        <?php endif; ?>
	        <?php if ( $data['customer_sign'] ): ?>
                <?php echo $data['customer_sign']; ?>
	        <?php endif; ?>
			<?php echo $data['barcode']; ?>
			<?php if ( $data['url'] ): ?>
				<?php echo $data['url']; ?>
			<?php endif; ?>
			<?php if ( $data['email'] ): ?>
				<?php echo $data['email']; ?>
			<?php endif; ?>
        </div>
    </div>
<?php
include( FCI_VIEW_PATH . 'footer.php' );