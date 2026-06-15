<?php

if (!defined('ABSPATH')){
    exit(__( 'No Access!', 'fci' ));
}

include(FCI_VIEW_PATH . 'header.php');

?>

    <div class="view-2 container">
        <table class="fci-border-table fci-fix-table">
            <tbody>
            <?php if ( $data['title'] ||  $data['sender'] || $data['postcode'] ||
                $data['economical'] || $data['reg'] ||
                $data['phone'] ||  $data['email'] || $data['url'] ||
                $data['print_date'] || $data['transmission_date']): ?>
                <tr>
                    <td class="shop-detail" colspan="2">
                        <?php echo $data['title']; ?>
                        <?php echo $data['sender']; ?>
                        <?php echo $data['postcode']; ?>
                        <?php echo $data['economical']; ?>
                        <?php echo $data['reg']; ?>
                        <?php echo  $data['phone']; ?>
                        <?php echo $data['email']; ?>
                        <?php echo $data['url']; ?>
                        <?php echo $data['print_date']; ?>
                        <?php echo $data['transmission_date']; ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php if ( $data['recipient'] || $data['full_name']): ?>
                <tr>
                    <td><?php echo $data['recipient']; ?></td>
                    <td><?php echo $data['full_name']; ?></td>
                </tr>
            <?php endif; ?>
            <?php if ( $data['r_postcode'] || $data['r_phone']): ?>
                <tr>
                    <td><?php echo $data['r_postcode']; ?></td>
                    <td><?php echo $data['r_phone']; ?></td>
                </tr>
            <?php endif; ?>
            <?php if ( $data['r_email'] ||  $data['order_date']): ?>
                <tr>
                    <td><?php echo  $data['r_email']; ?></td>
                    <td><?php echo $data['order_date']; ?></td>
                </tr>
            <?php endif; ?>
            <?php if ( $data['trans_id'] || $data['national_id']): ?>
                <tr>
                    <td><?php echo $data['trans_id']; ?></td>
                    <td><?php echo $data['national_id']; ?></td>
                </tr>
            <?php endif; ?>
            <?php if ( $data['user_meta'] || $data['shipping']): ?>
                <tr>
                    <td><?php echo $data['user_meta']; ?></td>
                    <td><?php echo $data['shipping']; ?></td>
                </tr>
            <?php endif; ?>
            <?php if ( $data['pay_method'] ||  $data['order_meta']): ?>
                <tr>
                    <td><?php echo $data['pay_method']; ?></td>
                    <td><?php echo  $data['order_meta']; ?></td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        <?php echo   $data['barcode']; ?>
        <?php echo  $data['order_id_html']; ?>
    </div>
<?php
include(FCI_VIEW_PATH . 'footer.php');