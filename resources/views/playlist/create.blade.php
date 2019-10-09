<form class="modal-content" id="form-create">
    @csrf
    <div class="modal-header bg-light">
        <h5 class="modal-title">បង្កើតទំនិញដំុ</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>

    <div class="modal-body">
        <table class="table table-striped table-bordered table-sm">
            <thead>
            <tr class="bg-light">
                <th class="px-1">
                    <input type="text" placeholder="ពិពណ៌នា" name="desc" class="form-control">
                </th>
                <th class="px-1">
                    <input type="number" placeholder="ចំនួន" id="main_qty" name="qty" step="any" min="1" class="form-control">
                </th>
                <th class="px-1">
                    <input type="number" placeholder="តម្លៃទិញ" name="purchase" step="any" min="1" class="form-control">
                </th>
                <th class="px-1">
                    <input type="number" placeholder="តម្លៃលក់" name="sale" step="any" min="1" class="form-control">
                </th>
            </tr>
            </thead>
            <tbody id="get-more-tr">
            {{--get tr--}}
            <tr data-id="0"></tr>
            <input type="hidden" id="validate-sub-item" name="validate">
            </tbody>
            <tfoot>
            <tr class="bg-light">
                <td colspan="4" class="pt-3">
                    <select data-placeholder="ជ្រើសរើស" class="form-control form-control-select2" data-fouc></select>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-link text-warning" data-dismiss="modal">ចាកចេញ</button>
        <button type="button" id="playlist-store" class="btn bg-success">រក្សារទុក</button>
    </div>
</form>