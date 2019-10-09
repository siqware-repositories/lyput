// Setup module
// ------------------------------
var table;
var ProductBasic = function() {
    //
    // Setup module components
    //
    // Basic Datatable examples
    var _componentDatatable = function() {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend( $.fn.dataTable.defaults, {
            autoWidth: false,
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [ 5 ]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
            }
        });
        // Alternative pagination
        $('.datatable-pagination').DataTable({
            pagingType: "simple",
            language: {
                paginate: {'next': $('html').attr('dir') == 'rtl' ? 'Next &larr;' : 'Next &rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr; Prev' : '&larr; Prev'}
            }
        });

        // Scrollable datatable
        table = $('.datatable-scroll-y').DataTable({
            autoWidth: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: route('product.list'),
                method:'post'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'product.desc', name: 'product.desc'},
                { data: 'qty', name: 'qty' },
                { data: 'pur_value', name: 'pur_value' },
                { data: 'sale_value', name: 'sale_value' },
                { data: 'stock_qty', name: 'stock_qty' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action',searchable:false,orderable:false },
            ],
            "columnDefs": [
                { className: "pl-2", "targets": [ 0,1,2,3,4,5,6] },
                { className: "text-center", "targets": [ 7 ] },
            ],
            drawCallback:function () {
                $('.text-input').editable({mode:'inline'});
            }
        });
        // Resize scrollable table when sidebar width changes
        $('.sidebar-control').on('click', function() {
            table.ajax.reload( null, false );
        });
    };

    // Select2 for length menu styling
    var _componentSelect2 = function() {
        if (!$().select2) {
            console.warn('Warning - select2.min.js is not loaded.');
            return;
        }

        // Initialize
        $('.dataTables_length select').select2({
            minimumResultsForSearch: Infinity,
            dropdownAutoWidth: true,
            width: 'auto'
        });
    };
    var select2DomSingle = 'form-control-select2-stock';
    var _product_stock = function () {
        $(select2DomSingle).on("select2:select", function (e) {
            var id = parseInt($(this).val());
            $.ajax({
                url:route('invoice.stock.item',id),
                method: 'post',
                dataType:'html',
                data: {'_token':$('meta[name="csrf-token"]').attr('content')},
                success:function (data) {
                    var ids = [];
                    $('#invoice-list tbody .stock').each(function (ket,val) {
                        ids.push(parseInt($(val).attr('data-id')));
                    });
                    if (ids.indexOf(id)===-1) {
                        $('#get-tr').append(data);
                    }
                }
            });
        });
    };
    //
    // Return objects assigned to module
    //
    return {
        init: function() {
            _componentDatatable();
            _componentSelect2();
        },
        initProductStock:function () {
            _product_stock();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    ProductBasic.init();
});
productAction();
function productAction() {
    /*product create single*/
    $(document).on('click','#product-create',function () {
        $.ajax({
            url:route('product.create'),
            method: 'get',
            success:function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*event add more row*/
    var i = 1;
    $(document).on('click','#product-add-row',function () {
        var tr = '<tr>\n' +
            '                            <td class="text-center">'+i+'</td>\n' +
            '                            <td>\n' +
            '                                <input type="text" placeholder="ពិពណ៌នា" name="product['+i+'][desc]"\n' +
            '                                       class="form-control ac-basic ui-autocomplete-input" autocomplete="off">\n' +
            '                            </td>\n' +
            '                            <td>\n' +
            '                                <input name="product['+i+'][qty]" id="qty" type="number" min="0" step="any"\n' +
            '                                       class="form-control" placeholder="ចំនួន">\n' +
            '                            </td>\n' +
            '                            <td>\n' +
            '                                <input name="product['+i+'][pur_price]" id="purchase" type="number" min="0" step="any"\n' +
            '                                       class="form-control" placeholder="តម្លៃទិញ">\n' +
            '                            </td>\n' +
            '                            <td>\n' +
            '                                <input name="product['+i+'][sell_price]" id="sell" type="number" min="0" step="any"\n' +
            '                                       class="form-control" placeholder="តម្លៃលក់">\n' +
            '                            </td>\n' +
            '                            <td>\n' +
            '                                <input name="product['+i+'][amount]" readonly="" id="amount" type="number" min="0"\n' +
            '                                       step="any" class="form-control amount" placeholder="សរុប">\n' +
            '                            </td>\n' +
            '                            <td>\n' +
            '                                <a href="#" id="product-remove-row" class="badge badge-warning"><i class="icon-diff-removed"></i></a>\n' +
            '                            </td>\n' +
            '                        </tr>';
        i++;
        var table_node = this.parentNode.parentNode.parentNode.parentNode;
        $(table_node).find('#get-more-tr').append(tr);
        reNumber(table_node);
        calcTotal(table_node);
    });
    /*renumber tr*/
    function reNumber(table_node){
        var tr_count =  $(table_node).find('tbody tr .text-center:not(.uncount)');
        tr_count.each(function (key,val) {
            $(val).text(key+1);
        });
    }
    /*remove row*/
    $(document).on('click','#product-remove-row',function () {
        var table_node = this.parentNode.parentNode.parentNode.parentNode;
        $(this.parentNode.parentNode).remove();
        reNumber(table_node);
        calcTotal(table_node);
    });
    /*calculate amount and total*/
    $(document).on('change keyup keydown','#qty,#purchase',function () {
        var tr_node = $(this.parentNode.parentNode);
        var qty = parseInt(tr_node.find('#qty').val());
        var purchase = parseFloat(tr_node.find('#purchase').val());
        var amount = tr_node.find('#amount');
        if (isNaN(qty)){
            qty = 0;
        }
        if (isNaN(purchase)){
            purchase = 0;
        }
        amount.val(qty*purchase);
        var table_node = this.parentNode.parentNode.parentNode.parentNode;
        calcTotal(table_node);
    });
    /*calc total function*/
    function calcTotal(table_node) {
        var inputAmount = $(table_node).find('.amount');
        var totalAmount = 0;
        inputAmount.each(function (key,val) {
            if (isNaN(parseFloat($(val).val()))) {
                $(val).val(0)
            }
            totalAmount+=parseFloat($(val).val());
        });
        $('#total').val(totalAmount);
    }
    /*store product*/
    $(document).on('click','#product-store',function () {
        var data = $('#form-create').serialize();
        $.ajax({
            url:route('product.store'),
            method:'post',
            data:data,
            success:function (data) {
                /*sweet alert*/
                if (data.success) {
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'រូចរាល់!',
                        text: 'ប្រតិបត្តិការជោគជ័យ!',
                        type: 'success'
                    });
                    $('#get-more-tr').html('<tr>\n' +
                        '                            <td class="text-center">0</td>\n' +
                        '                            <td>\n' +
                        '                                <input type="text" placeholder="ពិពណ៌នា" name="product[0][desc]" class="form-control ac-basic ui-autocomplete-input" autocomplete="off">\n' +
                        '                            </td>\n' +
                        '                            <td>\n' +
                        '                                <input name="product[0][qty]" id="qty" type="number" min="0" step="any" class="form-control" placeholder="ចំនួន">\n' +
                        '                            </td>\n' +
                        '                            <td>\n' +
                        '                                <input name="product[0][pur_price]" id="purchase" type="number" min="0" step="any" class="form-control" placeholder="តម្លៃទិញ">\n' +
                        '                            </td>\n' +
                        '                            <td>\n' +
                        '                                <input name="product[0][sell_price]" id="sell" type="number" min="0" step="any" class="form-control" placeholder="តម្លៃលក់">\n' +
                        '                            </td>\n' +
                        '                            <td>\n' +
                        '                                <input name="product[0][amount]" readonly="" id="amount" type="number" min="0" step="any" class="form-control" placeholder="សរុប">\n' +
                        '                            </td>\n' +
                        '                            <td>\n' +
                        '                            </td>\n' +
                        '                        </tr>');
                    document.getElementById('form-create').reset();
                    table.ajax.reload( null, false );
                }else{
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'មិនរូចរាល់!',
                        text: 'ប្រតិបត្តិការបរាជ័យ!',
                        type: 'warning'
                    });
                }
            }
        })
    });
    /*store product*/
    $(document).on('click','#product-store-group',function () {
        var data = $('#form-create').serialize();
        $.ajax({
            url:route('product.store.group'),
            method:'post',
            data:data,
            success:function (data) {
                /*sweet alert*/
                if (data.success) {
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'រូចរាល់!',
                        text: 'ប្រតិបត្តិការជោគជ័យ!',
                        type: 'success'
                    });
                    $('#get-more-tr').html('<tr>\n' +
                        '                            <td class="text-center">0</td>\n' +
                        '                            <td>\n' +
                        '                                <input type="text" placeholder="ពិពណ៌នា" name="product[0][desc]" class="form-control ac-basic ui-autocomplete-input" autocomplete="off">\n' +
                        '                            </td>\n' +
                        '                            <td>\n' +
                        '                                <input name="product[0][qty]" id="qty" type="number" min="0" step="any" class="form-control" placeholder="ចំនួន">\n' +
                        '                            </td>\n' +
                        '                            <td>\n' +
                        '                                <input name="product[0][pur_price]" id="purchase" type="number" min="0" step="any" class="form-control" placeholder="តម្លៃទិញ">\n' +
                        '                            </td>\n' +
                        '                            <td>\n' +
                        '                                <input name="product[0][sell_price]" id="sell" type="number" min="0" step="any" class="form-control" placeholder="តម្លៃលក់">\n' +
                        '                            </td>\n' +
                        '                            <td>\n' +
                        '                                <input name="product[0][amount]" readonly="" id="amount" type="number" min="0" step="any" class="form-control" placeholder="សរុប">\n' +
                        '                            </td>\n' +
                        '                            <td>\n' +
                        '                            </td>\n' +
                        '                        </tr>');
                    document.getElementById('form-create').reset();
                    table.ajax.reload( null, false );
                }else{
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'មិនរូចរាល់!',
                        text: 'ប្រតិបត្តិការបរាជ័យ!',
                        type: 'warning'
                    });
                }
            }
        })
    });
    /*product create multiple*/
    $(document).on('click','#product-create-group',function (){
        $.ajax({
            url:route('product.create.group'),
            method: 'get',
            success:function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*product create multiple*/
    $(document).on('click','#product-edit',function (){
        var id = parseInt($(this).attr('data-id'));
        $.ajax({
            url:route('product.edit',id),
            method: 'get',
            success:function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*store update*/
    $(document).on('click','#product-update',function () {
        var id = parseInt($(this).attr('data-id'));
        var data = $('#form-edit').serialize();
        $.ajax({
            url:route('product.update',id),
            method:'put',
            data:data,
            success:function (data) {
                /*sweet alert*/
                if (data.success) {
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'រូចរាល់!',
                        text: 'ប្រតិបត្តិការជោគជ័យ!',
                        type: 'success'
                    });
                    table.ajax.reload( null, false );
                }else{
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'មិនរូចរាល់!',
                        text: 'ប្រតិបត្តិការបរាជ័យ!',
                        type: 'warning'
                    });
                }
            }
        })
    });
    /*user destroy*/
    $(document).on('click','#product-destroy',function () {
        var id = parseInt($(this).attr('data-id'));
        swal({
            title: 'តើអ្នកប្រាកដដែរឬទេ?',
            text: "ចុចពាក្យ បាទ/ចាស៎ដើម្បីបន្តរ",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'បាទ/ចាស៎',
            cancelButtonText: 'ទេ',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(function(result) {
            if(result.value) {
                $.ajax({
                    url:route('product.destroy',id),
                    dataType:'json',
                    method:'delete',
                    data:{
                        '_token':$('meta[name="csrf-token"]').attr('content')
                    },
                    success:function (data) {
                        if (data.success) {
                            swal({
                                buttonsStyling: false,
                                confirmButtonClass: 'btn btn-primary',
                                cancelButtonClass: 'btn btn-light',
                                title: 'រូចរាល់!',
                                text: 'ប្រតិបត្តិការជោគជ័យ!',
                                type: 'success'
                            });
                            table.ajax.reload( null, false );
                        }
                    }
                });
            }
            else if(result.dismiss === swal.DismissReason.cancel) {
                swal({
                    buttonsStyling: false,
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-light',
                    title: 'មិនរូចរាល់!',
                    text: 'ប្រតិបត្តិការបរាជ័យ!',
                    type: 'error'
                });
            }
        });
    });
    /*stock*/
    /*stock create single*/
    $(document).on('click','#stock-create',function () {
        $.ajax({
            url:route('stock.create'),
            method: 'get',
            success:function (data) {
                $('#modal-content').html(data);
                $('.form-control-select2-stock').select2({
                    width:'400px',
                    ajax:{
                        url:route('search.stock.product'),
                        method:'post',
                        dataType:'json',
                        delay:250,
                        data:function (params) {
                            return {
                                _term: params.term
                            };
                        },
                    }
                });
            }
        });
    });
    /*store stock*/
    $(document).on('click','#stock-store',function () {
        var data = $('#form-create').serialize();
        $.ajax({
            url:route('stock.store'),
            method:'post',
            data:data,
            success:function (data) {
                /*sweet alert*/
                if (data.success) {
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'រូចរាល់!',
                        text: 'ប្រតិបត្តិការជោគជ័យ!',
                        type: 'success'
                    });
                    document.getElementById('form-create').reset();
                    table.ajax.reload( null, false );
                }else{
                    swal({
                        buttonsStyling: false,
                        confirmButtonClass: 'btn btn-primary',
                        cancelButtonClass: 'btn btn-light',
                        title: 'មិនរូចរាល់!',
                        text: 'ប្រតិបត្តិការបរាជ័យ!',
                        type: 'warning'
                    });
                }
            }
        })
    });
    /*check out stock product*/
    $.ajax({
        url:route('product.check.out.stock'),
        method: 'get',
        success:function (data) {
            $('.out-stock').text(data);
        }
    });
}
