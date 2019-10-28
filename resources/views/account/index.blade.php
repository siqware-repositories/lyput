@extends('ui.master')
@section('page-title')
    គណនី
@stop
@section('page-header')
    <div class="page-header">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">គណនី</span></h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>

            <div class="header-elements d-none text-center text-md-left mb-3 mb-md-0">
                <div class="btn-toolbar justify-content-center">
                    <div class="btn-group mr-2">
                        <button data-toggle="modal" id="account-create" data-target="#modal_action" type="button" class="btn btn-outline-info"><i class="icon-add mr-2"></i>បន្ថែម</button>
                        <button data-toggle="modal" id="deposit-create" data-target="#modal_action" type="button" class="btn btn-outline-info"><i class="icon-download mr-2"></i>ដាក់ប្រាក់</button>
                    </div>

                    {{--<div class="btn-group">
                        <button type="button" class="btn btn-outline-info"><i class="icon-add mr-2"></i>បន្ថែម</button>
                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@stop
@section('page-content')
    <!-- Basic card -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">បញ្ជីគណនី</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-striped datatable-scroll-y" width="100%">
            <thead>
            <tr>
                <th>#</th>
                <th>ពិពណ៌នា</th>
                <th>ប្រភេទ</th>
                <th>ទឹកប្រាក់</th>
                <th>ទឹកប្រាក់ផ្សេងទៀត</th>
                <th>កាលបរិច្ឆេទ</th>
                <th>ប្រវត្តិប្រតិបត្តការណ៍</th>
                <th></th>
            </tr>
            </thead>
        </table>
    </div>
    <!-- /basic card -->
    <!-- Action modal -->
    <div id="modal_action" class="modal fade">
        <div class="modal-dialog modal-xl" id="modal-content">
            {{--get data--}}
        </div>
    </div>
    <!-- /Action modal -->
    <!-- Sub Action modal -->
    <div id="modal_sub_action" class="modal fade">
        <div class="modal-dialog modal-sm" id="modal-sub-content">
            {{--get data--}}
        </div>
    </div>
    <!-- /Sub Action modal -->
@stop
@push('page-js')
    <script src="{{asset('ui/global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('ui/global_assets/js/plugins/notifications/sweet_alert.min.js')}}"></script>
    <script src="{{asset('ui/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('ui/global_assets/js/plugins/ui/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/pages/account/index.js')}}"></script>
@endpush