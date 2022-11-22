<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photos = Photo::orderBy('id','asc')
            ->paginate(5);
        return view('admin.photo.index' , compact(var_name: 'photos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.photo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'alt_text' => 'required',
            'main_photo' => 'required'
        ]);

        if ($request->hasFile('main_photo')) {

        $file = $request->main_photo;

        $file_name = $file->getClientOriginalName();

        if (!file_exists(public_path('images')))
            @mkdir(public_path('images'));


        $file->move(public_path('images'), $file_name);

        $request->merge([
            'photo' => $file_name
        ]);
        }
        Photo::create($request->post());
        return redirect()->route('photo.index')->with('success','Photo has been created sucessfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=[];
        $data ['row'] = Photo::find($id);
        return view('admin.photo.edit' , compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        $request->validate([
            'alt_text' => 'required',
            'main_photo' => 'required'
        ]);

        if ($request->hasFile('main_photo')) {

            $file = $request->main_photo;

            $file_name = $file->getClientOriginalName();

            if (!file_exists(public_path('images')))
                @mkdir(public_path('images'));


            $file->move(public_path('images'), $file_name);

            $request->merge([
                'photo' => $file_name
            ]);

            if(file_exists(public_path('images/'.$photo->photo))){
                @unlink(public_path('images/'.$photo->photo));
            }
        }

        $photo->fill($request->all())
            ->save();

        return redirect()->route('photo.index')->with('success','Photo has been Updated sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        if(file_exists(public_path('images/'.$photo->photo))){
            @unlink(public_path('images/'.$photo->photo));
        }

        $photo->delete();
        return redirect()->route('photo.index')->with('sucess','Photo has been deleted sucessfully');
    }
}
