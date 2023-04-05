<?php

namespace App\Http\Controllers\Admin;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        //
        
        
            // dd($feedback);
        return view('admin.feedback');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function getFeedback() {
        $feedback = DB::table('feedback')->select('feedback.*','users.id as user_id','users.name','profiles.profile_image')
        ->leftJoin('users','feedback.user_id','users.id')
        ->leftJoin('profiles','profiles.id','users.profile_id')
        ->get();
        // dd($feedback);
            foreach($feedback as $key=>$value){
            $roles = DB::select("SELECT roles.name,model_has_roles.model_id FROM model_has_roles 
            left join roles on model_has_roles.role_id = roles.id where model_has_roles.model_id = $value->user_id");

            if(!empty($roles)){
            foreach($roles as $r){
                if($r->model_id == $value->user_id){
                $feedback[$key]->roles = $r->name;  
                break;
                }
                else{
                    $feedback[$key]->roles = null;
                }
            }
            }
            else{
                $feedback[$key]->roles = null;
            }
            }
           // dd($feedback);
        return Datatables::of($feedback)
        ->addIndexColumn()
        // ->addColumn('action', function ($each) {
        //     $edit_icon = '';
        //     $delete_icon = '';

        //     $edit_icon = '<a href=" ' . route('feedback.edit', $each->id) . ' " class="text-success mx-1 " title="edit">
        //     <i class="fa-solid fa-edit fa-xl" data-id="' . $each->id . '"></i>
        // </a>';
        //     $delete_icon = '<a href=" ' . route('feedback.destroy', $each->id) . ' " class="text-danger mx-1" id="delete" title="delete">
        //     <i class="fa-solid fa-trash fa-xl delete-btn" data-id="' . $each->id . '"></i>
        // </a>';

        //     return '<div class="d-flex justify-content-center">' . $edit_icon . $delete_icon. '</div>';
        // })
        ->make(true);
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
    }
}
