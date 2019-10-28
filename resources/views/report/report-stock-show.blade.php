<div class="modal-content">
    <div class="modal-header bg-light">
        <h5 class="modal-title">វិក័យបត្រទិញ</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>ពិពណ៌នា</th>
                <th>ចំនួន</th>
                <th>តម្លៃទិញ</th>
            </tr>
            </thead>
            <tbody>
            @php
                $total = 0;
            @endphp
            @foreach($stock_detail as $value)
                <tr>
                    <td>{{$value->id}}</td>
                    <td>{{$value->product->desc}}</td>
                    <td>{{$value->qty}}</td>
                    <td>{{$value->pur_value}}</td>
                </tr>
                @php
                    $total += $value->qty*$value->pur_value;
                @endphp
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td class="text-right">សរុប</td>
                <td>{{$total}}</td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>