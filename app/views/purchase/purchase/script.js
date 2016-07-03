$('#vendor_name')
    .autocomplete({
        minLength: 1,
        source: function (request, response) {
            var result = [];
            var limit = 10;
            var term = request.term.toLowerCase();
            $.each(MASTERS.vendors, function () {
                var vendor = this;
                if (vendor.name.toLowerCase().indexOf(term) >= 0 || vendor.code.toLowerCase().indexOf(term) >= 0) {
                    result.push(vendor);
                    limit--;
                    if (limit <= 0) {
                        return false;
                    }
                }
            });
            response(result);
        },
        focus: function (event, ui) {
            $("#vendor_name").val(ui.item.name);
            return false;
        },
        select: function (event, ui) {
            $("#vendor_name").val(ui.item.name);
            $('#vendor_id').val(ui.item.id);
            return false;
        }
    })
    .autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append("<a>" + item.code + "<br>" + item.name + "</a>")
        .appendTo(ul);
};

// input product
$('#input-product').autocomplete({
    minLength: 0,
    source: function (request, response) {
        var result = [];
        var limit = 10;
        var term = request.term.toLowerCase();
        $.each(MASTERS.products, function () {
            var product = this;
            if (product.name.toLowerCase().indexOf(term) >= 0 || product.code.toLowerCase().indexOf(term) >= 0) {
                result.push(product);
                limit--;
                if (limit <= 0) {
                    return false;
                }
            }
        });
        response(result);
    },
    focus: function (event, ui) {
        $("#input-product").val(ui.item.name);
        return false;
    },
    select: function (event, ui) {
        selectProduct(ui.item);
        return false;
    }
})
    .autocomplete("instance")._renderItem = function (ul, item) {
    return $("<li>")
        .append("<a>" + item.code + "<br>" + item.name + "</a>")
        .appendTo(ul);
};

function selectProduct(item) {
    $("#input-product").val('');

    var $row = $('#detail-grid').mdmTabularInput('addRow');
    var itemPrice = item.price;

    $row.find(':input[data-field="item_id"]').val(item.id);
    $row.find('span[data-field="item"]').text(item.name);
    $row.find(':input[data-field="qty"]').val(1);
    $row.find(':input[data-field="price"]').val(itemPrice).focus();
    calculateTotal();
}

$('#detail-grid').on('keydown', ':input', function (e) {
    var th = $(this);
    var $row = th.closest('tr');
    if (e.which == 13) {
        if (th.data('field') == 'price') {
            $row.find(':input[data-field="qty"]').focus();
        } else if (th.data('field') == 'qty') {
            $("#input-product").focus();
        }
        return false;
    }
});

$('#detail-grid').on('change', ':input', function () {
    calculateTotal();
});

function calculateTotal() {
    var total = 0;
    $('#detail-grid').children('tr').each(function () {
        var $row = $(this);
        var price = $row.find(':input[data-field="price"]').val() * 1;
        var qty = $row.find(':input[data-field="qty"]').val() * 1;
        $row.find('span[data-field="totalLine"]').text(price * qty);
        total += price * qty;
    });
    $('#purchase-value').val(total);
    $('#total').text(total);
}

$('#purchase-branch_id').change(function () {
    changeBranch($(this).val());
});

changeBranch($('#purchase-branch_id').val(), $('#init_wh_id').val());

function changeBranch(id, wh_id) {
    $('#purchase-warehouse_id > option:gt(0)').remove();
    $.each(MASTERS.warehouses, function () {
        if (this.branch_id == id) {
            $('#purchase-warehouse_id').append($('<option>').val(this.id).text(this.name));
        }
    });
    if (wh_id) {
        $('#purchase-warehouse_id').val(wh_id);
    }
}
