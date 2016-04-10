(function ($) {
    var defaults = {
        plugins: ['draggable', 'droppable', 'resizable', 'pagination', 'tooltip',
            'linkbutton', 'menu', 'menubutton', 'splitbutton', 'switchbutton', 'progressbar',
            'tree', 'textbox', 'filebox', 'combo', 'combobox', 'combotree', 'combogrid', 'numberbox', 'validatebox', 'searchbox',
            'spinner', 'numberspinner', 'timespinner', 'datetimespinner', 'calendar', 'datebox', 'datetimebox', 'slider',
            'layout', 'panel', 'datagrid', 'propertygrid', 'treegrid', 'datalist', 'tabs', 'accordion', 'window', 'dialog', 'form'
        ],
        remotePlugins: ['datagrid', 'combobox'],
        datePlugins: ['calender', 'datebox'],
        defaultMethod: 'get',
        // date formatter
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
        }
    };

    for (var i = 0; i < defaults.remotePlugins.length; i++) {
        $.fn[defaults.remotePlugins[i]].defaults.method = defaults.defaultMethod;
    }

    for (var i = 0; i < defaults.datePlugins.length; i++) {
        $.fn[defaults.datePlugins[i]].defaults.formatter = defaults.dateFormatter;
        $.fn[defaults.datePlugins[i]].defaults.parser = defaults.dateParser;
    }

})(jQuery);