<?php
if (!defined('ABSPATH')) {
    exit(__('No Access!', 'fci'));
}


$labels = new FCI_Labels();
$render = new FCI_View_Render();

$id = $this->get_order_id();
$type = $this->type;
$font = $this->get_font_family();

$shop = new FCI_Shop($id, $type);
$customer = new FCI_Customer_Detail($id, $type);
$total = new FCI_Total_Table($id, $type);
$products = new FCI_Products_Table($id, $type);

$pdf = false;

$data = [
    'font_family' => $font,
    'title' => $shop->title_holder(true),
    'url' => $shop->url_holder(true),
    'email' => $shop->email_holder(true),
    'phone' => $shop->phone_holder(true),
    'logo' => $shop->logo_holder(true),
    'print_date' => $shop->print_date_holder(),
    'transmission_date' => $shop->transmission_date_holder(),
    'order_id_html' => $shop->order_id_holder(),
    'barcode' => $shop->barcode_holder(),
    'sender' => $shop->address_holder(true),
    'postcode' => $shop->postal_code_holder(true),
    'economical' => $shop->economical_num_holder(true),
    'reg' => $shop->reg_num_holder(true),
    'recipient' => $customer->address_holder(true),
    'full_name' => $customer->full_name_holder(true),
    'r_postcode' => $customer->postal_code_holder(true),
    'r_phone' => $customer->phone_holder(true),
    'r_email' => $customer->email_holder(true),
    'order_date' => $customer->order_date_holder(true),
    'pay_method' => $customer->payment_method_holder(true),
    'trans_id' => $customer->transaction_id_holder(true),
    'national_id' => $customer->national_id_holder(true),
    'shipping' => $customer->shipping_method_holder(true),
    'user_meta' => $customer->user_meta_holder(),
    'order_meta' => $customer->order_meta_holder(),
    'delivery_date' => $customer->delivery_date_holder(),
    'customer_note' => $shop->customer_note_holder(),
    'order_note' => $shop->order_note_holder(true),
    'shop_sign' => $shop->shop_signature_holder(),
    'customer_sign' => $shop->customer_signature_holder(),
    'deliver_date' => $shop->deliver_date_holder(),
    'deliver_time' => $shop->deliver_time_holder(),
    'watermark' => $shop->get_mark_holder(),
    'products_table' => $render->get_products_html($id, $type, $pdf, true),
    'products_list' => $products->render_list_html(),
    'total_table' => $total->render_html(true),
    'postbarcode' => $shop->get_post_barcode($shop->get_order_id()),

    'shop_order_id' => $shop->get_order_id(),
    'shop_barcode_render' => $shop->barcode_holder(2, 70),
];



$margin = get_fci_settings($type . '-margin');

?>


<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <title><?php echo $this->type; ?>(<?php echo $data['shop_order_id']; ?>)</title>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0, user-scalable=no">
    <?php echo $this->append_styles(); ?>


    <style>
        @page {
            size: auto;
            margin: 0;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }

        @media print {

            html,
            body {
                height: 99%;
                page-break-after: avoid;
                page-break-before: avoid;
            }
        }

        p {
            margin: 0;
        }

        .barcode {
            display: flex;
            justify-content: center;
            margin: 10px 0;
        }

        .button {
            background: #ed3819;
            color: #FFF;
            text-align: center;
            cursor: pointer;
            display: inline-block;
            margin: 0 3px;
            padding: 4px 16px !important;
            border-radius: 5px;
        }

        .print {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }

        a{
            text-decoration: none;
        }

        @media print {
            .print {
                display: none;
            }
        }
    </style>
</head>

