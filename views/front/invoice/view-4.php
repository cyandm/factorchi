<?php
if (!defined('ABSPATH')){
    exit(__( 'No Access!', 'fci' ));
}



include( FCI_VIEW_PATH . 'header.php' );


?>
    <div class="view-4 container">
		<?php if ( $data['logo'] || $data['url'] || $data['print_date'] || $data['transmission_date'] ||
            $data['order_id_html'] || $data['barcode'] ): ?>
            <table class="shop-detail fci-res-table fci-fix-table">
                <tbody>
                <tr>
                    <td class="fci-title-invoice"><?php echo $labels->get_label( 'goods-services' ); ?></td>
					<?php if ( $data['logo'] || $data['url'] ): ?>
                        <td>
							<?php echo $data['logo']; ?>
							<?php echo $data['url']; ?>
                        </td>
					<?php endif; ?>
					<?php if ( $data['print_date'] || $data['transmission_date'] || $data['order_id_html'] ||
                        $data['barcode'] ): ?>
                        <td>
							<?php echo $data['print_date'] ; ?>
							<?php echo $data['transmission_date']; ?>
							<?php echo $data['order_id_html']; ?>
							<?php echo $data['barcode']; ?>
                        </td>
					<?php endif; ?>
                </tr>
                </tbody>
            </table>
		<?php endif; ?>
        <div class="wrapper">
			<?php if ( $data['title'] || $data['economical'] || $data['reg'] || $data['sender'] ||
			 $data['postcode'] ||  $data['phone'] || $data['email'] ): ?>
                <div class="sender-info">
                    <div class="title"><?php echo $labels->get_label( 'shop-spec' ); ?></div>
                    <div class="content">
                        <div>
							<?php echo $data['title']; ?>
							<?php echo $data['economical']; ?>
							<?php echo $data['reg']; ?>
                        </div>
                        <div><?php echo $data['sender']; ?></div>
                        <div>
							<?php echo $data['postcode']; ?>
							<?php echo $data['phone']; ?>
							<?php echo $data['email']; ?>
                        </div>
                    </div>
                </div>
			<?php endif; ?>
            <?php if ( $data['recipient'] || $data['full_name'] || $data['r_postcode'] || $data['r_phone'] ||
                $data['r_email'] || $data['order_date'] || $data['pay_method'] || $data['trans_id'] ||
                $data['national_id'] || $data['shipping'] || $data['user_meta'] || $data['order_meta'] ): ?>
                <div class="customer-detail">
                    <div class="title"><?php echo $labels->get_label( 'customer-spec' ); ?></div>
                    <div class="content">
                        <div>
							<?php echo $data['recipient']; ?>
							<?php echo $data['full_name']; ?>
							<?php echo $data['r_postcode']; ?>
							<?php echo   $data['national_id']; ?>
                        </div>
                        <div>
							<?php echo  $data['r_phone']; ?>
							<?php echo  $data['r_email']; ?>
                        </div>
                        <div>
							<?php echo  $data['order_date']; ?>
							<?php echo $data['pay_method']; ?>
							<?php echo $data['trans_id']; ?>
							<?php echo $data['shipping']; ?>
							<?php echo  $data['user_meta']; ?>
							<?php echo  $data['order_meta']; ?>
                        </div>
                    </div>
                </div>
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
    </div>
<?php
include( FCI_VIEW_PATH . 'footer.php' );