@extends('ui.master')
@section('page-title')
    មេឌៀ
    @stop
@section('page-header')
    <div class="page-header">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">មេឌៀ</span></h4>
            </div>
        </div>
    </div>
    @stop
@section('page-content')
    <!-- Media card -->
    <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">គ្រប់គ្រងឯកសារ</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="laravel-filemanager"></iframe>
            </div>
        </div>
    </div>
    <!-- /media card -->
    @stop
@push('page-js')
    <script src="{{asset('js/pages/blank/index.js')}}"></script>
    @endpush