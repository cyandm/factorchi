<?php
if (!defined('ABSPATH')){
    exit(__( 'No Access!', 'fci' ));
}

$labels = new FCI_Labels();
$sections = get_fci_settings('sections');

$wc_countries = get_option('woocommerce_specific_allowed_countries');
$statuses = wc_get_order_statuses();
$state = $_GET['state'] ?? null;
$status = $_GET['status'] ?? null;
$start = $_GET['start-date'] ?? null;
$end = $_GET['end-date'] ?? null;
$fci_orders = new FCI_Orders_Table($state, $status, $start, $end , $labels , $sections);

$view = get_fci_settings('orders-view');

$items_quantity = 0;


// get orders
$args = array(
    'limit' => -1,
    'orderby' => 'date',
    'order' => 'DESC',
    'return' => 'ids',
    'type' => 'shop_order',
);
if ($state && $state != 'all') {
    $args['billing_state'] = $state;
}
if ($status && $status != 'all') {
    $args['status'] = $status;
}
if (get_bloginfo('language') == 'fa-IR' && $start && $end) {
    list($date_start_y, $date_start_m, $date_start_d) = explode('-', $start);
    list($date_end_y, $date_end_m, $date_end_d) = explode('-', $end);

    $date_start_g = FCI_Date_Convert::jalali_to_gregorian($date_start_y, $date_start_m, $date_start_d);
    $date_start_g = implode('-', $date_start_g);
    $date_end_g = FCI_Date_Convert::jalali_to_gregorian($date_end_y, $date_end_m, $date_end_d);
    $date_end_g = implode('-', $date_end_g);
    $date_start_g = strtotime($date_start_g);
    $date_end_g = strtotime($date_end_g);
    $args['date_created'] = $date_start_g . '...' . $date_end_g;
}

$query = new WC_Order_Query($args);
$orders = $query->get_orders();
?>


<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>

    <title><?php echo $labels->get_label('orders') ?></title>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="<?php echo FCI_CSS_URL; ?>persian-datepicker.css">
    <link rel="stylesheet" href="<?php echo FCI_CSS_URL; ?>views/<?php echo $view == 'view-1' ? 'orders-1' : 'orders-2' ?>.css">
    <?php echo $this->append_styles(); ?>
</head>
