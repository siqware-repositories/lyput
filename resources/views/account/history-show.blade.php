<div class="modal-content" id="form-create">
    @csrf
    <div class="modal-header bg-light">
        <h5 class="modal-title">បន្ថែមគណនី</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        {{--btn toollbar--}}
        <div class="row">
            <div class="col-md-12">
                <div class="btn-toolbar">
                    <div class="btn-group mt-1">
                        <button id="btn-today" class="btn btn-light">ថ្ងៃនេះ</button>
                        <button id="btn-yesterday" class="btn btn-light">ម្សិលមិញ</button>
                        <button id="btn-last-7days" class="btn btn-light">ប្រាំពីថ្ងៃមុន</button>
                    </div>
                    <div class="btn-group mr-2 mt-1">
                        <button id="btn-last-30days" class="btn btn-light">សាមសិបថ្ងៃមុន</button>
                        <button id="btn-this-month" class="btn btn-light">ខែនេះ</button>
                        <button id="btn-last-month" class="btn btn-light">ខែមុន</button>
                    </div>
                    <div class="btn-group mt-1">
                        <button type="button" class="btn btn-light p-0">
                            <input type="date" id="start" class="form-control border-0">
                        </button>
                        <button type="button" class="btn btn-light p-0">
                            <input type="date" id="end" class="form-control border-0">
                        </button>
                        <button type="button" class="btn btn-success" id="btn-range">បង្ហាញ</button>
                    </div>
                </div>
            </div>
        </div>
        {{--end btn toollbar--}}
        <div class="row mt-3">
            <div class="col-lg-4">
                <!-- Members online -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0 send"></h3>
                        </div>

                        <div>
                            ទឹកប្រាក់ចេញ
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div id="members-online"></div>
                    </div>
                </div>
                <!-- /members online -->

            </div>
            <div class="col-lg-4">
                <!-- Members online -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0 receive"></h3>
                        </div>

                        <div>
                            ទឹកប្រាក់ចូល
                        </div>
                    </div>

                    <div class="container-fluid">
                        <div id="members-online"></div>
                    </div>
                </div>
                <!-- /members online -->
            </div>
        </div>
        <table class="table table-bordered table-striped table-sm datatable-history" width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>ពិពណ៌នា</th>
                <th>ចំណាំ</th>
                <th>ទឹកប្រាក់</th>
                <th>កាលបរិច្ឆេទ</th>
            </tr>
            </thead>
        </table>
    </div>
    <div class="modal-footer bg-light">
        <button type="button" class="btn btn-link text-warning" data-dismiss="modal">ចាកចេញ</button>
    </div>
</div>