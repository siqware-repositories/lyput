<form class="modal-content" id="form-create-type">
    @csrf
    <div class="modal-body">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th>ប្រភេទគណនី</th>
                <th>Value</th>
                <th>CSS' Class</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="text" placeholder="ប្រភេទគណនី" name="type" class="form-control">
                </td>
                <td>
                    <input type="text" placeholder="Value" name="value" class="form-control">
                </td>
                <td>
                    <input type="text" placeholder="CSS' Class" name="class" class="form-control">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-link text-warning" data-dismiss="modal">ចាកចេញ</button>
        <button type="button" id="account-type-store" class="btn bg-success">រក្សារទុក</button>
    </div>
</form>