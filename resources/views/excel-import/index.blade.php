@extends('ui.master')
@section('page-title')
    Excel Import
@stop
@section('page-header')
    <div class="page-header">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Excel Import</span></h4>
            </div>
        </div>
    </div>
@stop
@section('page-content')
    <!-- Basic card -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">Excel Import</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{route('excel-import.store')}}" method="post" enctype="multipart/form-data" class="row">
                @csrf
                <div class="form-group col-lg-5">
                    <label>ជ្រើសរើសឯកសារ Excel</label>
                    <div class="input-group">
                    <input type="file" class="form-control" name="import_file">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="icon-import mr-2"></i> Import</button>
                    </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /basic card -->
@stop
@push('page-js')
    <script src="{{asset('ui/global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('ui/global_assets/js/plugins/notifications/sweet_alert.min.js')}}"></script>
    <script src="{{asset('ui/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('js/pages/product/index.js')}}"></script>
    <script src="{{asset('js/pages/playlist/index.js')}}"></script>
@endpush