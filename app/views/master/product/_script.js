
$('#srch').dsearchbox({
    data: [
        {field: 'code',},
        {field: 'name'},
        {field: 'category.name', title: 'Category'}
    ],
    panelHeight: 'auto',
    value: {},
    onChangeValue: function (v) {
        $('#dg').datagrid('reload', {q: v});
    }
});

$('#btn-vbox').linkbutton({
    onClick: function () {
        $('#vbox').textbox('addCustomInvalid', 'My message');
    }
});