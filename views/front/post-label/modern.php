<?php
if (!defined('ABSPATH')) {
    exit(__('No Access!', 'fci'));
}

include(FCI_VIEW_PATH . 'header.php');

$has_recipient = $data['recipient'] || $data['full_name'] || $data['r_postcode'] || $data['r_phone']
    || $data['national_id'] || $data['user_meta'];
$has_sender = $data['logo'] || $data['title'] || $data['sender'] || $data['postcode']
    || $data['economical'] || $data['reg'] || $data['phone'];
$has_note = !empty($data['customer_note']);
$has_footer = $data['shipping'] || $data['order_id_html'] || $data['print_date'];
?>
<div class="modern container fc-post-label">
    <div class="inner fc-post-label-layout">
        <?php if ($has_recipient) : ?>
            <section class="fc-label-party fc-label-recipient">
                <h3 class="fc-label-party-title"><?php esc_html_e('گیرنده', 'factorchi'); ?></h3>
                <div class="fc-label-party-body">
                    <?php echo $data['full_name']; ?>
                    <?php echo $data['recipient']; ?>
                    <?php echo $data['r_postcode']; ?>
                    <?php echo $data['r_phone']; ?>
                    <?php echo $data['national_id']; ?>
                    <?php echo $data['user_meta']; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($has_note) : ?>
            <section class="fc-label-note">
                <?php echo $data['customer_note']; ?>
            </section>
        <?php endif; ?>

        <?php if ($has_sender) : ?>
            <section class="fc-label-party fc-label-sender">
                <h3 class="fc-label-party-title"><?php esc_html_e('فرستنده', 'factorchi'); ?></h3>
                <div class="fc-label-party-body">
                    <?php echo $data['title']; ?>
                    <?php echo $data['sender']; ?>
                    <?php echo $data['postcode']; ?>
                    <?php echo $data['economical']; ?>
                    <?php echo $data['reg']; ?>
                    <?php echo $data['phone']; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if (!empty($data['products_list'])) : ?>
            <section class="fc-label-products">
                <h4 class="fc-label-products-title"><?php esc_html_e('محصولات', 'factorchi'); ?></h4>
                <div class="fc-label-products-list">
                    <?php echo $data['products_list']; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($has_footer) : ?>
            <footer class="fc-label-footer">
                <?php echo $data['shipping']; ?>
                <?php echo $data['order_id_html']; ?>
                <?php echo $data['print_date']; ?>
            </footer>
        <?php endif; ?>
    </div>
</div>
<?php
include(FCI_VIEW_PATH . 'footer.php');
