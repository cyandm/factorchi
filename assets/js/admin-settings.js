(function ($) {
    'use strict';

    function initLogoPicker() {
        var frame;
        var $input = $('#shop_logo');
        var $preview = $('#fc-logo-preview');

        $('#fc-pick-logo').on('click', function (e) {
            e.preventDefault();

            if (frame) {
                frame.open();
                return;
            }

            frame = wp.media({
                title: 'انتخاب لوگو',
                button: { text: 'استفاده از این تصویر' },
                multiple: false,
                library: { type: 'image' }
            });

            frame.on('select', function () {
                var attachment = frame.state().get('selection').first().toJSON();
                $input.val(attachment.url);
                $preview.html('<img src="' + attachment.url + '" alt="" />').show();
            });

            frame.open();
        });

        $input.on('change', function () {
            var url = $(this).val();
            if (url) {
                $preview.html('<img src="' + url + '" alt="" />').show();
            } else {
                $preview.hide().empty();
            }
        });
    }

    function initChannelPanels() {
        var channelMap = {
            channel_email: 'email',
            channel_sms: 'sms',
            channel_whatsapp: 'whatsapp',
            channel_socials: 'socials',
            channel_telegram: 'telegram',
            channel_bale: 'bale'
        };

        function syncPanels() {
            $.each(channelMap, function (inputName, channelId) {
                var enabled = $('input[name="' + inputName + '"]').is(':checked');
                var $card = $('.fc-channel-card[data-channel="' + channelId + '"]');
                var $fields = $('.fc-channel-fields[data-channel="' + channelId + '"]');

                $card.toggleClass('is-enabled', enabled);
                $fields.toggleClass('is-visible', enabled);
            });
        }

        $.each(channelMap, function (inputName) {
            $('input[name="' + inputName + '"]').on('change', syncPanels);
        });

        syncPanels();
    }

    function initTemplatePreview() {
        $('.fc-template-select').on('change', function () {
            var $row = $(this).closest('.fc-field');
            var base = $row.data('preview-base');
            var view = $(this).val();
            var $link = $row.find('.fc-preview-btn');

            if (!base || !view || !$link.length) {
                return;
            }

            var url = base + (base.indexOf('?') > -1 ? '&' : '?') + 'view=' + encodeURIComponent(view);
            var sizeMatch = String(view).match(/-a([45])$/i);
            if (sizeMatch) {
                url += '&print-size=a' + sizeMatch[1];
            } else if (String(view) === '50x80') {
                url += '&print-size=50x80';
            }
            $link.attr('href', url);
        });
    }

    function initToggleAutoSave() {
        var cfg = window.factorchiSettings || {};
        if (!cfg.ajaxUrl || !cfg.nonce) {
            return;
        }

        var timers = {};
        var clearTimers = {};
        var $status = $('.fc-autosave-status');
        var i18n = cfg.i18n || {};

        function setStatus(state, message) {
            $status
                .removeClass('is-saving is-saved is-error')
                .addClass(state)
                .text(message || '');
        }

        $('.factorchi-admin').on('change', '.fc-switch input[type="checkbox"]', function () {
            var $input = $(this);
            var key = $input.attr('name');
            if (!key) {
                return;
            }

            clearTimeout(timers[key]);
            clearTimeout(clearTimers[key]);

            timers[key] = setTimeout(function () {
                setStatus('is-saving', i18n.saving || '…');

                $.post(cfg.ajaxUrl, {
                    action: 'factorchi_save_toggle',
                    nonce: cfg.nonce,
                    key: key,
                    value: $input.is(':checked') ? 'yes' : 'no'
                })
                    .done(function (res) {
                        if (res && res.success) {
                            setStatus('is-saved', i18n.saved || 'OK');
                            clearTimers[key] = setTimeout(function () {
                                if ($status.hasClass('is-saved')) {
                                    setStatus('', '');
                                }
                            }, 2000);
                        } else {
                            var msg = (res && res.data && res.data.message) || i18n.error || 'Error';
                            setStatus('is-error', msg);
                        }
                    })
                    .fail(function () {
                        setStatus('is-error', i18n.error || 'Error');
                    });
            }, 150);
        });
    }

    $(document).ready(function () {
        if ($('.factorchi-admin').length) {
            initLogoPicker();
            initChannelPanels();
            initTemplatePreview();
            initToggleAutoSave();
        }
    });
})(jQuery);
