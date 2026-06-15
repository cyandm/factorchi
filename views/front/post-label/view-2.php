<?php
if (!defined('ABSPATH')){
    exit(__( 'No Access!', 'fci' ));
}

include(FCI_VIEW_PATH . 'header.php');
?>
    <div class="view-2 container">
        <div class="inner">
            <table class="fci-border-table fci-fix-table">
                <?php if(get_fci_settings('tapin-status')): ?>
                    <?php echo $data['postbarcode']?>
                <?php endif; ?>

                <tbody>
                <tr>
                    <td>
                        <table class="stamp-placeholder">
                            <tr>

                                <td><?php esc_html_e($labels->get_label('stamp-placeholder')); ?></td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <?php echo $data['logo']; ?>
                        <?php echo $data['title']; ?>
                        <?php echo $data['sender'] ; ?>
                        <?php echo  $data['postcode']; ?>
                        <?php echo  $data['economical']; ?>
                        <?php echo $data['reg']; ?>
                        <?php echo  $data['phone']; ?>
                        <?php echo $data['email']; ?>
                        <?php echo  $data['url']; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php echo $data['recipient']; ?>
                        <?php echo $data['full_name']; ?>
                        <?php echo $data['r_postcode']; ?>
                        <?php echo  $data['r_phone']; ?>
                        <?php echo   $data['r_email']; ?>
                        <?php echo  $data['national_id']; ?>
                        <?php echo $data['user_meta']; ?>
                        <?php echo $data['customer_note']; ?>
                        <?php echo $data['barcode']; ?>
                    </td>
                    <td>
                        <div class="operations"><?php esc_html_e($labels->get_label('operations-location')); ?></div>
                    </td>
                </tr>
                </tbody>
                <?php if ( $data['shipping'] || $data['order_id_html'] || $data['print_date']): ?>
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