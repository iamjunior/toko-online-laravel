@extends('layouts.global')

@section('title') Edit Category @endsection

@section('content')
    <div class="col-md-8">
        
        @if(session('status'))
            <div class="alert alert-success">
                {{session('status')}}
            </div>
        @endif

        <form action="{{route('categories.update', [$category->id])}}" enctype="multipart/form-data" method="POST" class="bg-white shadow-sm p-3" >
            @csrf
            <input type="hidden" value="PUT" name="_method">
            
            <label>Category Name</label>
            <input type="text" class="form-control" value="{{$category->name}}" name="name">
            <br/><br/>

            <label>Category Slug</label>
            <input type="text" class="form-control" value="{{$category->slug}}" name="slug">
            <br/><br/>

            @if($category->image)
                <span>Current image</span><br>
                <img src="{{asset('storage/'. $category->image)}}" width="120px">
                <br><br>
            @endif

            <input type="file" class="form-control" name="image">
            <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar</small>
            <br><br>
            
            <input type="submit" class="btn btn-primary" value="Update">
            <a class="btn btn-warning" href="{{route('categories.index')}}">Back</a>
        </form>
    </div>
@endsection