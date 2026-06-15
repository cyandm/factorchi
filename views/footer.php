<?php

if (!defined('ABSPATH')) {
    exit;
}

if (factorchi_get_setting('use_persian_number', 'yes') === 'yes' && !$this->get_check_email()) {
    echo Factorchi_View_Render::footer_js();
}

echo Factorchi_View_Render::footer_action_btn($this->order_id, $this->type, $this->get_check_email());

if (factorchi_get_setting('page_break', 'no') === 'yes') {
    echo '<p style="page-break-before:always;"></p>';
}

?>
</div>
</body>
</html>
