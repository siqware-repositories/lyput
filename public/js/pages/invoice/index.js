// Setup module
// ------------------------------
var table;
var table_group;
var InvoiceBasic = function() {
    /*renumber tr*/
    function reNumber(){
        var tr_count =  $('tbody tr .row-count');
        tr_count.each(function (key,val) {
            $(val).text(key+1);
        });
    }
    function calcAmount(node_selector) {
        /*calc amount*/
        var amount = 0;
        $(node_selector+' tbody .amount').each(function (key,val) {
            amount+=parseFloat($(val).val());
        });
        $(node_selector+' #total').val(amount);
    }
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
            lengthChange: false,
            pageLength:5,
            info:false,
            select:true,
            ajax: {
                url: route('invoice.stock.list'),
                method:'post'
            },
            columns: [
                { data: 'product.desc', name: 'product.desc'}
            ],
            "columnDefs": [
                { className: "pl-2", "targets": [ 0] }
            ]
        });
        table_group = $('.datatable-scroll-y-group').DataTable({
            autoWidth: true,
            processing: true,
            serverSide: true,
            lengthChange: false,
            pageLength:5,
            info:false,
            select:true,
            ajax: {
                url: route('invoice.stock.list.group'),
                method:'post'
            },
            columns: [
                { data: 'product.desc', name: 'product.desc'}
            ],
            "columnDefs": [
                { className: "pl-2", "targets": [ 0] }
            ]
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
    //
    // Return objects assigned to module
    //
    var select2DomSingle = '.form-control-select2-single';
    var select2DomGroup = '.form-control-select2-group';
    var _searchStockSingle = function () {
        $(select2DomSingle).select2({
            width:'100%',
            ajax:{
                url:route('search.stock'),
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
                        calcAmount('#invoice-list');
                        reNumber();
                    }
                }
            });
        });
    };
    var _searchStockGroup = function () {
        $(select2DomGroup).select2({
            width:'89.2%',
            ajax:{
                url:route('bundle.search'),
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
        $(select2DomGroup).on("select2:select", function (e) {
            var id = parseInt($(this).val());
            $.ajax({
                url:route('invoice.stock.item.playlist',id),
                method: 'post',
                dataType:'html',
                data: {'_token':$('meta[name="csrf-token"]').attr('content')},
                success:function (data) {
                    var ids = [];
                    $('#invoice-list tbody .playlist').each(function (ket,val) {
                        ids.push(parseInt($(val).attr('data-id')));
                    });
                    if (ids.indexOf(id)===-1) {
                        $('#get-tr').append(data);
                        calcAmount('#invoice-list');
                        reNumber();
                    }
                }
            });
        });
    };
    return {
        init: function() {
            _componentDatatable();
            _componentSelect2();
            _searchStockSingle();
            _searchStockGroup();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    InvoiceBasic.init();
    invoiceAction();
});
function invoiceAction() {
    /*add tr*/
    $(document).on('click','.datatable-scroll-y tbody tr',function () {
        var id = parseInt($(this).attr('id'));
        $.ajax({
            url:route('invoice.stock.item',id),
            method: 'post',
            dataType:'html',
            data: {'_token':$('meta[name="csrf-token"]').attr('content')},
            success:function (data) {
                var ids = [];
                $('#invoice-list tbody tr').each(function (ket,val) {
                    ids.push(parseInt($(val).attr('data-id')));
                });
                if (ids.indexOf(id)===-1) {
                    $('#get-tr').append(data);
                    calcAmount('#invoice-list');
                    reNumber();
                }
            }
        });
    });
    /*add tr group*/
    $(document).on('click','.datatable-scroll-y-group tbody tr',function () {
        var id = parseInt($(this).attr('id'));
        $.ajax({
            url:route('invoice.stock.item.group',id),
            method: 'post',
            dataType:'html',
            data: {'_token':$('meta[name="csrf-token"]').attr('content')},
            success:function (data) {
                var ids = [];
                $('#invoice-list-group tbody tr').each(function (ket,val) {
                    ids.push(parseInt($(val).attr('data-id')));
                });
                if (ids.indexOf(id)===-1) {
                    $('#get-tr-group').append(data);
                    calcAmount('#invoice-list-group');
                    reNumber();
                }
            }
        });
    });
    /*remove tr*/
    $(document).on('click','#remove-tr',function (){
        $(this.parentNode.parentNode).remove();
        calcAmount();
        reNumber();
    });
    /*calc total*/
    $(document).on('keyup keydown keypress change','#invoice-list tbody .qty,#invoice-list tbody .sale, #invoice-list-group tbody .qty,#invoice-list-group tbody .sale',function (){
        var tr_node = this.parentNode.parentNode;
        var qty_el = $(tr_node).find('.qty').val();
        var sale_el = $(tr_node).find('.sale').val();
        $(tr_node).find('.amount').val(parseInt(qty_el)*parseFloat(sale_el));
        calcAmount('#invoice-list');
        calcAmount('#invoice-list-group');
    });
    function calcAmount(node_selector) {
        /*calc amount*/
        var amount = 0;
        $(node_selector+' tbody .amount').each(function (key,val) {
            amount+=parseFloat($(val).val());
        });
        $(node_selector+' #total').val(amount);
    }
    /*store invoice*/
    $(document).on('click','#invoice-store',function () {
        var data = $('#invoice-create').serialize();
        $.ajax({
            url:route('invoice.store'),
            method:'post',
            data:data,
            success:function (data) {
                console.log(data);
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
                    $('#get-tr').html('<tr data-id="0"><input type="hidden" name="invoice_validate" value="ok"></tr>');
                    document.getElementById('invoice-create').reset();
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
    /*renumber tr*/
    function reNumber(){
        var tr_count =  $('tbody tr .row-count');
        tr_count.each(function (key,val) {
            $(val).text(key+1);
        });
    }
}
