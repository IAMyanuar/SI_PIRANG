@extends('layout.master1')

@section('title')
    SI PIRANG | Kalender
@stop

@section('css')

@stop

@section('content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Calendar</h4>
                    <div class="d-flex align-items-center">
                        {{-- <nav aria-label="breadcrumb">
                            <ol class="breadcrumb m-0 p-0">
                                <li class="breadcrumb-item text-muted active" aria-current="page">Apps</li>
                                <li class="breadcrumb-item text-muted" aria-current="page">Calendar</li>
                            </ol>
                        </nav> --}}
                    </div>
                </div>
                <div class="col-5 align-self-center">
                    <div class="customize-input float-right">
                        <p><span class="form-control bg-white border-0 custom-shadow custom-radius"id="tanggalwaktu"></span>
                        </p>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <div class="row justify-content-md-center">
                <div class="col-9 card">
                    {{-- <div class="card"> --}}
                        <div class="card-body calender-sidebar">
                            <div id="calendar"></div>
                        </div>
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>


@endsection
