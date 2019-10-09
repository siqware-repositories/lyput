<form class="modal-content" id="form-edit">
    @csrf
    <div class="modal-header bg-light">
        <h5 class="modal-title">កែប្រែគណនី</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th>ពិពណ៌នា</th>
                <th>ប្រភេទគណនី</th>
                <th>ទឹកប្រាក់</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="text" placeholder="ពិពណ៌នា" name="desc" value="{{$account->desc}}" class="form-control">
                </td>
                <td>
                    <div class="input-group">
                        <select class="form-control" disabled>
                            <option>{{$account->type}}</option>
                        </select>
                    </div>
                </td>
                <td>
                    <input disabled class="form-control" type="number" step="any" value="{{$account->balance}}">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-link text-warning" data-dismiss="modal">ចាកចេញ</button>
        <button type="button" id="account-update" data-id="{{$account->id}}" class="btn bg-success">រក្សារទុក</button>
    </div>
</form>