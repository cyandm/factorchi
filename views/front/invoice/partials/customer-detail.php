<?php
/**
 * Customer / buyer detail block.
 *
 * @var array<string, mixed> $data
 * @var bool                 $compact_party_texts
 */

if (!defined('ABSPATH')) {
    exit;
}

$has_customer = $data['recipient'] || $data['full_name'] || $data['r_postcode'] || $data['r_phone']
    || $data['r_email'] || $data['order_date'] || $data['pay_method']
    || $data['trans_id'] || $data['national_id'] || $data['shipping'] || $data['customer_note_line']
    || $data['user_meta'] || $data['order_meta'];

if (!$has_customer) {
    return;
}

$strlen = static function (string $text): int {
    $text = trim(wp_strip_all_tags($text));
    return function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);
};

$title_len = $strlen((string) ($data['title'] ?? ''));
$addr_len  = $strlen((string) ($data['recipient'] ?? ''));
$threshold = $title_len > 0 ? $title_len : 55;
$meta_long = $addr_len > $threshold;

$has_fields = $data['full_name'] || $data['r_phone'] || $data['r_postcode'] || $data['r_email']
    || $data['order_date'] || $data['pay_method'] || $data['trans_id'] || $data['national_id']
    || $data['shipping'] || $data['customer_note_line'] || $data['user_meta'] || $data['order_meta'];
?>
<?php if ($compact_party_texts) : ?>
	<div class="customer-detail fc-party-compact">
		<div class="fc-customer-meta-line<?php echo $meta_long ? ' is-long' : ' is-short'; ?>">
			<?php echo $data['recipient']; ?>
			<?php if ($has_fields) : ?>
				<div class="fc-customer-contacts">
					<?php echo $data['full_name']; ?>
					<?php echo $data['r_phone']; ?>
					<?php echo $data['r_postcode']; ?>
					<?php echo $data['r_email']; ?>
					<?php echo $data['order_date']; ?>
					<?php echo $data['pay_method']; ?>
					<?php echo $data['trans_id']; ?>
					<?php echo $data['national_id']; ?>
					<?php echo $data['shipping']; ?>
					<?php echo $data['customer_note_line']; ?>
					<?php echo $data['user_meta']; ?>
					<?php echo $data['order_meta']; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php else : ?>
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
		<?php echo $data['customer_note_line']; ?>
		<?php echo $data['user_meta']; ?>
		<?php echo $data['order_meta']; ?>
	</div>
<?php endif; ?>
