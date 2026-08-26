<?php

if (!defined('ABSPATH')) {
    exit;
}

/** @var array<string, mixed> $settings */
/** @var string $tab */

$partials = FACTORCHI_DIR . 'admin/views/partials/';

$nav_groups = [
    __('فروشگاه', 'factorchi') => [
        'general' => ['label' => __('اطلاعات عمومی', 'factorchi'), 'icon' => 'dashicons-store'],
    ],
    __('نمایش', 'factorchi') => [
        'templates' => [
            'label'    => __('قالب‌ها', 'factorchi'),
            'icon'     => 'dashicons-layout',
            'children' => [
                'select'  => __('انتخاب قالب', 'factorchi'),
                'style'   => __('استایل', 'factorchi'),
                'content' => __('محتوا', 'factorchi'),
            ],
        ],
        'access' => ['label' => __('دسترسی', 'factorchi'), 'icon' => 'dashicons-lock'],
    ],
    __('ارسال', 'factorchi') => [
        'notify' => ['label' => __('کانال‌ها', 'factorchi'), 'icon' => 'dashicons-email-alt'],
        'sms'    => ['label' => __('SMS', 'factorchi'), 'icon' => 'dashicons-smartphone'],
        'bots'   => ['label' => __('تلگرام / بله', 'factorchi'), 'icon' => 'dashicons-format-chat'],
        'auto'   => ['label' => __('خودکار', 'factorchi'), 'icon' => 'dashicons-update'],
        'survey' => ['label' => __('اطلاع رسانی (ارسال فاکتور)', 'factorchi'), 'icon' => 'dashicons-megaphone'],
    ],
    __('سایر', 'factorchi') => [
        'tapin'  => ['label' => __('تاپین', 'factorchi'), 'icon' => 'dashicons-tag'],
    ],
];

$tab_titles = [
    'general'   => __('تنظیمات عمومی فروشگاه', 'factorchi'),
    'templates' => __('قالب‌های فاکتور و برچسب', 'factorchi'),
    'access'    => __('دسترسی و نمایش فرانت‌اند', 'factorchi'),
    'notify'    => __('کانال‌های ارسال', 'factorchi'),
    'sms'       => __('تنظیمات SMS', 'factorchi'),
    'bots'      => __('ربات تلگرام و بله', 'factorchi'),
    'auto'      => __('ارسال خودکار', 'factorchi'),
    'survey'    => __('اطلاع رسانی (ارسال فاکتور)', 'factorchi'),
    'tapin'     => __('یکپارچگی تاپین', 'factorchi'),
];

$templates_section = isset($_GET['section']) ? sanitize_key(wp_unslash($_GET['section'])) : 'select';
if (!in_array($templates_section, ['select', 'style', 'content'], true)) {
    $templates_section = 'select';
}

$section_titles = [
    'select'  => __('انتخاب قالب', 'factorchi'),
    'style'   => __('استایل', 'factorchi'),
    'content' => __('محتوا', 'factorchi'),
];

$active_title = $tab_titles[$tab] ?? $tab_titles['general'];
if ($tab === 'templates') {
    $active_title = ($tab_titles['templates'] ?? '') . ' — ' . ($section_titles[$templates_section] ?? $section_titles['select']);
}

$base_url = admin_url('admin.php?page=factorchi');
?>
<div class="wrap factorchi-admin">
    <div class="fc-header">
        <h1><?php esc_html_e('فاکتورچی', 'factorchi'); ?></h1>
        <p><?php esc_html_e('مدیریت فاکتور، قالب‌ها، ارسال و یکپارچگی ووکامرس', 'factorchi'); ?></p>
    </div>
    <hr class="wp-header-end" />

    <?php if (!empty($_GET['updated'])) : ?>
        <div class="notice notice-success is-dismissible fc-notice">
            <p><?php esc_html_e('تنظیمات ذخیره شد.', 'factorchi'); ?></p>
        </div>
    <?php endif; ?>

    <div class="fc-layout">
        <aside class="fc-sidebar">
            <?php foreach ($nav_groups as $group_title => $items) : ?>
                <div class="fc-nav-group">
                    <p class="fc-nav-group-title"><?php echo esc_html($group_title); ?></p>
                    <?php foreach ($items as $slug => $item) : ?>
                        <?php
                        $has_children = !empty($item['children']) && is_array($item['children']);
                        $is_open      = $has_children && $tab === $slug;
                        $parent_url   = add_query_arg('tab', $slug, $base_url);
                        if ($slug === 'templates') {
                            $parent_url = add_query_arg('section', 'select', $parent_url);
                        }
                        ?>
                        <div class="fc-nav-block <?php echo $is_open ? 'is-open' : ''; ?>">
                            <a href="<?php echo esc_url($parent_url); ?>"
                               class="fc-nav-item <?php echo $tab === $slug ? 'is-active' : ''; ?>">
                                <span class="dashicons <?php echo esc_attr($item['icon']); ?>"></span>
                                <?php echo esc_html($item['label']); ?>
                            </a>
                            <?php if ($has_children) : ?>
                                <div class="fc-nav-children" <?php echo $is_open ? '' : 'hidden'; ?>>
                                    <?php foreach ($item['children'] as $child_slug => $child_label) : ?>
                                        <?php
                                        $child_url = add_query_arg(
                                            [
                                                'tab'     => $slug,
                                                'section' => $child_slug,
                                            ],
                                            $base_url
                                        );
                                        $child_active = $tab === $slug && $templates_section === $child_slug;
                                        ?>
                                        <a href="<?php echo esc_url($child_url); ?>"
                                           class="fc-nav-child <?php echo $child_active ? 'is-active' : ''; ?>">
                                            <?php echo esc_html($child_label); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </aside>

        <main class="fc-main">
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('factorchi_save_settings'); ?>
                <input type="hidden" name="action" value="factorchi_save_settings" />
                <input type="hidden" name="factorchi_tab" value="<?php echo esc_attr($tab); ?>" />

                <div class="fc-card">
                    <h2 class="fc-card-title"><?php echo esc_html($active_title); ?></h2>

                    <?php
                    $view_file = FACTORCHI_DIR . 'admin/views/tabs/' . $tab . '.php';
                    if (file_exists($view_file)) {
                        include $view_file;
                    } else {
                        include FACTORCHI_DIR . 'admin/views/tabs/general.php';
                    }
                    ?>
                </div>

                <div class="fc-save-bar">
                    <button type="submit" class="button button-primary button-large">
                        <?php esc_html_e('ذخیره تنظیمات', 'factorchi'); ?>
                    </button>
                    <span class="fc-autosave-status" aria-live="polite"></span>
                    <span class="fc-autosave-hint"><?php esc_html_e('سوئیچ‌ها خودکار ذخیره می‌شوند', 'factorchi'); ?></span>
                </div>
            </form>
        </main>
    </div>
</div>
