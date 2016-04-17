
$('#srch').dsearchbox({
    data: [
        {field: 'code'},
        {field: 'name'},
        {field: 'nmCategory', op: 'bt'}
    ],
    panelHeight: 'auto',
    value: {},
    onChangeValue: function (v) {
        $('#dg').datagrid('reload', {q: v});
    }
});

$('#btn-vbox').linkbutton({
    onClick:function(){
        $('#vbox').textbox('addCustomInvalid','My message');
    }
});