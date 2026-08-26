/**
 * jquery.dotanimator.js
 * @author Igor Karbachinsky <igorkarbachinsky@mail.ru>
 * @description Creates a simple dot flicker animation for arbitrary jquery element.
 * (c) September 2015
 *
 */
(function ($) {
    /**
     * Initialize
     * @param {Jquery selector} $block
     * @return
     */
    function DotAnimation($block, params) {
        var self = this;

        self.$block = $block;

        self.params = {
            speed: 400,
            numDots: 3,
            dotElement: '.'
        };

        if ("object" == typeof (params)) {
            $.extend(self.params, params);
        }

        self._bindEvents();
        self.$block.trigger('startDotAnimation');

        return self;
    }

    /**
     * Start animation
     * @return
     */
    DotAnimation.prototype._bindEvents = function () {
        var self = this;

        self.$block.bind('startDotAnimation', function () {
            self._start();
        });

        self.$block.bind('stopDotAnimation', function () {
            self._stop();
        });

        return self;
    },

        /**
         * Start animation
         * @return
         */
        DotAnimation.prototype._start = function (i) {
            var self = this;

            var i = 0;
            var html = self.$block.html();

            self.intervalId = setInterval(function () {
                i = ++i % (self.params['numDots'] + 1);
                self.$block.html(html + Array(i + 1).join(self.params['dotElement']));
            }, self.params['speed']);

            return self;
        };

    /**
     * Stop animation
     * @return
     */
    DotAnimation.prototype._stop = function () {
        var self = this;
        clearInterval(self.intervalId);

        return self;
    };

    /**
     * Wrapper for JQuery
     * @param {Objeet} params
     * @return DotAnimation object
     */
    $.fn.dotAnimation = function (params) {
        return new DotAnimation(this, params);
    };

})(jQuery);

