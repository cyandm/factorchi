(function () {
    'use strict';

    var config = window.FACTORCHI_PRINT || {};
    var storageKey = config.storageKey || 'factorchi_print_prefs';

    function readPrefs() {
        try {
            var raw = localStorage.getItem(storageKey);
            return raw ? JSON.parse(raw) : {};
        } catch (e) {
            return {};
        }
    }

    function writePrefs(prefs) {
        try {
            localStorage.setItem(storageKey, JSON.stringify(prefs));
        } catch (e) {
            /* ignore */
        }
    }

    function getPrintSize() {
        var params = new URLSearchParams(window.location.search);
        return params.get('print-size') || config.printSize || 'a4';
    }

    function applyBodyClasses(printSize) {
        var body = document.body;
        body.classList.remove('fc-print-a4', 'fc-print-a5');
        body.classList.add('fc-print-' + printSize);
    }

    function buildUrl(printSize) {
        var url = new URL(window.location.href);
        url.searchParams.set('print-size', printSize);
        return url.toString();
    }

    function init() {
        var sizeSelect = document.getElementById('fc-print-size');
        var printBtn = document.getElementById('fc-print-trigger');

        if (!sizeSelect) {
            if (printBtn) {
                printBtn.addEventListener('click', function () {
                    window.print();
                });
            }
            return;
        }

        var prefs = readPrefs();
        var current = getPrintSize();

        if (!window.location.search.includes('print-size') && prefs.printSize) {
            current = prefs.printSize;
        }

        sizeSelect.value = current === 'a5' ? 'a5' : 'a4';
        applyBodyClasses(sizeSelect.value);

        sizeSelect.addEventListener('change', function () {
            var printSize = sizeSelect.value;
            writePrefs({ printSize: printSize });
            if (printSize !== current) {
                window.location.href = buildUrl(printSize);
                return;
            }
            applyBodyClasses(printSize);
        });

        if (printBtn) {
            printBtn.addEventListener('click', function () {
                window.print();
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
