<?php
if (!defined('ABSPATH')) {
    exit(__('No Access!', 'fci'));
}

include(FCI_VIEW_PATH . 'header.php');
$colspan = 0;

?>
<div class="view-1 container">
    <div class="inner">
        <table class="fci-border-table fci-fix-table">

            <?php if (get_fci_settings('tapin-status')): ?>
                <?php echo $data['postbarcode'] ?>
            <?php endif; ?>

            <tbody>
                <tr>

                    <?php if (
                        $data['logo'] || $data['title'] || $data['sender'] || $data['postcode'] ||
                        $data['economical'] || $data['reg'] || $data['phone']
                    ): ?>
                        <td>
                            <h3 class="fc-label-party-title"><?php esc_html_e('فرستنده', 'factorchi'); ?></h3>
                            <div class="fc-label-party-body">
                                <!-- <//?php echo $data['logo']; ?> -->
                                <?php echo $data['title']; ?>
                                <?php echo $data['sender']; ?>
                                <?php echo $data['postcode']; ?>
                                <?php echo $data['economical']; ?>
                                <?php echo $data['reg']; ?>
                                <?php echo $data['phone']; ?>
                            </div>
                        </td>
                        <?php $colspan++ ?>
                    <?php endif; ?>

                    <?php if (
                        $data['recipient'] || $data['full_name'] || $data['r_postcode'] || $data['r_phone'] ||
                        $data['national_id'] || $data['user_meta'] ||
                        $data['customer_note'] || $data['barcode']
                    ): ?>
                        <td>
                            <h3 class="fc-label-party-title"><?php esc_html_e('گیرنده', 'factorchi'); ?></h3>
                            <div class="fc-label-party-body">
                                <?php echo $data['recipient']; ?>
                                <?php echo $data['full_name']; ?>
                                <?php echo $data['r_postcode']; ?>
                                <?php echo $data['r_phone']; ?>
                                <?php echo $data['national_id']; ?>
                                <?php echo $data['user_meta']; ?>
                                <?php echo $data['customer_note']; ?>
                                <?php echo $data['barcode']; ?>
                            </div>
                        </td>
                        <?php $colspan++ ?>
                    <?php endif; ?>
                </tr>
                <?php if ($data['products_list']) : ?>
                    <tr>
                        <td colspan="2" class="fc-label-products-cell">
                            <div class="fc-label-products">
                                <h4 class="fc-label-products-title"><?php esc_html_e('محصولات', 'factorchi'); ?></h4>
                                <div class="fc-label-products-list">
                                    <?php echo $data['products_list']; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <?php if ($data['shipping'] || $data['order_id_html'] || $data['print_date']): ?>
                <tfoot>
                    <tr>
                        <td colspan="2">
                            <div>
                                <?php echo  $data['shipping']; ?>
                                <?php echo $data['order_id_html']; ?>
                                <?php echo $data['print_date']; ?>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>
<?php
include(FCI_VIEW_PATH . 'footer.php');
