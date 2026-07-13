<?php

if (!defined('ABSPATH')) {
    exit;
}

class Factorchi_Labels
{
    /** @var array<string, string> */
    private array $labels;

    public function __construct()
    {
        $this->labels = [
            'invoice'       => __('فاکتور', 'factorchi'),
            'pre-invoice'   => __('پیش‌فاکتور', 'factorchi'),
            'post-label'    => __('برچسب پستی', 'factorchi'),
            'shop-label'    => __('برچسب فروشگاه', 'factorchi'),
            'customer-label'=> __('برچسب مشتری', 'factorchi'),
            'product-label' => __('برچسب محصول', 'factorchi'),
            'mini-label'    => __('برچسب مینی', 'factorchi'),
            'row'           => __('ردیف', 'factorchi'),
            'order'         => __('سفارش', 'factorchi'),
            'address'       => __('آدرس:', 'factorchi'),
            'note'          => __('یادداشت', 'factorchi'),
            'price'         => __('مبلغ', 'factorchi'),
            'quantity'      => __('تعداد', 'factorchi'),
            'provider-price'=> __('قیمت تامین‌کننده', 'factorchi'),
            'order_id'      => __('شناسه سفارش', 'factorchi'),
            'order_date'    => __('تاریخ سفارش', 'factorchi'),
            'customer'      => __('مشتری', 'factorchi'),
            'products'      => __('محصولات', 'factorchi'),
            'total'         => __('جمع کل', 'factorchi'),
            'subtotal'      => __('جمع جزء', 'factorchi'),
            'shipping'      => __('هزینه ارسال', 'factorchi'),
            'discount'      => __('تخفیف', 'factorchi'),
            'tax'           => __('مالیات', 'factorchi'),
            'payment'       => __('روش پرداخت', 'factorchi'),
            'print'         => __('چاپ', 'factorchi'),
            'send'          => __('ارسال', 'factorchi'),
        ];
    }

    public function get_label(string $key): string
    {
        return $this->labels[$key] ?? $key;
    }
}
