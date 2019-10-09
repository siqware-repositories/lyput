document.addEventListener('DOMContentLoaded', function() {
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    });
    $.ajax({
        url:route('account.dashboard.data'),
        method: 'post',
        success:function (data) {
            $('.retain-earning').text(formatter.format(data.retain_earning));
        }
    });
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
        $.ajax({
            url:route('invoice.selling.report'),
            method: 'post',
            data:{start:start,end:end},
            success:function (data) {
                $('.total-sale').text(formatter.format(data.totalSale));
                $('.total-pur').text(formatter.format(data.totalPur));
                $('.total-remain').text(formatter.format(data.remain));
            }
        });
    }
    initDatatable();
});
