<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = \App\Category::paginate(10);

        $filterKeyword = $request->get('name');
        if($filterKeyword){
            $categories = \App\Category::where("name", "LIKE", "%$filterKeyword%")->paginate(10);
        }

        return view('categories.index',['categories' => $categories]);
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $name = $request->get('name');

        $new_category = new \App\Category;
        $new_category->name = $name;

        if($request->file('image')){
            $image_path = $request->file('image')->store('category_images','public');

            $new_category->image = $image_path;
        }

        $new_category->created_by   = \Auth::user()->id;
        $new_category->slug         = \Str::slug($name,'-');
        $new_category->save();

        return redirect()->route('categories.create')->with('status','Category Successfully Created');
    }

    public function show($id)
    {
        $category = \App\Category::findOrFail($id);

        return view('categories.show', ['category' => $category]);
    }

    public function edit($id)
    {
        $category_to_edit = \App\Category::findOrFail($id);

        return view('categories.edit',['category' => $category_to_edit]);
    }

    public function update(Request $request, $id)
    {
        $name = $request->get('name');
        $slug = $request->get('slug');

        $category = \App\Category::findOrFail($id);
        
        $category->name = $name;
        $category->slug = $slug;

        //mengecek file gambar, kalau tersedia hapus dahulu kemudian baru lakukan update file baru 
        if($request->file('image')){
            if($category->image && file_exists(storage_path('app/public/'.$category->image))){
                \Storage::delete('public/'. $category->name);
            }

            $new_image = $request->file('image')->store('category_images','public');
            $category->image = $new_image;
        }
        $category->updated_by   = \Auth::user()->id;
        $category->slug         = \Str::slug($name);
        $category->save();
        return redirect()->route('categories.edit',[$id])->with('status','Category Successfully Edit');
    }

    public function destroy($id)
    {
        $category = \App\Category::findOrFail($id);

        $category->delete();

        return redirect()->route('categories.index')
        ->with('status','Category Successfully Moved to trash');
    }

    public function trash(){
        $deleted_category = \App\Category::onlyTrashed()->paginate(10);

        return view('categories.trash',['categories' => $deleted_category]);
    }

    public function restore($id){
        $category = \App\Category::withTrashed()->findOrFail($id);

        if($category->trashed()){
            $category->restore();
        }else{
            return redirect()->route('categories.index')->with('status','Category Successfully Restored');
        }
    }

    public function deletePermanent($id){
        $category = \App\Category::withTrashed()->findOrFail($id);

        if(!$category->trashed()){
            return redirect()->route('categories.index')->with('status','Can not delete permanen active category ');
        }else{
            $category->forceDelete();

            return redirect()->route('categories.index')->with('status', 'category Permanently deleted');
        }
    }
}
