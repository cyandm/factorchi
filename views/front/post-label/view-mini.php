<?php
if (!defined('ABSPATH')) {
    exit(__('No Access!', 'fci'));
}

$type = $this->type;
$labels = new FCI_Labels();
$data = FCI_View_Render::header($this->get_order_id(), $type, $this->get_font_family(), false, true);


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

        a {
            text-decoration: none;
        }

        @media print {
            .print {
                display: none;
            }
        }

        .label-container {
            display: flex;
            height: max-content;
            gap: 5px;
            flex-direction: column;
        }

        .label-col {
            width: 100%;
            height: 100%;
        }

        .label-footer {
            gap: 20px;
        }

        @media screen and (max-width: 400px) {
            .label-col {
                width: 100%;
                height: auto;
            }

            .label-footer {
                gap: 5px;
            }


            .label-container {
                display: block;

            }

        }
    </style>
</head>

<body class="<?php echo $this->append_body_class(); ?>">

    <div style="<?php echo $margin ? 'margin: ' . $margin . 'px' : '' ?>">


        <?php if ($data['title'] || $data['logo'] || $data['order_id_html'] || $data['print_date']) : ?>

            <div style="margin-bottom: 5px;">
                <div style="text-align: center; margin-bottom: 5px;">
                    <?php echo $data['logo']; ?>
                </div>
                <p style="font-weight: 900; font-size: 18px; text-align:center; margin-bottom: 5px;"><?php echo $data['title'] ?></p>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center;">
                        <span style="font-size: 9px; text-align:center; margin-left: 3px;">شناسه سفارش:</span>
                        <span style="font-weight: 900; font-size: 15px; text-align:center;"><?php echo $data['order_id_html'] ?></span>
                    </div>
                    <div>
                        <span style="font-size: 10px;"><?php echo $data['print_date'] ?></span>
                    </div>
                </div>
            </div>

            <div class="label-container">
                <div class="label-col">

                    <?php if (
                        $data['logo'] || $data['title'] || $data['sender'] || $data['postcode'] ||
                        $data['economical'] || $data['reg'] || $data['phone'] || $data['email'] || $data['url']
                    ) : ?>
                        <div style="border: 1px dashed #6b6b6b; padding: 2px 5px; border-radius: 8px; font-size: 12px; margin-bottom: 16px;height: 100%">

                            <p style="font-weight: bold; font-size: 14px; margin-bottom: 5px;">فرستنده: </p>

                            <?php if ($data['sender']) : ?>
                                <p style="margin-bottom: 3px;">آدرس: <?php echo $data['sender']; ?></p>
                            <?php endif; ?>

                            <?php if ($data['postcode']) : ?>
                                <p style="margin-bottom: 3px;">کدپستی: <?php echo  $data['postcode']; ?></p>
                            <?php endif; ?>

                            <?php if ($data['economical']) : ?>
                                <p style="margin-bottom: 3px;">شماره اقتصادی: <?php echo  $data['economical']; ?></p>
                            <?php endif; ?>

                            <?php if ($data['reg']) : ?>
                                <p style="margin-bottom: 3px;">شماره ثبت: <?php echo $data['reg']; ?></p>
                            <?php endif; ?>

                            <?php if ($data['phone']) : ?>
                                <p style="margin-bottom: 3px;">تلفن: <?php echo  $data['phone']; ?></p>
                            <?php endif; ?>

                            <?php if ($data['email']) : ?>
                                <p style="margin-bottom: 3px;">ایمیل: <?php echo $data['email']; ?></p>
                            <?php endif; ?>

                            <?php if ($data['url']) : ?>
                                <p style="margin-bottom: 3px;"><?php echo  $data['url']; ?></p>
                            <?php endif; ?>

                        </div>
                        <?php $colspan++ ?>
                    <?php endif; ?>
                </div>
                <div class="label-col">
                    <?php if (
                        $data['recipient'] || $data['full_name'] || $data['r_postcode'] || $data['r_phone'] ||
                        $data['r_email'] || $data['national_id'] || $data['user_meta'] ||
                        $data['customer_note'] || $data['barcode']
                    ) : ?>
                        <div style="border: 1px dashed #6b6b6b; padding: 2px 5px; border-radius: 8px; font-size: 12px; margin-bottom: 16px; height: 100%">


                            <p style="font-weight: bold; font-size: 14px; margin-bottom: 5px;">گیرنده: </p>

                            <?php if ($data['recipient']) : ?>
                                <p style="margin-bottom: 3px;">آدرس: <?php echo $data['recipient']; ?></p>
                            <?php endif; ?>

                            <?php if ($data['full_name']) : ?>
                                <p style="margin-bottom: 3px;">نام: <?php echo $data['full_name']; ?></p>
                            <?php endif; ?>

                            <?php if ($data['r_postcode']) : ?>
                                <p style="margin-bottom: 3px;">کدپستی: <?php echo $data['r_postcode']; ?></p>
                            <?php endif; ?>

                            <?php if ($data['r_phone']) : ?>
                                <p style="margin-bottom: 3px;">تلفن: <?php echo  $data['r_phone']; ?></p>
                            <?php endif; ?>

                            <?php if ($data['r_email']) : ?>
                                <p style="margin-bottom: 3px;">ایمیل: <?php echo   $data['r_email']; ?></p>
                            <?php endif; ?>

                            <?php if ($data['national_id']) : ?>
                                <p style="margin-bottom: 3px;">کدملی: <?php echo  $data['national_id']; ?></p>
                            <?php endif; ?>

                            <?php if ($data['user_meta']) : ?>
                                <p style="margin-bottom: 3px;"><?php echo $data['user_meta']; ?></p>
                            <?php endif; ?>

                            <?php if ($data['customer_note']) : ?>
                                <p style="margin-bottom: 3px;">یادداشت سفارش: <?php echo $data['customer_note']; ?></p>
                            <?php endif; ?>

                            <?php if ($data['barcode']) : ?>
                                <p style="margin-bottom: 3px;"><?php echo $data['barcode']; ?></p>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>

                </div>

            </div>

            <?php if ($data['shipping']) : ?>

                <div class="label-footer" style="display:flex; align-items: center; flex-wrap: wrap; border: 1px dashed #6b6b6b; padding: 8px 5px; border-radius: 8px; font-size: 12px; margin-bottom: 16px; height: 100%">

                    <p style="margin-bottom: 3px;">روش حمل و نقل: <?php echo $data['shipping']; ?></p>

                </div>

            <?php endif; ?>


        <?php endif; ?>

    </div>
    <?php

    include(FCI_VIEW_PATH . 'footer.php');
