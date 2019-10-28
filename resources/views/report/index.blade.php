@extends('ui.master')
@section('page-title')
    វិក័យបត្រ ទិញលក់
@stop
@section('page-header')
    <div class="page-header">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">វិក័យបត្រ ទិញលក់</span></h4>
            </div>
        </div>
    </div>
@stop
@section('page-content')
    <!-- Basic card -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">វិក័យបត្រ ទិញលក់</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            {{--tab--}}
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#left-icon-tab1" class="nav-link active" data-toggle="tab"><i class="icon-menu7 mr-2"></i> ទិញ</a></li>
                <li class="nav-item"><a href="#left-icon-tab2" class="nav-link" data-toggle="tab"><i class="icon-menu7 mr-2"></i> លក់</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="left-icon-tab1">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped datatable-stock-inv table-bordered" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>តម្លៃលក់</th>
                                    <th>តម្លៃទិញ</th>
                                    <th>ចំនួន</th>
                                    <th>វិក័យបត្រ</th>
                                    <th>កាលបរិច្ឆេទ</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="left-icon-tab2">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped datatable-inv table-bordered" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ចំនួន</th>
                                    <th>តម្លៃទិញ</th>
                                    <th>វិក័យបត្រ</th>
                                    <th>កាលបរិច្ឆេទ</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{--/tab--}}
        </div>
    </div>
    <!-- /basic card -->
    <!-- Action modal -->
    <div id="modal_action" class="modal fade">
        <div class="modal-dialog modal-lg" id="modal-content">
            {{--get data--}}
        </div>
    </div>
    <!-- /Action modal -->
@stop
@push('page-js')
    <script src="{{asset('ui/global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('ui/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('js/pages/report/index.js')}}"></script>
@endpush