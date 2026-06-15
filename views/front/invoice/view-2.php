<?php
if (!defined('ABSPATH')){
    exit(__( 'No Access!', 'fci' ));
}

include( FCI_VIEW_PATH . 'header.php' );

?>
    <div class="view-2 container">
        <?php if ( $data['title'] || $data['print_date'] ||
            $data['transmission_date'] || $data['url'] || $data['email'] || $data['phone']  || $data['order_id_html'] ||
            $data['barcode'] || $data['sender'] || $data['postcode'] || $data['economical'] || $data['reg'] ): ?>
            <div class="fci-title-invoice"><?php echo $labels->get_label( 'invoice' ); ?></div>
            <table class="shop-detail fci-res-table fci-fix-table">
                <tbody>
                <tr>
					<?php if ( $data['logo'] || $data['url'] ): ?>
                        <td>
							<?php echo $data['logo']; ?>
							<?php echo $data['url']; ?>
                        </td>
					<?php endif; ?>
					<?php if (  $data['title'] || $data['email'] || $data['phone'] ): ?>
                        <td>
							<?php echo $data['title']; ?>
							<?php echo $data['email']; ?>
							<?php echo $data['phone']; ?>
                        </td>
					<?php endif; ?>
					<?php if ( $data['print_date'] || $data['transmission_date'] || $data['order_id_html']
					 || $data['barcode'] ): ?>
                        <td>
							<?php echo $data['print_date']; ?>
							<?php echo $data['transmission_date']; ?>
							<?php echo $data['order_id_html']; ?>
							<?php echo $data['barcode']; ?>
                        </td>
					<?php endif; ?>
                </tr>
                </tbody>
            </table>
		<?php endif; ?>
		<?php if ( $data['sender'] || $data['postcode'] || $data['economical'] || $data['reg'] || $data['recipient'] ||
            $data['full_name'] || $data['r_postcode'] || $data['r_phone'] || $data['r_email'] || $data['order_date'] ||
            $data['pay_method'] || $data['trans_id'] || $data['national_id'] || $data['shipping'] || $data['user_meta'] ||
            $data['order_meta'] ): ?>
            <table class="to-customer-table fci-res-table fci-fix-table">
                <tbody>
                <tr>
					<?php if ( $data['sender'] || $data['postcode'] || $data['economical'] || $data['reg'] ): ?>
                        <td class="sender-info">
							<?php echo $data['sender']; ?>
							<?php echo $data['postcode']; ?>
							<?php echo $data['economical']; ?>
							<?php echo $data['reg']; ?>
                        </td>
					<?php endif; ?>
					<?php if ( $data['recipient'] || $data['full_name'] || $data['r_postcode'] || $data['r_phone'] ||
                        $data['r_email'] || $data['order_date'] || $data['pay_method'] || $data['trans_id'] ||
                        $data['national_id'] || $data['shipping'] || $data['user_meta'] || $data['order_meta'] ): ?>
                        <td class="customer-detail">
							<?php echo $data['recipient']; ?>
							<?php echo $data['full_name']; ?>
							<?php echo $data['r_postcode']; ?>
							<?php echo $data['r_phone']; ?>
							<?php echo $data['r_email']; ?>
							<?php echo $data['order_date']; ?>
							<?php echo $data['pay_method']; ?>
							<?php echo $data['trans_id']; ?>
							<?php echo $data['national_id']; ?>
							<?php echo $data['shipping']; ?>
							<?php echo $data['user_meta']; ?>
							<?php echo $data['order_meta']; ?>
                        </td>
					<?php endif; ?>
                </tr>
                </tbody>
            </table>
		<?php endif; ?>
	    <?php if ( $data['products_table'] ): ?>
		    <?php echo $data['products_table'] ; ?>
	    <?php endif; ?>
	    <?php if ( $data['total_table'] ): ?>
		    <?php echo $data['total_table']; ?>
	    <?php endif; ?>
		<?php if ( $data['customer_note'] ): ?>
			<?php echo $data['customer_note']; ?>
		<?php endif; ?>
		<?php if ( $data['order_note'] ): ?>
			<?php echo $data['order_note']; ?>
		<?php endif; ?>
		<?php if ( $data['shop_sign'] || $data['customer_sign'] ): ?>
            <table class="fci-sign-table fci-res-table fci-fix-table">
                <tbody>
                <tr>
					<?php if ( $data['shop_sign'] ): ?>
                        <td><?php echo $data['shop_sign']; ?></td>
					<?php endif; ?>
					<?php if ( $data['customer_sign'] ): ?>
                        <td><?php echo $data['customer_sign']; ?></td>
					<?php endif; ?>
                </tr>
                </tbody>
            </table>
		<?php endif; ?>
		<?php echo $data['watermark']; ?>
    </div>
<?php
include( FCI_VIEW_PATH . 'footer.php' );