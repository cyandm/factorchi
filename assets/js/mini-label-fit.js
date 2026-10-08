(function () {
    'use strict';

    var MIN_FONT_PX = 8;
    var MIN_FONT_RATIO = 0.75;
    var STEP_PX = 0.5;
    var MAX_PAGES = 20;

    function overflows(label) {
        var inner = label.querySelector('.inner');
        if (!inner) {
            return false;
        }
        var orders = inner.querySelector('.fc-mini-orders');
        if (orders && orders.scrollHeight > orders.clientHeight + 1) {
            return true;
        }
        return inner.scrollHeight > inner.clientHeight + 1;
    }

    function shrinkToFit(label) {
        var start = parseFloat(window.getComputedStyle(label).fontSize) || 12;
        var min = Math.max(MIN_FONT_PX, start * MIN_FONT_RATIO);
        var size = start;
        while (overflows(label) && size - STEP_PX >= min) {
            size -= STEP_PX;
            label.style.fontSize = size + 'px';
        }
        return size;
    }

    function formatPage(template, current, total) {
        return String(template || '%1$s / %2$s')
            .replace('%1$s', String(current))
            .replace('%2$s', String(total));
    }

    function makeMoreMarker(source) {
        var li = document.createElement('li');
        li.className = 'fc-mini-more';
        li.textContent = source.getAttribute('data-next-label') || '…';
        return li;
    }

    function makeContinuation(source, fontSize) {
        var label = document.createElement('div');
        label.className = source.className + ' fc-mini-label-continued';
        label.style.fontSize = fontSize + 'px';

        var inner = document.createElement('div');
        inner.className = 'inner';

        var head = document.createElement('p');
        head.className = 'fc-mini-continued-head';
        var title = document.createElement('strong');
        title.textContent = (source.getAttribute('data-continued-label') || '') + ' #' + (source.getAttribute('data-order-id') || '');
        var page = document.createElement('span');
        page.className = 'fc-mini-page';
        head.appendChild(title);
        head.appendChild(page);
        inner.appendChild(head);

        var name = source.querySelector('.fc-mini-name');
        if (name) {
            inner.appendChild(name.cloneNode(true));
        }

        var orders = document.createElement('div');
        orders.className = 'fc-mini-orders';
        var list = document.createElement('ol');
        list.className = 'fc-mini-orders-list';
        orders.appendChild(list);
        inner.appendChild(orders);

        label.appendChild(inner);
        return label;
    }

    function mount(afterNode, sourceFrame, label) {
        var frame = document.createElement('div');
        frame.className = sourceFrame.className + ' fc-mini-continued-frame';
        var style = sourceFrame.getAttribute('style');
        if (style) {
            frame.setAttribute('style', style);
        }
        frame.appendChild(label);

        var node = frame;
        var sheet = sourceFrame.parentNode;
        if (sheet && sheet.classList && sheet.classList.contains('fc-print-sheet')) {
            node = sheet.cloneNode(false);
            node.appendChild(frame);
        }
        afterNode.parentNode.insertBefore(node, afterNode.nextSibling);
        return node;
    }

    /**
     * Moves trailing items out of the list until the label fits, keeping at least one item.
     * Returns the moved items in their original order.
     */
    function trimToFit(label, list, source) {
        var moved = [];
        if (!overflows(label)) {
            return moved;
        }
        var marker = makeMoreMarker(source);
        list.appendChild(marker);
        while (overflows(label) && list.children.length > 2) {
            moved.unshift(list.removeChild(marker.previousElementSibling));
        }
        if (moved.length === 0) {
            list.removeChild(marker);
        }
        return moved;
    }

    function fit(label) {
        if (label.getAttribute('data-fc-fitted') === '1') {
            return;
        }
        label.setAttribute('data-fc-fitted', '1');

        var fontSize = shrinkToFit(label);
        var list = label.querySelector('.fc-mini-orders-list');
        var frame = label.closest('.fc-document-frame');
        if (!list || !frame || !overflows(label)) {
            return;
        }

        var pending = trimToFit(label, list, label);
        if (pending.length === 0) {
            return;
        }

        var lastNode = frame.parentNode && frame.parentNode.classList.contains('fc-print-sheet') ? frame.parentNode : frame;
        var continuations = [];

        while (pending.length && continuations.length < MAX_PAGES) {
            var cont = makeContinuation(label, fontSize);
            lastNode = mount(lastNode, frame, cont);
            continuations.push(cont);

            var contList = cont.querySelector('.fc-mini-orders-list');
            pending.forEach(function (li) {
                contList.appendChild(li);
            });
            pending = trimToFit(cont, contList, label);
        }

        var total = continuations.length + 1;
        var pageTemplate = label.getAttribute('data-page-label');
        continuations.forEach(function (cont, i) {
            var page = cont.querySelector('.fc-mini-page');
            if (page) {
                page.textContent = formatPage(pageTemplate, i + 2, total);
            }
        });
    }

    function run() {
        var labels = document.querySelectorAll('.fc-mini-label-50x80[data-fc-fit]');
        Array.prototype.forEach.call(labels, fit);
        if (typeof window.persianNumber === 'function') {
            window.persianNumber();
        }
    }

    function start() {
        var go = function () {
            window.requestAnimationFrame(run);
        };
        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(go, go);
        } else {
            go();
        }
    }

    if (document.readyState === 'complete') {
        start();
    } else {
        window.addEventListener('load', start);
    }
})();
