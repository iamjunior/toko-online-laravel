<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookController extends Controller
{
    
    public function index()
    {
        //
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $new_book = new \App\Book;
        $new_book->title        = $request->get('title');
        $new_book->description  = $request->get('description');
        $new_book->author       = $request->get('author');
        $new_book->publisher    = $request->get('publisher');
        $new_book->price        = $request->get('price');
        $new_book->stock        = $request->get('stock');
        
        $new_book->status       = $request->get('save_action');

        $cover = $request->file('cover');

        if($cover){
            $cover_patch        = $cover->store('book-covers','public');
            $new_book->cover    = $cover_patch;
        }

        $new_book->slug = \Str::slug($request->get('title'));
        $new_book->created_by = \Auth::user()->id;
        $new_book->save();

        $new_book->categories()->attach($request->get('categories'));

        if($request->get('save_action') == 'PUBLISH'){
            return redirect()
                ->route('books.create')
                ->with('status','Book Successfully saved and published');
        }else{
            return redirect()
            ->route('books.create')
            ->with('status','Book saved as draft');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
