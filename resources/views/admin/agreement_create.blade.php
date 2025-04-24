@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Agreements Create Here</h3>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{$data?"Update":"Create"}} Agreements</h2>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>

                            @endif
                            <form action="{{ $data ? route('agreements.update') :  route('agreements.store') }}" method="POST">
                                @csrf

                                <input type="text" name="edit_id" value="{{$data?$data->id:"" }}" hidden>
                                <div class="form-group">
                                    <label for="name">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{$data? $data->title:''}}">
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="email">description</label>
                                            <textarea class="form-control" id="description" name="description"
                                               value="{{$data? $data->description:''}}"> </textarea>
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
