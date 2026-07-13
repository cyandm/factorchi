<?php
if (!defined('ABSPATH')) {
    exit(__('No Access!', 'fci'));
}

include(FCI_VIEW_PATH . 'header.php');

?>
    <div class="classic container">
		<?php if ($type === 'pre-invoice'): ?>
            <div class="fc-document-type"><?php echo esc_html($labels->get_label('pre-invoice')); ?></div>
		<?php endif; ?>
		<?php if ($data['title'] || $data['print_date'] ||
            $data['transmission_date'] || $data['url'] || $data['email'] || $data['phone'] || $data['order_id_html'] ||
            $data['sender'] || $data['postcode'] || $data['economical'] || $data['reg']): ?>
            <table class="shop-detail fci-res-table fci-fix-table">
                <tbody>
                <tr>
					<?php $colspan = 0; ?>
					<?php if ($data['title'] || $data['url'] || $data['email'] || $data['phone']): ?>
                        <td>
							<?php echo $data['title']; ?>
							<?php echo $data['url']; ?>
							<?php echo $data['email']; ?>
							<?php echo $data['phone']; ?>
                        </td>
						<?php $colspan++; ?>
					<?php endif; ?>
					<?php if ($data['logo']): ?>
                        <td><?php echo $data['logo']; ?></td>
						<?php $colspan++; ?>
					<?php endif; ?>
					<?php if ($data['print_date'] || $data['transmission_date'] || $data['order_id_html']): ?>
                        <td class="shop-meta-dates">
							<?php echo $data['print_date']; ?>
							<?php echo $data['transmission_date']; ?>
							<?php echo $data['order_id_html']; ?>
							<?php $colspan++; ?>
                        </td>
					<?php endif; ?>
                </tr>
                </tbody>
				<?php if ($data['sender'] || $data['postcode'] || $data['economical'] || $data['reg']): ?>
                    <tfoot>
                    <tr>
                        <td colspan="<?php echo $colspan; ?>">
							<?php echo $data['sender']; ?>
							<?php echo $data['postcode']; ?>
							<?php echo $data['economical']; ?>
							<?php echo $data['reg']; ?>
                        </td>
                    </tr>
                    </tfoot>
				<?php endif; ?>
            </table>
		<?php endif; ?>
		<?php if ($data['recipient'] || $data['full_name'] || $data['r_postcode'] || $data['r_phone'] ||
         $data['r_email'] || $data['order_date'] || $data['pay_method'] ||
            $data['trans_id'] || $data['national_id'] || $data['shipping'] || $data['user_meta'] ||
             $data['order_meta']): ?>
            <div class="customer-detail">
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
            </div>
		<?php endif; ?>
		<?php if ($data['products_table']): ?>
			<?php echo $data['products_table']; ?>
		<?php endif; ?>
		<?php if ($data['total_table'] || $data['customer_note'] || $data['order_note'] || $data['barcode']): ?>
            <div class="fc-invoice-footer-row">
				<?php if ($data['total_table']): ?>
                    <div class="fc-invoice-footer-total">
						<?php echo $data['total_table']; ?>
                    </div>
				<?php endif; ?>
				<?php if ($data['customer_note'] || $data['order_note']): ?>
                    <div class="fc-invoice-footer-note">
						<?php if ($data['customer_note']): ?>
							<?php echo $data['customer_note']; ?>
						<?php endif; ?>
						<?php if ($data['order_note']): ?>
							<?php echo $data['order_note']; ?>
						<?php endif; ?>
                    </div>
				<?php endif; ?>
				<?php if ($data['barcode']): ?>
                    <div class="fc-invoice-footer-barcode">
						<?php echo $data['barcode']; ?>
                    </div>
				<?php endif; ?>
            </div>
		<?php endif; ?>
		<?php echo $data['watermark']; ?>
    </div>
<?php
include(FCI_VIEW_PATH . 'footer.php');
