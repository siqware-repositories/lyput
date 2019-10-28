<form class="modal-content" id="form-create-deposit">
    @csrf
    <div class="modal-header bg-light">
        <h5 class="modal-title">ដាក់ប្រាក់</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th>ពិពណ៌នា</th>
                <th>គណនី</th>
                <th>ទឹកប្រាក់</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="text" placeholder="ពិពណ៌នា" name="desc" class="form-control">
                </td>
                <td>
                    <select name="receiver" class="form-control km">
                        @foreach($receiver as $value)
                            <option value="{{$value->id}}">{{$value->desc}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input name="balance" type="number" min="0" value="0" step="any" class="form-control" placeholder="ទឹកប្រាក់">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-link text-warning" data-dismiss="modal">ចាកចេញ</button>
        <button type="button" id="deposit-store" class="btn bg-success">រក្សារទុក</button>
    </div>
</form>