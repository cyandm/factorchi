<?php
if (!defined('ABSPATH')){
    exit(__( 'No Access!', 'fci' ));
}



include( FCI_VIEW_PATH . 'header.php' );


?>
    <div class="top-line"></div>
    <div class="view-8 container">
		<?php if ( $data['logo'] || $data['print_date'] || $data['transmission_date'] ): ?>
            <table class="header-table fci-res-table fci-fix-table">
                <tbody>
                <tr>
					<?php if ( $data['logo'] ): ?>
                        <td><?php echo $data['logo']; ?></td>
					<?php endif; ?>
                    <td class="fci-title-invoice"><?php echo $labels->get_label( 'goods-services' ); ?></td>
					<?php if ( $data['print_date'] || $data['transmission_date'] ): ?>
                        <td class="print-date">
                            <?php echo $data['print_date']; ?>
                            <?php echo $data['transmission_date']; ?>
                        </td>
					<?php endif; ?>
                </tr>
                </tbody>
            </table>
		<?php endif; ?>
		<?php if ( $data['title']  || $data['sender'] || $data['postcode'] || $data['economical'] || $data['reg'] || $data['url'] ||
            $data['email'] || $data['phone'] || $data['recipient'] || $data['full_name'] || $data['r_postcode'] ||
            $data['r_phone'] || $data['r_email'] || $data['order_date'] || $data['pay_method'] || $data['trans_id'] ||
            $data['national_id'] || $data['shipping'] || $data['user_meta'] || $data['order_meta'] ): ?>
            <table class="fci-customer-shop-detail-table fci-res-table fci-border-table">
                <tbody>
                <?php if ( $data['full_name']  || $data['recipient']  || $data['r_postcode']  || $data['r_phone']  ||
                    $data['r_email']  || $data['order_date']  || $data['pay_method']  || $data['trans_id']  ||
                    $data['national_id']  ||  $data['shipping']  ||  $data['user_meta'] ||  $data['order_meta'] ||
                     $data['order_id_html']  || $data['barcode']  ): ?>
                    <tr>
                        <th>
                            <span><?php echo $labels->get_label( 'shopper' ); ?></span>
                        </th>
                        <td class="info">
                            <?php echo  $data['full_name']; ?>
                            <?php echo $data['recipient']; ?>
                            <?php echo $data['r_postcode']; ?>
                            <?php echo $data['r_phone']; ?>
                            <?php echo  $data['r_email']; ?>
                            <?php echo $data['order_date']; ?>
                            <?php echo  $data['pay_method'] ; ?>
                            <?php echo  $data['trans_id']; ?>
                            <?php echo    $data['national_id']; ?>
                            <?php echo  $data['shipping']; ?>
                            <?php echo $data['user_meta']; ?>
                            <?php echo  $data['order_meta'] ; ?>
                        </td>
                        <td class="last">
                            <?php echo  $data['order_id_html']; ?>
                            <?php echo  $data['barcode']; ?>
                        </td>
                    </tr>
                <?php endif; ?>
				<?php if (  $data['title'] || $data['sender'] ||  $data['postcode'] ||  $data['economical'] ||
                    $data['reg'] || $data['url'] ||  $data['email'] ||  $data['phone'] ): ?>
                    <tr>
                        <th>
                            <span><?php echo $labels->get_label( 'seller' ); ?></span>
                        </th>
                        <td class="info">
							<?php echo  $data['title']; ?>
							<?php echo $data['sender']; ?>
							<?php echo $data['postcode']; ?>
							<?php echo  $data['economical']; ?>
							<?php echo   $data['reg']; ?>
                        </td>
                        <td class="last">
							<?php echo $data['url'] ; ?>
							<?php echo  $data['email']; ?>
							<?php echo  $data['phone']; ?>
                        </td>
                    </tr>
				<?php endif; ?>
                </tbody>
            </table>
		<?php endif; ?>
        <div style="padding: 0 5px;">
			<?php if (  $data['products_table'] ): ?>
				<?php echo  $data['products_table']; ?>
			<?php endif; ?>
            <table class="fci-fix-table fci-res-table">
                <tbody>
                <tr>
                    <td class="note-section">
						<?php if ( $data['customer_note'] ): ?>
							<?php echo $data['customer_note']; ?>
						<?php endif; ?>
						<?php if (  $data['order_note'] ): ?>
							<?php echo $data['order_note']; ?>
						<?php endif; ?>
                    </td>
                    <td class="total-section">
						<?php if ( $data['total_table'] ): ?>
							<?php echo $data['total_table']; ?>
						<?php endif; ?>
                    </td>
                </tr>
                </tbody>
            </table>
			<?php if ( $data['shop_sign']||
                $data['customer_sign'] ): ?>
                <table class="fci-sign-table fci-res-table fci-fix-table fci-border-table">
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
        </div>
		<?php echo  $data['watermark ']; ?>
    </div>
<?php
include( FCI_VIEW_PATH . 'footer.php' );