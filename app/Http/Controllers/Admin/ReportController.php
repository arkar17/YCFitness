<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Pusher\Pusher;
use App\Models\Post;
use App\Models\Action;
use App\Models\Report;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\New_;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        $report_posts = Post::where('report_status','=',0)->with('reports')->with('user')->get();

        //$report_posts=DB::table('posts')->get();
        return view('admin.socialmedia_report.index', compact('report_posts'));
    }

    public function ssd()
    {
        $report_counts= DB::select('SELECT count(reports.post_id)as report_count,post_id FROM `reports` WHERE reports.status=0 group by reports.post_id;');
        $reports=Report::where('status',0)->orderBy('created_at','DESC')->get();

        foreach($reports as $key=>$value){
            foreach($report_counts as $rp_count){
                if($value->post_id==$rp_count->post_id){
                    $reports[$key]['rp_count']=$rp_count->report_count;
                }
            }
        }

        return Datatables::of($reports)
        ->editColumn('created_at', function ($each) {
            return $each->created_at->format('d M Y , g:i A'); // human readable format
          })
        ->addIndexColumn()
        ->addColumn('action', function ($each) {
            $view_icon = '';
            $delete_icon = '';

            $view_icon ='<a href=" ' . route('admin.view.report', $each->id) . ' " class="btn btn-primary" title="view">
                            <i class="fa fa-folder-open" data-id="' . $each->id . '"></i>&nbsp;&nbsp;View
                        </a>';
            // $delete_icon = '<a href=" ' . route('admin.accept.report', $each->id) . ' " class="btn btn-danger" id="delete" title="delete">
            //             <i class="fa fa-ban" data-id="' . $each->id . '"></i>&nbsp;&nbsp;Ban
            //         </a>';

                        return '<div class="d-flex justify-content-center">' . $view_icon . '</div>';
                    })
        ->rawColumns(['action',''])
        ->make(true);

    }

    public function delete_report($id)
    {
        dd('delete');
        $report = Report::findOrFail($id);
        $report->delete();
        return redirect()->back();
    }

    public function action_ssd()
    {
        $reports=Report::where('status',1)->get();

        return Datatables::of($reports)
        ->editColumn('created_at', function ($each) {
            return $each->created_at->format('d M Y , g:i A'); // human readable format
          })
        ->addIndexColumn()
        ->addColumn('action', function ($each) {
            $view_icon = '';
            $delete_icon = '';

            // $view_icon = '<a href=" ' . route('admin.view.report', $each->id) . ' " class="btn btn-primary" title="view">
            //             <i class="fa fa-folder-open" data-id="' . $each->id . '"></i>&nbsp;&nbsp;View
            //         </a>';
            $delete_icon = '<form action="' . route('report.destroy', $each->id) . ' " method="DELETE">
                                <button class="btn btn-danger" type="submit">
                                    <i class="fa fa-trash" data-id="' . $each->id . '"></i>&nbsp;&nbsp;Delete
                                </button>
                            </form>';

                        return '<div class="d-flex justify-content-center">' . $delete_icon . '</div>';
                    })
        ->rawColumns(['action',''])
        ->make(true);
    }

    public function view_post(Request $request,$id)
    {
        $noti =  DB::table('notifications')->where('report_id',$id)->update(['notification_status' => 2]);

        $report=Report::findOrFail($id);
        $report_post=DB::table('reports')
                                ->where('post_id',$report->post_id)
                                ->leftjoin('posts','posts.id','reports.post_id')
                                ->where('posts.report_status',0)
                                ->get();
        return view('admin.socialmedia_report.view_report',compact('report'));
    }

    public function accept_report($report_id)
    {
        $report=Report::findOrFail($report_id);
        if($report->post_id != null){
            $post=Post::findOrFail($report->post_id);
            $post_id=$post->id;
            $post_owner=$post->user_id;
            $admin_id=  auth()->user()->id;
            $rp_posts=Report::where('post_id',$post_id)->get();
            foreach($rp_posts as $rp_post){
                $rp_post->action_message='delete post';
                $rp_post->status=1;
                $rp_post->update();
        }
        $post->report_status=1;
        $post->update();
        }
       elseif($report->comment_id != null){
         $comment=Comment::findOrFail($report->comment_id);
         $comment_id=$comment->id;
         $comment_owner=$comment->user_id;
         $admin_id=  auth()->user()->id;
            $rp_posts=Report::where('comment_id',$comment_id)->get();
            foreach($rp_posts as $rp_post){
                $rp_post->action_message='delete post';
                $rp_post->status=1;
                $rp_post->update();
        }
        $comment->report_status=1;
        $comment->update();
       }
        $options = array(
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
            );
            $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
            );

                $data = 'Removed';

                $description='Against Our Community And Guidelines';
                $post_rp = new Notification();
                $post_rp->description = $description;
                $post_rp->date = Carbon::Now()->toDateTimeString();

                $post_rp->sender_id = $admin_id;
                $post_rp->receiver_id = $post_owner;
                $post_rp->notification_status = $admin_id;
                $post_rp->report_id=$report_id;
                $post_rp->save();

                $pusher->trigger('friend_request.'.$post_owner , 'friendRequest', $data);

        return response()->json([
            'success' => 'Deleted',
        ]);
    }

    public function decline_report($report_id)
    {
        $report=Report::findOrFail($report_id);
        $post=Post::findOrFail($report->post_id);
        $post_id=$post->id;

        $rp_posts=Report::where('post_id',$post_id)->get();

        foreach($rp_posts as $rp_post){
            $rp_post->status=2;
            $rp_post->update();
        }

        return response()->json([
            'success' => 'Reported Post is Decline',
        ]);
    }

    public function create()
    {
        //
    }


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
        dd('edit');
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
        dd('delete');
    }
}
