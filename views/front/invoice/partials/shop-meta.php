<?php
/**
 * Shop meta (footer of shop-detail table).
 *
 * @var array<string, mixed> $data
 * @var int                  $colspan
 * @var bool                 $compact_party_texts
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!($data['sender'] || $data['url'] || $data['phone'] || $data['postcode'] || $data['economical'] || $data['reg'])) {
    return;
}

$strlen = static function (string $text): int {
    $text = trim(wp_strip_all_tags($text));
    return function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);
};

$title_len     = $strlen((string) ($data['title'] ?? ''));
$shop_addr_len = $strlen((string) ($data['sender'] ?? ''));
$threshold     = $title_len > 0 ? $title_len : 55;
$shop_meta_long = $shop_addr_len > $threshold;
?>
<tfoot>
	<tr>
		<td colspan="<?php echo (int) $colspan; ?>">
			<?php if ($compact_party_texts) : ?>
				<?php if ($data['sender'] || $data['url'] || $data['phone'] || $data['postcode']) : ?>
					<div class="fc-shop-meta-line<?php echo $shop_meta_long ? ' is-long' : ' is-short'; ?>">
						<?php echo $data['sender']; ?>
						<?php if ($data['url'] || $data['phone'] || $data['postcode']) : ?>
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
			<?php else : ?>
				<div class="fc-shop-meta-grid">
					<?php echo $data['sender']; ?>
					<?php echo $data['url']; ?>
					<?php echo $data['phone']; ?>
					<?php echo $data['postcode']; ?>
					<?php echo $data['economical']; ?>
					<?php echo $data['reg']; ?>
				</div>
			<?php endif; ?>
		</td>
	</tr>
</tfoot>