<body class="<?php echo $this->append_body_class(); ?>">

    <div style="<?php echo $margin ? 'margin: ' . $margin . 'px' : '' ?>">

        <?php if ($data['title'] || $data['logo'] || $data['shop_order_id'] || $data['order_date']) : ?>

            <div style="margin-bottom: 5px;">
                <div style="text-align: center; margin-bottom: 5px;">
                    <?php echo $data['logo']; ?>
                </div>
                <p style="font-weight: 900; font-size: 18px; text-align:center; margin-bottom: 5px;"><?php echo $data['title'] ?></p>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center;">
                        <span style="font-size: 9px; text-align:center; margin-left: 3px;">شناسه سفارش:</span>
                        <span style="font-weight: 900; font-size: 15px; text-align:center;"><?php echo $data['shop_order_id'] ?></span>
                    </div>
                    <div>
                        <span style="font-size: 10px;"><?php echo $data['order_date'] ?></span>
                    </div>
                </div>
            </div>


        <?php endif; ?>

        <?php if ($data['url'] || $data['sender'] || $data['email'] || $data['phone'] || $data['postcode'] || $data['economical'] || $data['reg']) : ?>
            <div style="border: 1px dashed #6b6b6b; padding: 2px 5px; border-radius: 8px; font-size: 12px; margin-bottom: 16px">
                <p style="margin-bottom: 3px;"><?php echo $data['url'] ?></p>
                <p style="margin-bottom: 3px;"><?php echo $data['sender'] ?></p>
                <p style="margin-bottom: 3px;"><?php echo $data['email'] ?></p>
                <p style="margin-bottom: 3px;"><?php echo $data['phone'] ?></p>
                <?php if ($data['postcode']) : ?>
                    <p style="margin-bottom: 3px;">کدپستی: <?php echo $data['postcode'] ?></p>
                <?php endif; ?>
                <?php if ($data['economical']) : ?>
                    <p style="margin-bottom: 3px;">شماره اقتصادی: <?php echo $data['economical'] ?></p>
                <?php endif; ?>
                <?php if ($data['reg']) : ?>
                    <p style="margin-bottom: 3px;">شماره ثبت: <?php echo $data['reg'] ?></p>
                <?php endif; ?>

            </div>
        <?php endif; ?>


        <?php if ($data['products_table']) : ?>
            <?php echo $data['products_table']; ?>
        <?php endif; ?>


        <?php if ($data['total_table']) : ?>
            <?php echo $data['total_table']; ?>
        <?php endif; ?>


        <?php if ($data['recipient'] || $data['full-name'] || $data['r_postcode'] || $data['r_phone'] || $data['r_email'] || $data['r_postcode'] || $data['trans_id'] || $data['national_id'] || $data['shipping']) : ?>
            <div style="border: 1px dashed #6b6b6b; padding: 2px 5px; border-radius: 8px; font-size: 12px; margin-top: 10px;">
                <p style="margin-bottom: 3px;">آدرس: <?php echo $data['recipient'] ?></p>
                <p style="margin-bottom: 3px;">نام: <?php echo $data['full_name'] ?></p>
                <p style="margin-bottom: 3px;">ایمیل: <?php echo $data['r_email'] ?></p>
                <p style="margin-bottom: 3px;">تلفن: <?php echo $data['r_phone'] ?></p>
                <?php if ($data['r_postcode']) : ?>
                    <p style="margin-bottom: 3px;">کدپستی: <?php echo $data['r_postcode'] ?></p>
                <?php endif; ?>
                <?php if ($data['pay_method']) : ?>
                    <p style="margin-bottom: 3px;">تاریخ سفارش: <?php echo $data['pay_method'] ?></p>
                <?php endif; ?>
                <?php if ($data['trans_id']) : ?>
                    <p style="margin-bottom: 3px;">شماره تراکنش: <?php echo $data['trans_id'] ?></p>
                <?php endif; ?>
                <?php if ($data['national_id']) : ?>
                    <p style="margin-bottom: 3px;">کد ملی: <?php echo $data['national_id'] ?></p>
                <?php endif; ?>
                <?php if ($data['shipping']) : ?>
                    <p style="margin-bottom: 3px;">روش ارسال: <?php echo $data['shipping'] ?></p>
                <?php endif; ?>

            </div>
        <?php endif; ?>

        <?php if ($data['order_note']) : ?>
            <p style="font-size: 13px; text-align: center; margin-top:10px;"><?php echo $data['order_note']; ?></p>
        <?php endif; ?>

        <?php echo $data['barcode']; ?>

    </div>

    <?php if (get_fci_settings('use-persian-number') && !$this->get_check_email()) : ?>
        <?php echo FCI_View_Render::footer_js(); ?>
    <?php endif; ?>


    <?php echo FCI_View_Render::footer_action_btn($this->order_id, $this->type, $this->get_check_email()); ?>



    <?php if (get_fci_settings('page-break')) : ?>
        <p style="page-break-before: always;"></p>
    <?php endif; ?>



</body>

</html>