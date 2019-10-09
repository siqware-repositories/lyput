// Setup module
// ------------------------------
var table;
var _table;
var BudgetBasic = function () {
    //
    // Setup module components
    //
    // Basic Datatable examples
    var _componentDatatable = function () {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend($.fn.dataTable.defaults, {
            autoWidth: false,
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [5]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: {
                    'first': 'First',
                    'last': 'Last',
                    'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                    'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
                }
            }
        });
        // Alternative pagination
        $('.datatable-pagination').DataTable({
            pagingType: "simple",
            language: {
                paginate: {
                    'next': $('html').attr('dir') == 'rtl' ? 'Next &larr;' : 'Next &rarr;',
                    'previous': $('html').attr('dir') == 'rtl' ? '&rarr; Prev' : '&larr; Prev'
                }
            }
        });

        // Scrollable datatable
        table = $('.datatable-income').DataTable({
            autoWidth: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: route('income.list'),
                method: 'post'
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'desc', name: 'desc'},
                {data: 'account', name: 'account'},
                {data: 'type', name: 'type'},
                {data: 'balance', name: 'balance'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', searchable: false, orderable: false},
            ],
            "columnDefs": [
                {className: "pl-2", "targets": [0, 1, 2, 3, 4, 5]},
                {className: "text-center", "targets": [6]},
            ]
        });
        _table = $('.datatable-expense').DataTable({
            autoWidth: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: route('expense.list'),
                method: 'post'
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'desc', name: 'desc'},
                {data: 'account', name: 'account'},
                {data: 'type', name: 'type'},
                {data: 'balance', name: 'balance'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', searchable: false, orderable: false},
            ],
            "columnDefs": [
                {className: "pl-2", "targets": [0, 1, 2, 3, 4, 5]},
                {className: "text-center", "targets": [6]},
            ]
        });
        // Resize scrollable table when sidebar width changes
        $('.sidebar-control').on('click', function () {
            table.ajax.reload(null, false);
        });
    };

    // Select2 for length menu styling
    var _componentSelect2 = function () {
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

    return {
        init: function () {
            _componentDatatable();
            _componentSelect2();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    BudgetBasic.init();
});
budgetBasic();

function budgetBasic() {
    /*income create*/
    $(document).on('click', '#income-create', function () {
        $.ajax({
            url: route('income.create'),
            method: 'get',
            success: function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*expense create*/
    $(document).on('click', '#expense-create', function () {
        $.ajax({
            url: route('expense.create'),
            method: 'get',
            success: function (data) {
                $('#modal-content').html(data);
            }
        });
    });
    /*event add more row*/
    var i = 1;
    $(document).on('click', '#product-add-row', function () {
        var tr = '<tr>\n' +
            '                <td class="text-center">1</td>\n' +
            '                <td>\n' +
            '                    <input type="text" placeholder="ពិពណ៌នា" name="income[' + i + '][desc]"\n' +
            '                           class="form-control ac-basic ui-autocomplete-input" autocomplete="off">\n' +
            '                </td>\n' +
            '                <td>\n' +
            '                    <input name="income[' + i + '][balance]" id="qty" type="number" min="0" step="any"\n' +
            '                           class="form-control" placeholder="ចំនួន">\n' +
            '                </td>\n' +
            '                <td>\n' +
            '                <a href="#" id="product-remove-row" class="badge badge-warning"><i class="icon-diff-removed"></i></a>\n' +
            '                </td>\n' +
            '            </tr>';
        i++;
        var table_node = this.parentNode.parentNode.parentNode.parentNode;
        $(table_node).find('#get-more-tr').append(tr);
        reNumber(table_node);
        calcTotal(table_node);
    });
    /*event add more row expense*/
    var i = 1;
    $(document).on('click', '#product-add-row-expense', function () {
        var tr = '<tr>\n' +
            '                <td class="text-center">1</td>\n' +
            '                <td>\n' +
            '                    <input type="text" placeholder="ពិពណ៌នា" name="expense[' + i + '][desc]"\n' +
            '                           class="form-control ac-basic ui-autocomplete-input" autocomplete="off">\n' +
            '                </td>\n' +
            '                <td>\n' +
            '                    <input name="expense[' + i + '][balance]" id="qty" type="number" min="0" step="any"\n' +
            '                           class="form-control" placeholder="ចំនួន">\n' +
            '                </td>\n' +
            '                <td>\n' +
            '                <a href="#" id="product-remove-row" class="badge badge-warning"><i class="icon-diff-removed"></i></a>\n' +
            '                </td>\n' +
            '            </tr>';
        i++;
        var table_node = this.parentNode.parentNode.parentNode.parentNode;
        $(table_node).find('#get-more-tr').append(tr);
        reNumber(table_node);
        calcTotal(table_node);
    });
    /*renumber tr*/
    function reNumber(table_node) {
        var tr_count = $(table_node).find('tbody tr .text-center:not(.uncount)');
        tr_count.each(function (key, val) {
            $(val).text(key + 1);
        });
    }

    /*remove row*/
    $(document).on('click', '#product-remove-row', function () {
        var table_node = this.parentNode.parentNode.parentNode.parentNode;
        $(this.parentNode.parentNode).remove();
        reNumber(table_node);
        calcTotal(table_node);
    });
    /*calculate amount and total*/
    $(document).on('change keyup keydown', '#qty,#purchase', function () {
        var tr_node = $(this.parentNode.parentNode);
        var qty = parseInt(tr_node.find('#qty').val());
        var purchase = parseFloat(tr_node.find('#purchase').val());
        var amount = tr_node.find('#amount');
        if (isNaN(qty)) {
            qty = 0;
        }
        if (isNaN(purchase)) {
            purchase = 0;
        }
        amount.val(qty * purchase);
        var table_node = this.parentNode.parentNode.parentNode.parentNode;
        calcTotal(table_node);
    });

    /*calc total function*/
    function calcTotal(table_node) {
        var inputAmount = $(table_node).find('.amount');
        var totalAmount = 0;
        inputAmount.each(function (key, val) {
            if (isNaN(parseFloat($(val).val()))) {
                $(val).val(0)
            }
            totalAmount += parseFloat($(val).val());
        });
        $('#total').val(totalAmount);
    }

    /*store income*/
    $(document).on('click', '#income-store', function () {
        var data = $('#form-create').serialize();
        $.ajax({
            url: route('income.store'),
            method: 'post',
            data: data,
            success: function (data) {
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
                        '                <td class="text-center">1</td>\n' +
                        '                <td>\n' +
                        '                    <input type="text" placeholder="ពិពណ៌នា" name="income[0][desc]" class="form-control ac-basic ui-autocomplete-input" autocomplete="off">\n' +
                        '                </td>\n' +
                        '                <td>\n' +
                        '                    <input name="income[0][balance]" id="qty" type="number" min="0" step="any" class="form-control" placeholder="ចំនួន">\n' +
                        '                </td>\n' +
                        '                <td>\n' +
                        '                </td>\n' +
                        '            </tr>');
                    document.getElementById('form-create').reset();
                    table.ajax.reload(null, false);
                } else {
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
    /*store expense*/
    $(document).on('click', '#expense-store', function () {
        var data = $('#form-create').serialize();
        $.ajax({
            url: route('expense.store'),
            method: 'post',
            data: data,
            success: function (data) {
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
                        '                <td class="text-center">1</td>\n' +
                        '                <td>\n' +
                        '                    <input type="text" placeholder="ពិពណ៌នា" name="expense[0][desc]" class="form-control ac-basic ui-autocomplete-input" autocomplete="off">\n' +
                        '                </td>\n' +
                        '                <td>\n' +
                        '                    <input name="expense[0][balance]" id="qty" type="number" min="0" step="any" class="form-control" placeholder="ចំនួន">\n' +
                        '                </td>\n' +
                        '                <td>\n' +
                        '                </td>\n' +
                        '            </tr>');
                    document.getElementById('form-create').reset();
                    _table.ajax.reload(null, false);
                } else {
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
    $(document).on('click', '#income-destroy', function () {
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
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: route('income.destroy', id),
                    dataType: 'json',
                    method: 'delete',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.success) {
                            swal({
                                buttonsStyling: false,
                                confirmButtonClass: 'btn btn-primary',
                                cancelButtonClass: 'btn btn-light',
                                title: 'រូចរាល់!',
                                text: 'ប្រតិបត្តិការជោគជ័យ!',
                                type: 'success'
                            });
                            table.ajax.reload(null, false);
                        }
                    }
                });
            } else if (result.dismiss === swal.DismissReason.cancel) {
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
    /*expense destroy*/
    $(document).on('click', '#expense-destroy', function () {
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
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: route('expense.destroy', id),
                    dataType: 'json',
                    method: 'delete',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.success) {
                            swal({
                                buttonsStyling: false,
                                confirmButtonClass: 'btn btn-primary',
                                cancelButtonClass: 'btn btn-light',
                                title: 'រូចរាល់!',
                                text: 'ប្រតិបត្តិការជោគជ័យ!',
                                type: 'success'
                            });
                            _table.ajax.reload(null, false);
                        }
                    }
                });
            } else if (result.dismiss === swal.DismissReason.cancel) {
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

}
