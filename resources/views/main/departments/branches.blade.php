@extends('layouts/base')
@section('title'){{ $title }}@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>{{ $title }}</h1>
            @include('includes/breadcrumbs')
        </section>
        <!-- Main content -->
        <section class="content">
            @include('includes/flash')
            <!-- Box primary-->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <a href="{{ route('departments', [], false) }}" class="btn btn-default">Jurusan</a>
                </div>
                <div class="box-body">
                    @if (count($branches) > 0)
                        <ul class="list-inline">
                            @foreach ($branches as $branch)
                                <li>&raquo; {{ $branch }}</li> 
                            @endforeach
                        </ul>
                    @else
                        <h3 class="no-content">Tidak ada fakultas yang terasosiasi dengan {{ $department->department_name }} Jurusan</h3>
                    @endif
                </div>
            </div>
            <!-- /.box -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection