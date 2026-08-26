<?php

if (!defined('ABSPATH')) {
    exit;
}

$partials = FACTORCHI_DIR . 'admin/views/partials/';
$s         = $settings;

$section = isset($_GET['section']) ? sanitize_key(wp_unslash($_GET['section'])) : 'select';
if (!in_array($section, ['select', 'style', 'content'], true)) {
    $section = 'select';
}

$sections = [
    'select'  => __('انتخاب قالب', 'factorchi'),
    'style'   => __('استایل', 'factorchi'),
    'content' => __('محتوا', 'factorchi'),
];

$base_url = add_query_arg(
    [
        'page' => 'factorchi',
        'tab'  => 'templates',
    ],
    admin_url('admin.php')
);
?>
<nav class="fc-subnav" aria-label="<?php esc_attr_e('بخش‌های قالب', 'factorchi'); ?>">
    <?php foreach ($sections as $slug => $label) : ?>
        <a
            href="<?php echo esc_url(add_query_arg('section', $slug, $base_url)); ?>"
            class="fc-subnav-item <?php echo $section === $slug ? 'is-active' : ''; ?>"
        >
            <?php echo esc_html($label); ?>
        </a>
    <?php endforeach; ?>
</nav>

<input type="hidden" name="factorchi_section" value="<?php echo esc_attr($section); ?>" />

<?php
include FACTORCHI_DIR . 'admin/views/tabs/templates/' . $section . '.php';
