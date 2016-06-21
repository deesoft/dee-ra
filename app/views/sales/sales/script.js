function formatNumber(v) {
    var r = /\d(?=(\d{3})$)/g;
    return v.toString().replace(r, '$&,');
}

$('#dtl-datagrid').datagrid({
    fit: true, singleSelect: true, showHeader: false,
    fitColumns: true,
    toolbar: '#dtl-datagrid-tb',
    columns: [[
            {field: 'name', title: 'Item', width: 250},
            {field: 'nprice', title: 'Price', width: 100, align: 'right'},
            {field: 'qty', title: 'Qty', editor: {type: 'numberbox'}, width: 80, align: 'right'},
            {field: 'discon', title: 'Diskon', editor: {type: 'numberbox'}, width: 80, align: 'right'},
            {field: 'total', title: 'Total', width: 100, formatter: function (v, row) {
                    var discon = row.discon ? row.discon : 0;
                    return formatNumber(row.qty * row.price * (1 - 0.01 * discon));
                }, align: 'right'},
        ]],
    data: [],
    onClickCell: function (index, field) {
        beginEdit(index, field);
    }
});

$('#item-combogrid').combogrid({
    columns: [[
            {field: 'name', title: 'Name', width: 250},
            {field: 'price', title: 'Price'},
        ]],
    idField: 'id',
    panelWidth: 400,
    url: dOpts.itemUrl,
    delay: 500,
    mode: 'remote',
    selectOnNavigation: false,
    onSelect: function () {
        var row = $('#item-combogrid').combogrid('grid').datagrid('getSelected');
        if (row) {
            addRow(row);
            $('#item-combogrid').combogrid('setValue', '');
        }
    }
});

$('div.datagrid-view').on('keypress', 'input.textbox-text', function (e) {
    if (e.keyCode == 13) {
        $('#item-combogrid').combogrid('textbox').focus();
        endEdit();
    }
});

function addRow(row) {
    var ada = false, i = 0;
    $.each($('#dtl-datagrid').datagrid('getRows'), function (k, r) {
        if (r.id == row.id) {
            ada = true;
            r.qty++;
            $('#dtl-datagrid').datagrid('refreshRow', i);
            return false;
        }
        i++;
    });
    if (!ada) {
        $('#dtl-datagrid').datagrid('appendRow', {
            id: row.id,
            name: row.name,
            price: row.price,
            nprice: formatNumber(row.price),
            qty: 1,
        });
    }
}

var editIndex = undefined;
function beginEdit(index, field) {
    if (index !== editIndex) {
        if (editIndex !== undefined) {
            $('#dtl-datagrid').datagrid('endEdit', editIndex);
        }
        $('#dtl-datagrid').datagrid('beginEdit', index);
        editIndex = index;
        var ed = $('#dtl-datagrid').datagrid('getEditor', {index: index, field: field});
        if (ed) {
            $(ed.target)[ed.type]('textbox').focus();
        }
        console.log(field);
    }
}

function endEdit() {
    if (editIndex !== undefined) {
        $('#dtl-datagrid').datagrid('endEdit', editIndex);
        editIndex = undefined;
    }
}