jQuery(document).ready(function ($) {

    var nonce = $('meta[name="factorchi-nonce"]').attr('content');


    var bulkPrintTypeMap = {
        invoice: 'invoice',
        post_label: 'post-label',
        mini_label: 'mini-label'
    };

    function handleBulkPrint(e, selectorTop, checkboxSelector) {
        var $val = $(selectorTop).val();
        if (!$val || !$val.match("^factorchi_bulk_print")) {
            return;
        }
        e.preventDefault();
        var actionKey = $val.replace('factorchi_bulk_print_', '');
        var $type = bulkPrintTypeMap[actionKey] || actionKey;
        var ids = '';
        var cbox = $(checkboxSelector + ':checked');
        var all = cbox.length;
        if (!all) {
            return false;
        }
        cbox.each(function (index) {
            ids += $(this).val();
            if (index !== all - 1) {
                ids += ',';
            }
        });

        var url = FACTORCHI_JS_DATA.base_url + '?action=factorchi-show&type=' + encodeURIComponent($type) + '&order-id=' + ids;
        if ($type === 'mini-label' && FACTORCHI_JS_DATA.mini_label_print_size) {
            url += '&print-size=' + encodeURIComponent(FACTORCHI_JS_DATA.mini_label_print_size);
            if (FACTORCHI_JS_DATA.mini_label_view) {
                url += '&view=' + encodeURIComponent(FACTORCHI_JS_DATA.mini_label_view);
            }
        } else if ($type === 'post-label' && FACTORCHI_JS_DATA.post_label_print_size) {
            url += '&print-size=' + encodeURIComponent(FACTORCHI_JS_DATA.post_label_print_size);
            if (FACTORCHI_JS_DATA.post_label_view) {
                url += '&view=' + encodeURIComponent(FACTORCHI_JS_DATA.post_label_view);
            }
        } else if (FACTORCHI_JS_DATA.print_size) {
            url += '&print-size=' + encodeURIComponent(FACTORCHI_JS_DATA.print_size);
        }
        window.open(url);
    }

    $('.post-type-shop_order .bulkactions #doaction').on('click', function (e) {
        handleBulkPrint(e, '#bulk-action-selector-top', "#posts-filter [name='post[]']");
    });
    $('.post-type-shop_order .bulkactions #doaction2').on('click', function (e) {
        handleBulkPrint(e, '#bulk-action-selector-bottom', "#posts-filter [name='post[]']");
    });

    $('.woocommerce_page_wc-orders .bulkactions #doaction').on('click', function (e) {
        handleBulkPrint(e, 'select[name="action"]', '.woocommerce_page_wc-orders input[name="id[]"]');
    });
    $('.woocommerce_page_wc-orders .bulkactions #doaction2').on('click', function (e) {
        handleBulkPrint(e, 'select[name="action2"]', '.woocommerce_page_wc-orders input[name="id[]"]');
    });


    $('#factorchi-send-invoice').click(function (e) {
        e.preventDefault();
        var $this = $(this);
        
        var order_id = parseInt($this.data('id'));
        if (!order_id) {
            return false;
        }

        $this.dotAnimation({
            speed: 400,
            dotElement: '.',
            numDots: 3
        });
        
        $this.text(FACTORCHI_JS_DATA.waiting);
        $this.prop('disabled', true);
        
        $.ajax({
            url: ajaxurl,
            type: 'post',
            dataType: 'json',
            timeout: 45000,
            data: {
                action: 'factorchi_send_invoice',
                factorchiNonce: nonce,
                orderID: order_id
            },
            success: function (response) {
                // show simple alert
                if (response.result === true) {
                    alert(FACTORCHI_JS_DATA.invoice_send);
                }
                
            },
            error: function () {
                alert(FACTORCHI_JS_DATA.error_happend);
            },
            complete: function (data) {
                $this.text(FACTORCHI_JS_DATA.send_invoice);
                $this.prop('disabled', false);
                
                $this.trigger('stopDotAnimation');
            }
        });
    });
    $('#factorchi-send-invoice-payment').click(function (e) {
        e.preventDefault();
        var $this = $(this);
        
        var order_id = parseInt($this.data('id'));
        if (!order_id) {
            return false;
        }

        $this.dotAnimation({
            speed: 300,
            dotElement: '.',
            numDots: 3
        });
        
        $this.text(FACTORCHI_JS_DATA.waiting);
        $this.prop('disabled', true);
       

        $.ajax({
            url: ajaxurl,
            type: 'post',
            dataType: 'json',
            timeout: 45000,
            data: {
                action: 'factorchi_send_invoice_payment',
                factorchiNonce: nonce,
                orderID: order_id
            },
            success: function (response) {
                // simple alert
                if (response.result === true) {
                    alert(FACTORCHI_JS_DATA.payment_link_send);
                }
            },
            error: function () {
                alert(FACTORCHI_JS_DATA.error_happend);
            },
            complete: function (data) {
                $this.text(FACTORCHI_JS_DATA.send_payment_link);
                $this.prop('disabled', false);
                $this.trigger('stopDotAnimation');
            }
        });
    });

    $('#factorchi-send-invoice-sms').click(function (e) {
        e.preventDefault();
        var $this = $(this);

        var order_id = parseInt($this.data('id'));
        if (!order_id) {
            return false;
        }

        $this.dotAnimation({
            speed: 300,
            dotElement: '.',
            numDots: 3
        });

        $this.text(FACTORCHI_JS_DATA.waiting);
        $this.prop('disabled', true);

        $.ajax({
            url: ajaxurl,
            type: 'post',
            dataType: 'json',
            timeout: 45000,
            data: {
                action: 'factorchi_send_invoice_sms',
                factorchiNonce: nonce,
                orderID: order_id
            },
            success: function (response) {
                // simple alert
                if (response.result === true) {
                    alert(FACTORCHI_JS_DATA.invoice_send);
                }
            },
            error: function () {
                alert(FACTORCHI_JS_DATA.error_happend);
            },
            complete: function (data) {
                $this.text(FACTORCHI_JS_DATA.send_invoice_sms);
                $this.prop('disabled', false);

                $this.trigger('stopDotAnimation');
            }
        });
    });

    $('#factorchi-send-invoice-wa').click(function (e) {
        e.preventDefault();
        var $this = $(this);

        var order_id = parseInt($this.data('id'));
        if (!order_id) {
            return false;
        }

        $this.dotAnimation({
            speed: 300,
            dotElement: '.',
            numDots: 3
        });

        $this.text(FACTORCHI_JS_DATA.waiting);
        $this.prop('disabled', true);

        $.ajax({
            url: ajaxurl,
            type: 'post',
            dataType: 'json',
            timeout: 45000,
            data: {
                action: 'factorchi_send_invoice_wa',
                factorchiNonce: nonce,
                orderID: order_id
            },
            success: function (response) {
                // simple alert
                if (response.result === true) {
                    alert(FACTORCHI_JS_DATA.invoice_send);
                }
            },
            error: function () {
                alert(FACTORCHI_JS_DATA.error_happend);
            },
            complete: function (data) {
                $this.text(FACTORCHI_JS_DATA.send_invoice_sms);
                $this.prop('disabled', false);

                $this.trigger('stopDotAnimation');
            }
        });
    });

        $('.wp-list-table .factorchi-invoice, .wp-list-table .factorchi-packing-slip, .wp-list-table .factorchi-post-label, .wp-list-table .factorchi-shop-label, .wp-list-table .factorchi-customer-label, .wp-list-table .factorchi-product-label, .wp-list-table .factorchi-mini-label').attr('target', '_blank');


    $('#factorchi-send-invoice-sms-payment').click(function (e) {
        e.preventDefault();
        var $this = $(this);
        var order_id = parseInt($this.data('id'));
        if (!order_id) {
            return false;
        }

        $this.dotAnimation({
            speed: 300,
            dotElement: '.',
            numDots: 3
        });


        $this.text(FACTORCHI_JS_DATA.waiting);
        $this.prop('disabled', true);

        $.ajax({
            url: ajaxurl,
            type: 'post',
            dataType: 'json',
            timeout: 45000,
            data: {
                action: 'factorchi_send_invoice_sms_payment',
                factorchiNonce: nonce,
                orderID: order_id
            },
            success: function (response) {
                //simple alert
                if (response.result === true) {
                    alert(FACTORCHI_JS_DATA.payment_link_send);
                }
            },
            error: function () {
                alert(FACTORCHI_JS_DATA.error_happend);
            },
            complete: function (data) {
                $this.text(FACTORCHI_JS_DATA.send_payment_link);
                $this.prop('disabled', false);

                $this.trigger('stopDotAnimation');
            }
        });
    });

});