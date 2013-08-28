(function () {
    var answers,
        reg = /(\d), (\d), (.*)/,
        p = document.querySelectorAll('p'),
        nodes = Array.prototype.slice.call(p),
        _parse = function (node) {
            var text = (node.innerText || node.textContent);

            // remove extra spaces
            text = text.replace(/\s+/g, ' ');

            // trim
            text = text.replace(/^\s+|\s+$/g,'');

            // remove carriage returns
            text = text.replace(/\r?\n|\r/g, '');


            return text.match(reg);
        };

    // filter unrelated dom
    nodes = nodes.filter(_parse);

    // create answers array
    answers = nodes.map(function (node, index) {
        var res = 0,
            parse = _parse(node),
            r1 = parseInt(parse[1], 10),
            r2 = parseInt(parse[2], 10);

        if (r1 && r2) {
            res = 3;
        } else if (r1 && !r2) {
            res = 2;
        } else if (!r1 && r2) {
            res = 1;
        }

        return {
            elem: node,
            res: res,
            text: parse[3]
        };
    });

    // change html for each answers
    answers.forEach(function (answer) {
        var elem = answer.elem;
        elem.className += 'answer-type-' + (answer.res);
        elem.innerHTML = '<span class="coche">✓</span> ' + answer.text;
    });
})();