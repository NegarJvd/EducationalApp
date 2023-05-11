<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    function __construct()
    {
        $permissions = [
            'comment-list',
            'comment-create',
            'comment-edit',
        ];

        foreach ($permissions as $permission) {
            $array = Permission::where('name', $permission)->get();
            if(count($array) == 0){
                Permission::create(['name' => $permission]);
            }
        }

        $this->middleware('permission:comment-list|comment-create|comment-edit');
        $this->middleware('permission:comment-list', ['only' => ['index','show']]);
        $this->middleware('permission:comment-create', ['only' => ['store']]);
        $this->middleware('permission:comment-edit', ['only' => ['update']]);
    }

    public function index(Request $request){
        $comments = Comment::whereNull('reply_id');

        //filter
        $product_id = $request->get('product_id');
        if (!is_null($product_id)){
            $comments = $comments->where('product_id', $product_id);
        }

        $status = $request->get('status');
        if (!is_null($status)){
            $comments = $comments->where('status', $status);
        }
        //endfilter

        $comments = $comments->orderBy('id','DESC')->paginate($this->perPagePanel);

        return view('panel.comments.index',compact('comments'));

    }

    public function show($id){
        $comment = Comment::whereNull('reply_id')->find($id);

        if(!$comment) return abort(404);

        return view('panel.comments.show',compact('comment'));
    }

    public function store(Request $request){
        $request->validate([
            'comment_id' => ['required', Rule::in(Comment::whereNull('reply_id')->pluck('id'))],
            'text' => ['required', 'string'],
            'status' => ['nullable', Rule::in(Comment::status_list())],
        ]);

        $comment = Comment::find($request->get('comment_id'));
        if(!is_null($request->get('status'))){
            $comment->status = $request->get('status');
            $comment->save();
        }


        if($comment->replies()->count() > 0){
            $reply = $comment->replies()->first();
            $reply->update([
                'admin_id' => Auth::id(),
                'text' => $request->get('text'),
            ]);

        }else{
            Comment::create([
                'admin_id' => Auth::id(),
                'product_id' => $comment->product_id,
                'reply_id' => $comment->id,
                'text' => $request->get('text'),
                'status' => Comment::status_list()[2]
            ]);

        }

        return redirect()->back()->with('success', "پاسخ با موفقیت ذخیره شد.");
    }

    public function update(Request $request, $id){
        $status_list = Comment::status_list();
        unset($status_list[1]);

        $request->validate([
            'status' => ['required', Rule::in($status_list)],
        ]);

        $comment = Comment::find($id);

        if(!$comment) abort(404);

        $comment->status = $request->get('status');
        $comment->save();

        return redirect()->back()->with('success', "وضعیت با موفقیت تغییر یافت.");
    }
}
