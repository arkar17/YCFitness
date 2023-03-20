<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FreeVideo;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Storage;

class FreeVideosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        //
        return view('admin.freevideo.index');
    }

    public function getVideos() {
        $video = FreeVideo::get();
        return Datatables::of($video)
        ->addIndexColumn()
        ->addColumn('action', function ($each) {
            $edit_icon = '';
            $delete_icon = '';

            $edit_icon = '<a href=" ' . route('free_video.edit', $each->id) . ' " class="text-success mx-1 " title="edit">
            <i class="fa-solid fa-edit fa-xl" data-id="' . $each->id . '"></i>
        </a>';
            $delete_icon = '<a href=" ' . route('free_video.destroy', $each->id) . ' " class="text-danger mx-1" id="delete" title="delete">
            <i class="fa-solid fa-trash fa-xl delete-btn" data-id="' . $each->id . '"></i>
        </a>';

            return '<div class="d-flex justify-content-center">' . $edit_icon . $delete_icon. '</div>';
        })
        ->make(true);
            //    ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.freevideo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
       //dd($request);
        $video_create = new FreeVideo();
        if($request->hasFile('video')) {
            $video = $request->file('video');
            $video_name =uniqid().'_'. $video->getClientOriginalName();
            Storage::put(
                'public/free_video/'.$video_name,
                file_get_contents($video),'public'
            );
            //dd($video_name);
        }
        $video_create->name = $request->name;
        $video_create->video = $video_name;
        $video_create->save();
        return redirect()->route('free_video.index')->with('success', 'New Video is created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $data = FreeVideo::findOrFail($id);
        return view('admin.freevideo.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $data = FreeVideo::findOrFail($id);
        if($request->hasFile('video')) {
            $video = $request->file('video');
            $video_name =uniqid().'_'. $video->getClientOriginalName();
            Storage::put(
                'public/free_video/'.$video_name,
                file_get_contents($video)
            );
        }else{
            $video_name = $data->video;
        }
        $video_naming = $request->name;
        //dd($video_naming);
        $data->name = $video_naming;
        $data->video = $video_name;
        $data->update();
        return redirect()->route('free_video.index')->with('success', 'FreeVideo is updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $video_delete=FreeVideo::findOrFail($id);
        $video_delete->delete();
        return 'success';
    }
}
