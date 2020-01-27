<?php 
    $select2 = true;
    $datepicker = true;  
?>
@extends('layouts/base')
@section('title') {{ $title }} @endsection
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
            <div class="row">
                <div class="col-md-10">
                    <!-- Box primary-->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <a href="{{ route('documents', [], false) }}" class="btn btn-default">Dokumen</a>
                        </div>
                        {!! Form::open(['action' => 'DocumentsController@store', 'method' => 'POST', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data',]) !!}
                            <div class="box-body">
                                <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                    {{ Form::label('title', 'Judul*') }}
                                    {{ Form::text('title', '', ['class' => 'form-control', 'placeholder' => 'Judul Dokumen', 'required' => true]) }}
                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                        {{ $errors->first('title') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ (check_array_values($errors->all(), 'files') || $errors->has('files')) ? ' has-error' : '' }}">
                                    {{ Form::label('files', 'File*') }}
                                    {{ Form::file('files[]', ['required' => true, 'multiple' => true,]) }}
                                    <span class="help-block">Ukuran Maksimum: 5MB</span>
                                    <?php 
                                        foreach ($errors->all() as $error) {
                                            if (stripos($error, 'files') === false) { continue; }

                                            if (strpos($error, 'failed to upload') !== false) {
                                                echo '<span class="help-block">Satu atau lebih file melebihi ukuran maksimum 5MB</span>';
                                            } else {
                                                echo '<span class="help-block">'. $error . '</span>';
                                            }
                                        }  
                                    ?>
                                </div>
                                <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                                    {{ Form::label('category', 'Kategori*') }}
                                    {{ Form::select('category', $categories, null, ['placeholder' => 'Pilih Kategori', 'class' => 'form-control select2', 'required' => true,]) }}
                                    @if ($errors->has('category'))
                                        <span class="help-block">
                                        {{ $errors->first('category') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('creation_date') ? ' has-error' : '' }}">
                                    {{ Form::label('creation_date', 'Tanggal Dokumen Dibuat*') }}
                                    {{ Form::text('creation_date', '', ['class' => 'form-control date', 'placeholder' => 'Tanggal Dokumen Dibuat', 'required' => true]) }}
                                    @if ($errors->has('creation_date'))
                                        <span class="help-block">
                                        {{ $errors->first('creation_date') }}
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                    {{ Form::label('description', 'Deskripsi*') }}
                                    {{ Form::textarea('description', '', ['class' => 'form-control', 'placeholder' => 'Deskripsi Detail', 'required' => true, 'rows' => 4]) }}
                                    @if ($errors->has('description'))
                                        <span class="help-block">
                                        {{ $errors->first('description') }}
                                        </span>
                                    @endif
                                </div>
                                @if(is_admin())
                                <div class="form-group{{ $errors->has('branch') ? ' has-error' : '' }}">
                                    {{ Form::label('branch', 'Fakultas') }}
                                    {{ Form::select('branch', $branches, null, ['placeholder' => 'Pilih Fakultas', 'class' => 'form-control select2', 'id' => 'branch',]) }}
                                    @if ($errors->has('branch'))
                                    <span class="help-block">
                                    {{ $errors->first('branch') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="form-group{{ $errors->has('department') ? ' has-error' : '' }}">
                                    {{ Form::label('department', 'Jurusan') }}
                                    {{ Form::select('department', [], null, ['placeholder' => 'Pilih Dulu Fakultas', 'class' => 'form-control select2', 'id' => 'department',]) }}
                                    @if ($errors->has('department'))
                                    <span class="help-block">
                                    {{ $errors->first('department') }}
                                    </span>
                                    @endif
                                </div>
                                @endif
                                <span class="lead text-danger">* <em>wajib diisi</em></span>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                {{ Form::submit($title, 
                                    ['class' => 'btn btn-primary']) 
                                }}
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('scripts')
    @parent
        <script src="{{ asset('assets/vendor/select2/dist/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
        <script>
            //Initialize Select2 Elements
            $('.select2').select2();
            // Start Date
            $('.date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                endDate: "+0d",
            });

            <?php if (is_admin()) : ?>
                // Populate departments dropdown
                $('#branch').on('change', function(e) {
                    var branch_id = parseInt($(this).val(), 10);
                    if (branch_id) {
                        $.ajax({
                            method: "GET",
                            url: '/branches/' + branch_id + '/get_departments',
                            success: function(data) {
                                var values = data;
                                var options = '<option value="">Pilih Jurusan</option>';
                                for (var i = 0; i < values.length; i += 1) {
                                    options += '<option value="' + values[i]['id'];
                                    options += '">' + values[i]['department'];
                                    options += '</option>';
                                }
                                $('#department').html(options);
                            }
                        });
                    } else {
                        $('#department').html('<option value="">Select Branch First</option>');
                    }
                });
            <?php endif; ?>
        </script>
@stop