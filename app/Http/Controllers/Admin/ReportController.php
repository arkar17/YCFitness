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
use App\Models\UserReactPost;
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
        if($report->post_id != null){
            $report_post = DB::table('reports')->select('reports.*', 'reports.id as report_id', 'profiles.profile_image', 'users.name', 'posts.*')
                ->where('post_id', $report->post_id)
                ->where('reports.id', $report->id)
            ->leftjoin('posts','posts.id','reports.post_id')
            ->where('posts.report_status',0)
            ->leftJoin('users', 'users.id', 'posts.user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
                ->where('posts.deleted_at', null)
            ->first();
            // dd($report_post);
            $liked_post = UserReactPost::select('posts.*')->leftJoin('posts', 'posts.id', 'user_react_posts.post_id')->get();

            $liked_post_count = DB::select("SELECT COUNT(post_id) as like_count, post_id FROM user_react_posts GROUP BY post_id");
            $comment_post_count =  DB::table('comments')
            ->select('post_id', DB::raw('count(*) as comment_count'))
            ->where('report_status',0)
            ->where('deleted_at',null)
            ->groupBy('post_id')
            ->get();
            if($report_post){
            foreach ($report_post as $key => $value) {
            $report_post->like_count = 0;
            $report_post->comment_count= 0;
            foreach ($liked_post_count as $like_count) {
            if ($like_count->post_id === $report_post->id) {
                $report_post->like_count = $like_count->like_count;
                break;
            } else {
                $report_post->like_count = 0;
            }
            }
            foreach ($comment_post_count as $comment_count) {
            if ($comment_count->post_id === $report_post->id) {
                $report_post->comment_count = $comment_count->comment_count;
                break;
            } else {
                $report_post->comment_count = 0;
            }
            }
            }
            }
           
        }
        else{
            $report_post=DB::table('reports')
            ->select('reports.*','reports.id as report_id','reports.id as report_id','profiles.profile_image','users.name','comments.*')
            ->where('comments.id',$report->comment_id)
                ->where('reports.id', $report->id)
            ->leftjoin('comments','comments.id','reports.comment_id')
            ->leftJoin('users', 'users.id', 'comments.user_id')
            ->leftJoin('profiles', 'users.profile_id', 'profiles.id')
            ->first();
            if ($report_post) {
                foreach ($report_post as $key => $value) {
                    $report_post->post_id = null;
                }
            }
            
        }
    //    dd($report_post);
        return view('admin.socialmedia_report.view_report',compact('report_post'));
    }

    public function accept_report($report_id)
    {
        $report=Report::findOrFail($report_id);
        //dd($report);
        if($report->post_id != null or $report->post_id != 0){
            //sdd("post");
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
       elseif($report->comment_id != null or $report->comment != 0){
        // dd("comment");
         $comment=Comment::findOrFail($report->comment_id);
         $comment_id=$comment->id;
         $post_owner=$comment->user_id;
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
       $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        $options = array(
            'cluster' => 'eu',
            'encrypted' => true
        )
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

        $notification = Notification::select(
            'users.id as user_id',
            'users.name',
            'notifications.*',
            'notifications.post_id as post',
            'profiles.profile_image'
        )
            ->leftJoin('users', 'notifications.sender_id', '=', 'users.id')
            ->leftJoin(
                'profiles',
                'profiles.id',
                'users.profile_id'
            )
            ->where('notifications.id', $post_rp->id)
            ->first();
        $pusher->trigger('friend_request.' . $post_owner, 'friendRequest', $notification);

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
