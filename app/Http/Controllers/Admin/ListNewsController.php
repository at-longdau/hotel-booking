<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\News;
use App\Http\Requests\CreateNewsRequest;
use Session;

class ListNewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::with('category')->paginate(10);
        return view('backend.news.index', compact('news'));
    }

    /**
     * Create a new News.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.news.create');
    }

    /**
     * Store a newly News in storage.
     *
     * @param \Illuminate\Http\CreateNewsRequest $request of form creat News
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateNewsRequest $request)
    {
        $news = new News($request->all());
        $result = $news->save();
        if ($result) {
            Session::flash('successCreate', 'Create success!');
            return redirect()->route('news.index');
        } else {
            Session::flash('failSave', 'Create fail!');
            return redirect()->route('news.index');
        }
    }
}
