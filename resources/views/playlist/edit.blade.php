<form class="modal-content" id="form-edit">
    @csrf
    <div class="modal-header bg-light">
        <h5 class="modal-title">កែប្រែទំនិញ</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th>ពិពណ៌នា</th>
                <th>ចំនួន</th>
                <th>តម្លៃទិញ</th>
                <th>តម្លៃលក់</th>
            </tr>
            </thead>
            <tbody id="get-more-tr">
            <tr>
                <td>
                    <input type="text" value="{{$stock->product->desc}}" placeholder="ពិពណ៌នា" name="desc"
                           class="form-control ac-basic ui-autocomplete-input" autocomplete="off">
                </td>
                <td>
                    <input name="qty" id="qty" value="{{$stock->qty}}" type="number" min="0" step="any"
                           class="form-control" placeholder="ចំនួន">
                </td>
                <td>
                    <input name="pur_price" id="purchase" value="{{$stock->pur_value}}" type="number" min="0" step="any"
                           class="form-control" placeholder="តម្លៃទិញ">
                </td>
                <td>
                    <input name="sell_price" id="sell" value="{{$stock->sale_value}}" type="number" min="0" step="any"
                           class="form-control" placeholder="តម្លៃលក់">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-link text-warning" data-dismiss="modal">ចាកចេញ</button>
        <button type="button" id="product-update" data-id="{{$stock->id}}" class="btn bg-success">រក្សារទុក</button>
    </div>
</form>