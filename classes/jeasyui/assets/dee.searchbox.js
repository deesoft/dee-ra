/**
 * dsearchbox - jQuery EasyUI
 * 
 * Dependencies:
 *   combo
 * 
 */
(function ($) {

    /**
     * set values
     */
    function setValue(target, values, change) {
        var state = $.data(target, 'dsearchbox');
        var panel = $(target).combo('panel');
        var inputs = state.inputs;

        if (change) {
            panel.find('input[field]').val('');
        }
        var vv = values || {};
        var ss = [];
        var prefixs = ['!==', '!=', '>=', '<=', '>', '<', '==', '='];
        $.each(vv, function (f, v) {
            var tr = panel.find('tr[field="' + f + '"]');
            var op = tr.attr('operator');
            if (!op) {
                op = v.op;
            }
            var nv = {};
            if (typeof v == 'object') {
                nv.v = v.v;
                nv.v2 = v.v2;
            } else {
                nv.v = v;
            }
            if (!op && nv.v) {
                for (var i = 0; i < prefixs.length; i++) {
                    if (nv.v.indexOf(prefixs[i]) === 0) {
                        op = prefixs[i];
                        nv.v = nv.v.substr(prefixs[i].length);
                        break;
                    }
                }
            }
            
            if (op) {
                nv.op = op;
            }

            var s = f;
            if (op == '[]') {
                s += '[';
            } else if (op == '[]') {
                s += '![';
            } else {
                s += (op || '=');
            }

            if (nv.v) {
                s += encodeURIComponent(nv.v);
                if (change) {
                    tr.find('input[field="v"]')[inputs[f].type]('setValue', nv.v);
                }
            }
            if (op == '[]' || op == '![]') {
                s += ':' + encodeURIComponent(nv.v2) + ']';
                if (change) {
                    tr.find('input[field="v2"]')[inputs[f].type]('setValue', nv.v2);
                }
            }
            ss.push(s);
            vv[f] = nv;
        });
        state.value = vv;
        $(target).combo('setText', ss.join('&'));
    }

    /**
     * load data, the old list items will be removed.
     */
    function loadData(target, data) {
        var state = $.data(target, 'dsearchbox');
        var opts = state.options;
        state.data = data;

        var panel = $(target).combo('panel');
        render(target, panel, state.data);

        setValue(target, opts.value, true);
        opts.onLoadSuccess.call(target, data);
    }

    function render(target, container, data) {
        var state = $.data(target, 'dsearchbox');
        var opts = state.options;

        var tb = [];
        var ff = {};
        var inputs = {};
        var inpType, inpOpts;
        var width;
        tb.push('<table style="width:100%"><tbody>');
        for (var i = 0; i < data.length; i++) {
            var row = data[i];
            var f = row.field;
            if (!f || ff[f]) {
                continue;
            }
            ff[f] = true;
            var opAttr = (row.op) ? 'operator="' + row.op + '"' : '';

            var tr = '<tr field="' + f + '"' + opAttr + '>';
            tr += '<th>' + (row.title ? row.title : f) + '</th>';
            tr += '<th>' + (row.op || '&nbsp;') + '</th><td>';

            if (row.input) {
                if (typeof row.input == 'string') {
                    inpType = row.input;
                    inpOpts = {};
                } else {
                    inpType = row.input.type || 'textbox';
                    inpOpts = row.input.options || {};
                }
            } else {
                inpType = 'textbox';
                inpOpts = {};
            }

            tr += '<input field="v" >';
            width = 200;
            if (row.op == '[]' || row.op == '![]') {
                tr += ' <input field="v2" >';
                width = 140;
            }
            tr += '</td>';
            tb.push(tr);

            inputs[f] = ({
                type: inpType,
                options: $.extend({width: width}, inpOpts),
            });

        }
        tb.push('<tr><th colspan="2">&nbsp;</th><td>');
        tb.push('<a action="search" ></a>&nbsp;<a action="cancel" ></a>');

        tb.push('</td></tr></tbody></table>');

        var co = $(container);
        co.html(tb.join(''));

        $.each(inputs, function (f, inp) {
            var sel = 'tr[field="' + f + '"] input[field]';
            co.find(sel)[inp.type]($.extend({}, inp.options));
        });

        // button
        co.find('a[action="search"]').linkbutton($.extend({}, {
            iconCls: 'icon-search',
        }, opts.buttonSearchOptions, {
            onClick: function () {
                doEnter(target);
            }
        }));
        co.find('a[action="cancel"]').linkbutton($.extend({}, {
            iconCls: 'icon-cancel',
        }, opts.buttonCancelOptions, {
            onClick: function () {
                $(target).dsearchbox('hidePanel');
            }
        }));
        state.inputs = inputs;
    }

    function doEnter(target) {
        var t = $(target);
        var state = $.data(target, 'dsearchbox');
        var opts = state.options;
        var panel = t.combo('panel');
        var inputs = state.inputs;

        var vv = {};
        panel.find('tr[field]').each(function () {
            var tr = $(this);
            var f = tr.attr('field');

            var v = {
                op: tr.attr('operator')
            };
            tr.find('input[field]').each(function () {
                v[$(this).attr('field')] = $(this)[inputs[f].type]('getValue');
            });
            vv[f] = v;
        });
        setValue(target, vv, false);

        t.dsearchbox('hidePanel');
        opts.onChangeValue.call(target, vv);
    }

    /**
     * create the component
     */
    function create(target) {
        var state = $.data(target, 'dsearchbox');
        var opts = state.options;

        $(target).addClass('dsearchbox-f');
        $(target).combo($.extend({}, opts, {
            editable: false,
            onShowPanel: function () {
                opts.onShowPanel.call(this);
            }
        }));
        loadData(target, opts.data);
    }

    $.fn.dsearchbox = function (options, param) {
        if (typeof options == 'string') {
            var method = $.fn.dsearchbox.methods[options];
            if (method) {
                return method(this, param);
            } else {
                return this.combo(options, param);
            }
        }

        options = options || {};
        return this.each(function () {
            var state = $.data(this, 'dsearchbox');
            if (state) {
                $.extend(state.options, options);
            } else {
                state = $.data(this, 'dsearchbox', {
                    options: $.extend({}, $.fn.dsearchbox.defaults, $.fn.dsearchbox.parseOptions(this), options),
                    data: [],
                });
            }
            state.value = $.extend({}, state.options.value || {});
            create(this);
        });
    };

    $.fn.dsearchbox.methods = {
        options: function (jq) {
            var copts = jq.combo('options');
            return $.extend($.data(jq[0], 'dsearchbox').options, {
                width: copts.width,
                height: copts.height,
                originalValue: copts.originalValue,
                disabled: copts.disabled,
                readonly: copts.readonly
            });
        },
        cloneFrom: function (jq, from) {
            return jq.each(function () {
                $(this).combo('cloneFrom', from);
                $.data(this, 'dsearchbox', $(from).data('dsearchbox'));
                $(this).addClass('dsearchbox-f').attr('dsearchboxName', $(this).attr('textboxName'));
            });
        },
        getData: function (jq) {
            return $.data(jq[0], 'dsearchbox').data;
        },
        setValue: function (jq, values) {
            return jq.each(function () {
                setValue(this, values, true);
            });
        },
        getValue: function (jq) {
            return $.data(jq[0], 'dsearchbox').value;
        },
        clear: function (jq) {
            return jq.each(function () {
                $(this).combo('clear');
                var panel = $(this).combo('panel');
                panel.find('input[field]').val('');
                $.data(this, 'dsearchbox').options.value = {};
            });
        },
        loadData: function (jq, data) {
            return jq.each(function () {
                loadData(this, data);
            });
        },
    };

    $.fn.dsearchbox.parseOptions = function (target) {
        return $.extend({}, $.fn.combo.parseOptions(target), $.parser.parseOptions(target, [
        ]));
    };

    $.fn.dsearchbox.defaults = $.extend({}, $.fn.combo.defaults, {
        data: null,
        value: {},
        panelWidth: 'auto',
        panelHeight: 'auto',
        keyHandler: {
            up: function (e) {},
            down: function (e) {},
            left: function (e) {},
            right: function (e) {},
            enter: function (e) {
                doEnter(this)
            },
            query: function (q, e) {}
        },
        onLoadSuccess: function () {},
        onChangeValue: function (value) {}
    });
})(jQuery);