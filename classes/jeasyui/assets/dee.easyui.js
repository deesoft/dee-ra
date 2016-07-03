(function ($) {

    $.fn.deeEasyui = function () {};

    $.fn.deeEasyui.defaults = {
        method: 'get',
    };

    $.fn.deeEasyui.methods = {
        dateFormatter: function (date) {
            var y = date.getFullYear();
            var m = date.getMonth() + 1;
            var d = date.getDate();
            return (d < 10 ? ('0' + d) : d) + '/' + (m < 10 ? ('0' + m) : m) + '/' + y;
        },
        dateParser: function (s) {
            if (!s) {
                return new Date();
            }
            var ss = s.split('/');
            var d = parseInt(ss[0], 10);
            var m = parseInt(ss[1], 10);
            var y = parseInt(ss[2], 10);
            if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
                return new Date(y, m - 1, d);
            } else {
                return new Date();
            }
        },
        getValue: function (obj, field) {
            if (!$.isArray(obj) && !$.isPlainObject(obj)) {
                return undefined;
            }
            if (typeof obj[field] != 'undefined') {
                return obj[field];
            }
            var p = field.lastIndexOf('.');
            if (p > 0) {
                var f = field.substr(p + 1);
                field = field.substr(0, p);
                obj = $.fn.deeEasyui.methods.getValue(obj, field);
                return $.isArray(obj) || $.isPlainObject(obj) ? obj[f] : undefined;
            }
            return undefined;
        }
    };

    // remote method
    $.fn.datagrid.defaults.method = $.fn.deeEasyui.defaults.method;
    $.fn.combogrid.defaults.method = $.fn.deeEasyui.defaults.method;
    $.fn.combobox.defaults.method = $.fn.deeEasyui.defaults.method;

    // date format and parser
    $.fn.datebox.defaults.formatter = $.fn.deeEasyui.methods.dateFormatter;
    $.fn.datebox.defaults.parser = $.fn.deeEasyui.methods.dateParser;


    // Add custom to validation on the fly
    $.extend($.fn.validatebox.methods, {
        addCustomError: function (jq, message) {
            return jq.each(function () {
                $.data(this, 'validatebox').message = message;
                $(this).addClass('validatebox-invalid');
            });
        }
    });

})(jQuery);