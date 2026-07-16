<?php
if (!defined('ABSPATH')) {
	exit(__('No Access!', 'fci'));
}

include(FCI_VIEW_PATH . 'header.php');

$show_barcode_top               = factorchi_get_setting('show_barcode_top', 'no') === 'yes';
$show_barcode_top_text          = factorchi_get_setting('show_barcode_top_text', 'yes') === 'yes';
$show_barcode_under_title       = factorchi_get_setting('show_barcode_under_title', 'no') === 'yes';
$show_barcode_under_title_text  = factorchi_get_setting('show_barcode_under_title_text', 'no') === 'yes';
$show_barcode_bottom            = factorchi_get_setting('show_barcode_bottom', 'yes') === 'yes';
$show_barcode_bottom_text       = factorchi_get_setting('show_barcode_bottom_text', 'yes') === 'yes';
$barcode_top_html               = ($show_barcode_top && !empty($data['shop_barcode_render']))
	? $data['shop_barcode_render']
	: (($show_barcode_top && !empty($data['barcode'])) ? $data['barcode'] : '');
$barcode_under_title_html       = ($show_barcode_under_title && !empty($data['shop_barcode_render']))
	? $data['shop_barcode_render']
	: (($show_barcode_under_title && !empty($data['barcode'])) ? $data['barcode'] : '');
$barcode_bottom_html            = ($show_barcode_bottom && !empty($data['barcode'])) ? $data['barcode'] : '';
$barcode_top_html               = Factorchi_Barcode::with_text_option($barcode_top_html, $show_barcode_top_text);
$barcode_under_title_html       = Factorchi_Barcode::with_text_option($barcode_under_title_html, $show_barcode_under_title_text);
$barcode_bottom_html            = Factorchi_Barcode::with_text_option($barcode_bottom_html, $show_barcode_bottom_text);
$show_logo_cell                 = !empty($data['logo']) || $barcode_top_html !== '';

?>
<div class="classic container">
	<?php if ($type === 'pre-invoice'): ?>
		<div class="fc-document-type"><?php echo esc_html($labels->get_label('pre-invoice')); ?></div>
	<?php endif; ?>
	<?php if (
		$data['title'] || $show_logo_cell || $barcode_under_title_html !== '' || $data['print_date'] ||
		$data['transmission_date'] || $data['url'] || $data['email'] || $data['phone'] || $data['order_id_html'] ||
		$data['sender'] || $data['postcode'] || $data['economical'] || $data['reg']
	): ?>
		<table class="shop-detail fci-res-table fci-form-table">
			<tbody>
				<tr>
					<?php $colspan = 0; ?>
					<?php if ($data['title'] || $data['email'] || $barcode_under_title_html !== ''): ?>
						<td>
							<?php echo $data['title']; ?>
							<?php if ($barcode_under_title_html !== ''): ?>
								<div class="fc-shop-barcode-title">
									<?php echo $barcode_under_title_html; ?>
								</div>
							<?php endif; ?>
							<?php echo $data['email']; ?>
						</td>
						<?php $colspan++; ?>
					<?php endif; ?>
					<?php if ($show_logo_cell): ?>
						<td class="fc-shop-logo-cell">
							<div class="fc-shop-logo-row">
								<?php if ($data['logo']): ?>
									<?php echo $data['logo']; ?>
								<?php endif; ?>
								<?php if ($barcode_top_html !== ''): ?>
									<div class="fc-shop-barcode-top">
										<?php echo $barcode_top_html; ?>
									</div>
								<?php endif; ?>
							</div>
						</td>
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
			<?php
			$shop_addr_len = function_exists('mb_strlen')
				? mb_strlen(trim(wp_strip_all_tags((string) ($data['sender'] ?? ''))), 'UTF-8')
				: strlen(trim(wp_strip_all_tags((string) ($data['sender'] ?? ''))));
			$shop_meta_long = $shop_addr_len > 55;
			?>
			<?php if ($data['sender'] || $data['url'] || $data['phone'] || $data['postcode'] || $data['economical'] || $data['reg']): ?>
				<tfoot>
					<tr>
						<td colspan="<?php echo $colspan; ?>">
							<?php if ($data['sender'] || $data['url'] || $data['phone'] || $data['postcode']): ?>
								<div class="fc-shop-meta-line<?php echo $shop_meta_long ? ' is-long' : ' is-short'; ?>">
									<?php echo $data['sender']; ?>
									<?php if ($data['url'] || $data['phone'] || $data['postcode']): ?>
										<div class="fc-shop-contacts">
											<?php echo $data['url']; ?>
											<?php echo $data['phone']; ?>
											<?php echo $data['postcode']; ?>
										</div>
									<?php endif; ?>
								</div>
							<?php endif; ?>
							<?php echo $data['economical']; ?>
							<?php echo $data['reg']; ?>
						</td>
					</tr>
				</tfoot>
			<?php endif; ?>
		</table>
	<?php endif; ?>
	<?php if (
		$data['recipient'] || $data['full_name'] || $data['r_postcode'] || $data['r_phone'] ||
		$data['r_email'] || $data['order_date'] || $data['pay_method'] ||
		$data['trans_id'] || $data['national_id'] || $data['shipping'] || $data['user_meta'] ||
		$data['order_meta']
	): ?>
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
	<?php if ($data['total_table'] || $data['customer_note'] || $data['order_note'] || $barcode_bottom_html !== ''): ?>
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
			<?php if ($barcode_bottom_html !== ''): ?>
				<div class="fc-invoice-footer-barcode">
					<?php echo $barcode_bottom_html; ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php if (!empty($data['tearoff'])): ?>
		<?php echo $data['tearoff']; ?>
	<?php endif; ?>
	<?php echo $data['watermark']; ?>
</div>
<?php
include(FCI_VIEW_PATH . 'footer.php');
