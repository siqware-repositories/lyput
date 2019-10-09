<form class="modal-content" id="form-create">
    @csrf
    <div class="modal-header bg-light">
        <h5 class="modal-title">បន្ថែមគណនី</h5>
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
                    <input type="text" placeholder="ពិពណ៌នា" name="desc" class="form-control">
                </td>
                <td>
                    <div class="input-group">
                        <select data-placeholder="ជ្រើសរើសប្រភេទគណនី" name="type" class="form-control form-control-select2"></select>
                        <span class="input-group-append" id="account-type-create" data-toggle="modal" data-target="#modal_sub_action">
												<button class="btn btn-icon btn-light" type="button"><i class="icon-plus-circle2"></i></button>
											</span>
                    </div>
                </td>
                <td>
                    <input name="balance" id="purchase" type="number" min="0" value="0" step="any" class="form-control"
                           placeholder="តម្លៃទិញ">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-link text-warning" data-dismiss="modal">ចាកចេញ</button>
        <button type="button" id="account-store" class="btn bg-success">រក្សារទុក</button>
    </div>
</form>