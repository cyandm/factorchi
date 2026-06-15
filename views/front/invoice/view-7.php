<?php
if (!defined('ABSPATH')){
    exit(__( 'No Access!', 'fci' ));
}



include( FCI_VIEW_PATH . 'header.php' );



?>
    <div class="view-7 container">
		<?php if ( $data['logo'] || $data['print_date'] || $data['transmission_date'] ): ?>
            <div class="one-section">
                <div class="right">
                    <?php if ( $data['logo'] ): ?>
                        <div class="logo"><?php echo $data['logo']; ?></div>
                    <?php endif; ?>
                    <div class="title"><?php echo $labels->get_label( 'goods-services' ); ?></div>
                </div>
                <?php if ( $data['print_date'] || $data['transmission_date'] ): ?>
                    <div class="date">
                       <span style="display: flex; align-items: center;"><i class="fal fa-calendar-day"></i><?php echo $data['print_date']; ?></span>
                        <?php echo $data['transmission_date']; ?>
                    </div>
                <?php endif; ?>
            </div>
		<?php endif; ?>
		<?php if ( $data['title'] || $data['sender '] ||  $data['postcode'] ||  $data['economical']|| $data['reg'] || $data['url'] ||
             $data['email'] ||  $data['phone'] || $data['recipient'] ||  $data['full_name'] || $data['r_postcode'] ||
             $data['r_phone'] ||  $data['r_email'] ||  $data['order_date'] ||  $data['pay_method']|| $data['trans_id'] ||
             $data['national_id'] || $data['shipping'] || $data['user_meta'] || $data['order_meta'] ): ?>
            <div class="two-section">

                <div class="right">
                        <?php if ( $data['order_id_html'] || $data['barcode'] ): ?>
                        <div class="one">
                            <?php echo $data['order_id_html']; ?>
                            <?php echo $data['barcode']; ?>
                        </div>
                        <?php endif; ?>
                        <?php if ( $data['url'] ||  $data['email'] || $data['phone'] ): ?>
                            <div class="two">
                                 <?php if ( $data['url'] ): ?>
                                     <span style="display: flex; align-items: center;"><i class="fal fa-globe-asia"></i><?php echo $data['url']; ?></span>
                                 <?php endif; ?>
                                <?php if (  $data['email'] ): ?>
                                    <span style="display: flex; align-items: center;"><i class="fal fa-envelope-open-text"></i><?php echo  $data['email']; ?></span>
                                <?php endif; ?>
                                <?php if ( $data['phone'] ): ?>
                                    <span style="display: flex; align-items: center;"><i class="fal fa-phone"></i> <?php echo $data['phone']; ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                </div>

                <div class="left">
                    <?php if ( $data['full_name'] || $data['recipient'] || $data['r_postcode'] || $data['r_phone'] || $data['r_email'] ||
                        $data['order_date'] || $data['pay_method'] || $data['trans_id'] || $data['national_id'] || $data['shipping'] ||
                        $data['user_meta'] || $data['order_meta'] ):?>
                    <div class="one">
                        <div class="label"></div>
                        <?php if ($data['full_name']): ?>
                            <span style="display: flex; align-items: center;">
                                <i class="fal fa-user"></i>
                            <?php echo $data['full_name']; ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($data['recipient']): ?>
                            <span style="display: flex; align-items: center;">
                                <i class="fal fa-map-marker"></i>
                            <?php echo $data['recipient']; ?>
                        </span>
                        <?php endif; ?>
                        <?php if ( $data['r_postcode']): ?>
                            <span style="display: flex; align-items: center;">
                                <i class="fal fa-mailbox"></i>
                            <?php echo  $data['r_postcode']; ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($data['r_phone']): ?>
                            <span style="display: flex; align-items: center;">
                                <i class="fal fa-phone"></i>
                            <?php echo $data['r_phone']; ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($data['r_email']): ?>
                            <span style="display: flex; align-items: center;">
                                <i class="fal fa-envelope"></i>
                            <?php echo $data['r_email']; ?>
                        </span>
                        <?php endif; ?>
                        <?php if ( $data['order_date']): ?>
                            <span style="display: flex; align-items: center;">
                                <i class="fal fa-calendar-day"></i>
                           <?php echo  $data['order_date']; ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($data['pay_method']): ?>
                            <span style="display: flex; align-items: center;">
                                <i class="fal fa-credit-card-blank"></i>
                           <?php echo $data['pay_method']; ?>
                        </span>
                        <?php endif; ?>
                        <?php if ( $data['trans_id']): ?>
                            <span style="display: flex; align-items: center;">
                                <i class="fal fa-exchange-alt"></i>
                            <?php echo  $data['trans_id']; ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($data['national_id']): ?>
                            <span style="display: flex; align-items: center;">
                                <i class="fal fa-lightbulb"></i>
                            <?php echo $data['national_id']; ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($data['shipping']): ?>
                            <span style="display: flex; align-items: center;">
                                <i class="fal fa-shipping-fast"></i>
                           <?php echo $data['shipping']; ?>
                        </span>
                        <?php endif; ?>
                        <?php if ( $data['user_meta']): ?>
                            <span style="display: flex; align-items: center;">
                          <?php echo  $data['user_meta']; ?>
                        </span>
                        <?php endif; ?>
                        <?php if ($data['order_meta']): ?>
                            <span style="display: flex; align-items: center;">
                          <?php echo $data['order_meta']; ?>
                        </span>
                        <?php endif; ?>

                    </div>
                    <?php endif; ?>

                    <?php if ( $data['title'] || $data['sender'] || $data['postcode'] || $data['economical'] ||
                     $data['reg'] ): ?>
                        <div class="two">
                            <div class="label"></div>

                            <?php if ($data['title']): ?>
                                <span style="display: flex; align-items: center;">
                                    <i class="fal fa-store"></i>
                               <?php echo $data['title']; ?>
                            </span>
                            <?php endif; ?>
                            <?php if ($data['sender']): ?>
                                <span style="display: flex; align-items: center;">
                                    <i class="fal fa-map-marker-alt"></i>
                             <?php echo $data['sender']; ?>
                            </span>
                            <?php endif; ?>
                            <?php if ($data['postcode']): ?>
                                <span style="display: flex; align-items: center;">
                                    <i class="fal fa-mailbox"></i>
                                <?php echo $data['postcode']; ?>
                            </span>
                            <?php endif; ?>
                            <?php if ( $data['economical']): ?>
                                <span style="display: flex; align-items: center;">
                                 <?php echo  $data['economical']; ?>
                            </span>
                            <?php endif; ?>
                            <?php if ( $data['reg']): ?>
                                <span style="display: flex; align-items: center;">
                                <?php echo  $data['reg']; ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
		<?php endif; ?>

        <div style="padding: 0 5px;">
			<?php if (  $data['products_table'] ): ?>
				<?php echo $data['products_table']; ?>
			<?php endif; ?>
            <table class="fci-fix-table fci-res-table">
                <tbody>
                <tr>
                    <td class="note-section">
						<?php if (  $data['customer_note'] ): ?>
							<?php echo  $data['customer_note']; ?>
						<?php endif; ?>
						<?php if (  $data['order_note'] ): ?>
							<?php echo  $data['order_note']; ?>
						<?php endif; ?>
                    </td>
                    <td class="total-section">
						<?php if ( $data['total_table'] ): ?>
							<?php echo  $data['total_table']; ?>
						<?php endif; ?>
                    </td>
                </tr>
                </tbody>
            </table>
			<?php if ( $data['shop_sign'] ||
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
		<?php echo $data['watermark']; ?>
    </div>
<?php
include( FCI_VIEW_PATH . 'footer.php' );