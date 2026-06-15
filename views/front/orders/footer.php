<?php
if (!defined('ABSPATH')){
    exit(__( 'No Access!', 'fci' ));
}
?>

<?php if (get_bloginfo('language') == 'fa-IR'): ?>
    <!--    load jquery-->
    <script src="<?php echo includes_url(); ?>js/jquery/jquery.js?ver=1.12.4-wp"></script>
    <!--    load persian datepicker-->
    <script src="<?php echo FCI_JS_URL; ?>persian-datepicker.min.js"></script>
    <script>
        jQuery(document).ready(function ($) {
            if ($('.datepicker-field').length) {
                $('.datepicker-field').persianDatepicker({formatDate: "YYYY-MM-DD"});
            }
        });
    </script>
<?php if (get_fci_settings('use-persian-number')): ?>
    <script src="<?php echo FCI_JS_URL; ?>persianumber.min.js"></script>
    <script>
        jQuery(document).ready(function ($) {
            $('body').persiaNumber();
        });
    </script>
<?php endif; ?>
<?php endif; ?>
</body>
</html>