<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
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
        $teachers = Teacher::orderBy('id','asc')
            ->paginate(5);
        return view('admin.teacher.index' , compact(var_name: 'teachers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.teacher.create');
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
            'name'=> 'required',
            'main_photo'=> 'required',
            'email'=> 'required',
            'subject'=> 'required',
            'address'=> 'required'
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

        Teacher::create($request->post());
        return redirect()->route('teacher.index')->with('success','Teacher has been created sucessfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=[];
        $data['row']= Teacher::find($id);
        return view('admin.teacher.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => 'required',
            'main_photo' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'address' => 'required',
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

            if(file_exists(public_path('images/'.$teacher->photo))){
                @unlink(public_path('images/'.$teacher->photo));
            }
        }

        $teacher->fill($request->all())->save();

        return redirect()->route('teacher.index')->with('success', 'Teacher has been updated sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        if(file_exists(public_path('images/'.$teacher->photo))){
            @unlink(public_path('images/'.$teacher->photo));
        }

        $teacher->delete();
        return redirect()->route('teacher.index')->with('sucess','Teacher has been Deleted sucessfully');
    }
}
