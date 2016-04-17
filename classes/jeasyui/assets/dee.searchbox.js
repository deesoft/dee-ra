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
    function setValue(target, values) {
        var state = $.data(target, 'dsearchbox');
        var panel = $(target).combo('panel');
        var inputs = state.inputs;

        panel.find('input[dfield]').val('');
        var vv = values || {};
        var ss = [];
        $.each(vv, function (f, v) {
            var tr = panel.find('tr[dfield="' + f + '"]');
            var op = tr.attr('dop');
            var s = f;
            if (op == 'bt') {
                s += '{';
            } else if (op == 'nbt') {
                s += '![';
            } else {
                s += op;
            }
            var nv = {op: op};
            if (typeof v == 'object') {
                nv.v = v.v;
                nv.v2 = v.v2;
            } else {
                nv.v = v;
            }

            if (nv.v) {
                s += encodeURIComponent(nv.v);
                tr.find('input[dfield="v"]')[inputs[f].type]('setValue', nv.v);
            }
            if (op == 'bt' || op == 'nbt') {
                s += ':' + encodeURIComponent(nv.v2) + ']';
                tr.find('input[dfield="v2"]')[inputs[f].type]('setValue', nv.v2);
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

        setValue(target, opts.value);
        opts.onLoadSuccess.call(target, data);
    }

    function render(target, container, data) {
        var state = $.data(target, 'dsearchbox');
        var opts = state.options;

        var tb = [];
        var ff = {};
        var inputs = {};
        var inpType, inpOpts;
        tb.push('<table style="width:100%"><tbody>');
        for (var i = 0; i < data.length; i++) {
            var row = data[i];
            var f = row.field;
            if (!f || ff[f]) {
                continue;
            }
            ff[f] = true;
            var op = row.op || '=';

            var tr = '<tr dfield="' + f + '" dop="' + op + '">';
            tr += '<th>' + (row.title ? row.title : f) + '</th>';
            tr += '<td>' + (row.op || '&nbsp;') + '</td><td>';

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

            tr += '<input dfield="v" >';
            if (op == 'bt') {
                tr += '<br><input dfield="v2" >';
            }
            tr += '</td>';
            tb.push(tr);

            inputs[f] = ({
                type: inpType,
                options: inpOpts,
            });

        }
        tb.push('<tr><th colspan="2">&nbsp;</th><td>');
        tb.push('<a dbtn="search" ></a>&nbsp;<a dbtn="cancel" ></a>');

        tb.push('</td></tr></tbody></table>');

        var co = $(container);
        co.html(tb.join(''));

        $.each(inputs, function (f, inp) {
            var sel = 'tr[dfield="' + f + '"] input[dfield]';
            co.find(sel)[inp.type]($.extend({}, {
                width: 200,
            }, inp.options));
        });

        // button
        co.find('a[dbtn="search"]').linkbutton($.extend({}, {
            iconCls: 'icon-search',
        }, opts.buttonSearchOptions, {
            onClick: function () {
                doEnter(target);
            }
        }));
        co.find('a[dbtn="cancel"]').linkbutton($.extend({}, {
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
        panel.find('tr[dfield]').each(function () {
            var tr = $(this);
            var f = tr.attr('dfield');

            var v = {
                op: tr.attr('dop')
            };
            tr.find('input[dfield]').each(function () {
                v[$(this).attr('dfield')] = $(this)[inputs[f].type]('getValue');
            });
            vv[f] = v;
        });
        setValue(target, vv);

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
                setValue(this, values);
            });
        },
        getValue: function (jq) {
            return $.data(jq[0], 'dsearchbox').value;
        },
        clear: function (jq) {
            return jq.each(function () {
                $(this).combo('clear');
                var panel = $(this).combo('panel');
                panel.find('input[dfield]').val('');
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