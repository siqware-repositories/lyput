// Setup module
// ------------------------------
var table;
var AccountBasic = function() {
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
                url: route('account.list'),
                method:'post'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'desc', name: 'desc'},
                { data: 'type', name: 'type' },
                { data: 'balance', name: 'balance' },
                { data: 'other_balance', name: 'other_balance' },
                { data: 'created_at', name: 'created_at' },
                { data: 'history', name: 'history' },
                { data: 'action', name: 'action',searchable:false,orderable:false },
            ],
            "columnDefs": [
                { className: "pl-2", "targets": [ 0,1,2,3,4] },
                { className: "text-center", "targets": [ 5 ] },
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
    var _componentSearchSelect2 = function () {
        $('.form-control-select2').select2({
            width:'300px',
            ajax:{
                url:route('account.type.search'),
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
    };
    return {
        init: function() {
            _componentDatatable();
            _componentSelect2();
        },
        initSearchSelect2:function () {
            _componentSearchSelect2();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function() {
    AccountBasic.init();
});
accountAction();
function accountAction() {
    /*account create*/
    $(document).on('click','#account-create',function () {
        $.ajax({
            url:route('account.create'),
            method: 'get',
            success:function (data) {
                $('#modal-content').html(data);
                AccountBasic.initSearchSelect2();
            }
        });
    });
    /*account type create*/
    $(document).on('click','#account-type-create',function () {
        $.ajax({
            url:route('account-type.create'),
            method: 'get',
            success:function (data) {
                $('#modal-sub-content').html(data);
            }
        });
    });
    /*store account type*/
    $(document).on('click','#account-type-store',function () {
        var data = $('#form-create-type').serialize();
        $.ajax({
            url:route('account-type.store'),
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
                    document.getElementById('form-create-type').reset();
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
    /*account product*/
    $(document).on('click','#account-store',function () {
        var data = $('#form-create').serialize();
        $.ajax({
            url:route('account.store'),
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
    /*account edit*/
    $(document).on('click','#account-edit',function (){
        var id = parseInt($(this).attr('data-id'));
        $.ajax({
            url:route('account.edit',id),
            method: 'get',
            success:function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*store update*/
    $(document).on('click','#account-update',function () {
        var id = parseInt($(this).attr('data-id'));
        var data = $('#form-edit').serialize();
        $.ajax({
            url:route('account.update',id),
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
    /*start*/
//money format
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    });
    /*show history*/
    $(document).on('click','#history-show',function () {
        var id = parseInt($(this).attr('data-id'));
        $.ajax({
            url:route('account.show.history'),
            method: 'get',
            success:function (data) {
                $('#modal-content').html(data);
                /*date range*/
                var start = moment().format('Y-MM-DD');
                var end = moment().add(1,'days').format('Y-MM-DD');
                var range = {
                    'Today': [moment(), moment().add(1,'days')],
                    'Yesterday': [moment().subtract(1, 'days'), moment()],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment().add(1,'days')],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment().add(1,'days')],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                };
                $(document).on('click','#btn-today,#btn-yesterday,#btn-last-7days,#btn-last-30days,#btn-this-month,#btn-last-month, #btn-range',function () {
                    if (this.id==='btn-today') {
                        start = range.Today[0].format('Y-MM-DD');
                        end = range.Today[1].format('Y-MM-DD');
                    }else if (this.id==='btn-yesterday') {
                        start = range.Yesterday[0].format('Y-MM-DD');
                        end = range.Yesterday[1].format('Y-MM-DD');
                    }else if (this.id==='btn-last-7days') {
                        start = range["Last 7 Days"][0].format('Y-MM-DD');
                        end = range["Last 7 Days"][1].format('Y-MM-DD');
                    }else if (this.id==='btn-last-30days') {
                        start = range["Last 30 Days"][0].format('Y-MM-DD');
                        end = range["Last 30 Days"][1].format('Y-MM-DD');
                    }else if (this.id==='btn-this-month') {
                        start = range["This Month"][0].format('Y-MM-DD');
                        end = range["This Month"][1].format('Y-MM-DD');
                    }else if (this.id==='btn-last-month') {
                        start = range["Last Month"][0].format('Y-MM-DD');
                        end = range["Last Month"][1].format('Y-MM-DD');
                    }
                    else if (this.id==='btn-range') {
                        start = moment($('#start').val()).format('Y-MM-DD');
                        end = moment($('#end').val()).format('Y-MM-DD');
                    }
                    initDatatable();
                });
                /*end date range*/
                function initDatatable() {
                    var table_history = $('.datatable-history').DataTable({
                        destroy:true,
                        autoWidth:true,
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: route('account.show',id),
                            method:'get',
                            data: {
                                "range": {start,end}
                            }
                        },
                        columns: [
                            { data: 'id', name: 'id' },
                            { data: 'desc', name: 'desc'},
                            { data: 'memo', name: 'memo' },
                            { data: 'balance', name: 'balance' },
                            { data: 'created_at', name: 'created_at' }
                        ],
                        "columnDefs": [
                            { className: "pl-2", "targets": [ 0,1,2,3] },
                            { className: "text-center", "targets": [ 4 ] },
                        ],
                        order:[4,'desc']
                    });
                    $.ajax({
                        url:route('account.show.history.data',id),
                        method: 'post',
                        data: {
                            "range": {start,end}
                        },
                        success:function (data) {
                            $('.send').text(formatter.format(data.totalSend));
                            $('.receive').text(formatter.format(data.totalReceive));
                            table_history.columns().adjust().draw();
                        }
                    });
                }
                initDatatable();
            }
        });
    });
    /*deposit create*/
    $(document).on('click','#deposit-create',function () {
        $.ajax({
            url:route('deposit.create'),
            method: 'get',
            success:function (data) {
                $('#modal-content').html(data);
                AccountBasic.initSearchSelect2();
            }
        });
    });
    /*store deposit*/
    $(document).on('click','#deposit-store',function () {
        var data = $('#form-create-deposit').serialize();
        $.ajax({
            url:route('deposit.store'),
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
                    document.getElementById('form-create-deposit').reset();
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
    /*account pay create*/
    $(document).on('click','#account-pay',function () {
        var _id = parseInt($(this).attr('data-id'));
        $.ajax({
            url:route('account.return.create'),
            method: 'get',
            data:{id:_id},
            success:function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*account return store*/
    $(document).on('click','#ap-return-store',function () {
        var data = $('#form-create').serialize();
        $.ajax({
            url:route('account.return.store'),
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
    /*calc payable*/
    $(document).on('change keyup keydown keypress blur', '.payable', function (){
        var tr_node = $(this.parentNode.parentNode);
        var el_due = parseFloat(tr_node.find('.due').val());
        tr_node.find('.remain').val(el_due-parseFloat($(this).val()));
    });
}
