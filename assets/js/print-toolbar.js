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

    function getQueryParams() {
        var params = new URLSearchParams(window.location.search);
        return {
            printSize: params.get('print-size') || config.printSize || 'a4',
            perPage: parseInt(params.get('per-page') || config.perPage || '1', 10)
        };
    }

    function applyBodyClasses(printSize, perPage) {
        var body = document.body;
        body.classList.remove('fc-print-a4', 'fc-print-a5', 'fc-print-1up', 'fc-print-2up', 'fc-print-4up');
        body.classList.add('fc-print-' + printSize);
        body.classList.add('fc-print-' + perPage + 'up');
    }

    function buildUrl(printSize, perPage) {
        var url = new URL(window.location.href);
        url.searchParams.set('print-size', printSize);
        url.searchParams.set('per-page', String(perPage));
        if (!url.searchParams.get('mode')) {
            url.searchParams.set('mode', 'compact');
        }
        return url.toString();
    }

    function init() {
        var sizeSelect = document.getElementById('fc-print-size');
        var perPageSelect = document.getElementById('fc-print-per-page');
        var printBtn = document.getElementById('fc-print-trigger');

        if (!sizeSelect || !perPageSelect) {
            return;
        }

        var prefs = readPrefs();
        var current = getQueryParams();

        if (!window.location.search.includes('print-size') && prefs.printSize) {
            current.printSize = prefs.printSize;
        }
        if (!window.location.search.includes('per-page') && prefs.perPage) {
            current.perPage = prefs.perPage;
        }

        sizeSelect.value = current.printSize === 'a5' ? 'a5' : 'a4';
        perPageSelect.value = [1, 2, 4].indexOf(current.perPage) !== -1 ? String(current.perPage) : '1';

        applyBodyClasses(sizeSelect.value, parseInt(perPageSelect.value, 10));

        function onLayoutChange() {
            var printSize = sizeSelect.value;
            var perPage = parseInt(perPageSelect.value, 10);

            writePrefs({ printSize: printSize, perPage: perPage });

            var needsReload = printSize !== current.printSize || perPage !== current.perPage;
            if (needsReload) {
                window.location.href = buildUrl(printSize, perPage);
                return;
            }

            applyBodyClasses(printSize, perPage);
        }

        sizeSelect.addEventListener('change', onLayoutChange);
        perPageSelect.addEventListener('change', onLayoutChange);

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
