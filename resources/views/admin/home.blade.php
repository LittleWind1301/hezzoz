<!-- Stored in resources/views/child.blade.php -->

@extends('admin.layouts.admin')

@section('title')
    <title>Trang Chủ</title>
@endsection

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    @include('admin.partials.content-header', ['name'=> 'Trang Chủ', 'key'=>''])

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="alert alert-info">
                <h4>TRANG QUẢN TRỊ</h4>
            </div>
        </div>
    </div>
</div>


@endsection
