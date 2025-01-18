<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Blog;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class CommentController extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Comment::all();
            return DataTables::of($data)
                ->addColumn('id', function ($row) {
                    return '<strong>' . $row->id . '</strong>';
                })
                ->addColumn('name', function ($row) {
                    return strlen($row->name) > 25 ? substr($row->name, 0, 25) . '..' : $row->name;
                })
                ->addColumn('email', function ($row) {
                    return strlen($row->email) > 25 ? substr($row->email, 0, 25) . '..' : $row->email;
                })
                ->addColumn('blog', function ($row) {
                    return isset($row->blog) && $row->blog && strlen($row->blog->title) > 25 ? '<a href="' . route('admin.comments.index.blog', ['id' => $row->blog->id]) . '">' . substr($row->blog->title, 0, 25) . '..</a>' : '<a href="' . route('admin.comments.index.blog', ['id' => $row->blog->id]) . '">' . $row->blog->title . '</a>';
                })
                ->addColumn('status', function ($row) {
                    $data['id'] = $row->id;
                    $data['status'] = $row->status;
                    return View::make('admin.comments.status', ['data'=>$data])->render();
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? Carbon::parse($row->created_at)->setTimezone('Asia/Kolkata')->toDateTimeString() : '';
                })
                ->addColumn('actions', function ($row) {
                    $data['id'] = $row->id;
                    return View::make('admin.comments.actions', ['data' => $data])->render();
                })
                ->rawColumns(['id', 'actions', 'status', 'created_at', 'name', 'email','blog'])
                ->make(true);
        } else {
            return view('admin.comments.index');
        }
    }

    public function indexBlog(Request $request, $id)
    {
        if ($id) {

            if ($request->ajax()) {
                $draw = $request->get('draw');
                $start = $request->get("start");
                $rowperpage = $request->get("length") ?? 10;

                $columnIndex_arr = $request->get('order');
                $columnName_arr = $request->get('columns');
                $order_arr = $request->get('order');
                $search_arr = $request->get('search');

                $columnIndex = $columnIndex_arr[0]['column'] ?? '0'; // Column index
                $columnName = $columnName_arr[$columnIndex]['data']; // Column name
                $columnSortOrder = $order_arr[0]['dir'] ?? 'desc'; // asc or desc
                $searchValue = $search_arr['value']; // Search value

                // Total records
                $totalRecords = Comment::where('blog_id', $id)->select('count(*) as allcount')->count();
                $totalRecordswithFilter =
                    Comment::select('count(*) as allcount')
                    ->where('blog_id', $id)
                    ->where(function ($query) use ($searchValue) {
                        $query->where('id', 'like', '%' . $searchValue . '%')
                            ->orWhere('name', 'like', '%' . $searchValue . '%')
                            ->orWhere('email', 'like', '%' . $searchValue . '%')
                            ->orWhere('status', 'like', '%' . $searchValue . '%')
                            ->orWhere('created_at', 'like', '%' . $searchValue . '%');
                    })
                    ->count();

                // Get records, also we have included search filter as well
                $records = Comment::where('blog_id', $id)
                    ->where(function ($query)  use ($searchValue) {
                        $query->where('id', 'like', '%' . $searchValue . '%')
                            ->orWhere('name', 'like', '%' . $searchValue . '%')
                            ->orWhere('email', 'like', '%' . $searchValue . '%')
                            ->orWhere('status', 'like', '%' . $searchValue . '%')
                            ->orWhere('created_at', 'like', '%' . $searchValue . '%');
                    })

                    ->orderBy($columnName, $columnSortOrder)
                    ->select('*')
                    ->skip($start)
                    ->take($rowperpage)
                    ->get();

                $data_arr = array();

                foreach ($records as $row) {
                    $html = '<a href="' . route("admin.comments.view", $row->id) . '"> <button type="button"
                            class="btn btn-icon btn-outline-info">
                            <i class="bx bx-show"></i>
                        </button></a>
                    <a href="' . route("admin.comments.edit", $row->id) . '"> <button type="button"
                            class="btn btn-icon btn-outline-warning">
                            <i class="bx bxs-edit"></i>
                        </button></a>

                    <button type="button" class="btn btn-icon btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#delete-modal-' . $row->id . '">
                        <i class="bx bx-trash-alt"></i>
                    </button>
                    <div class="modal fade" id="delete-modal-' . $row->id . '"
                        tabindex="-1" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                        <form action="' . route("admin.comments.delete", $row->id) . '"
                            method="post">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalCenterTitle">Delete Item
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                        <h3>Do You Want To Really Delete This Item?</h3>
                                        ' . csrf_field() . '
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                    </div>
                                    </form>
                            </div>
                        </div>
                    </div>';
                    $data_arr[] = array(
                        "id" => '<strong>' . $row->id . '</strong>',
                        "name" => $row->name,
                        "email" => $row->email,
                        "blog" => isset($row->blog) && $row->blog && strlen($row->blog->title) > 25 ? '<a href="' . route('admin.blogs.view', ['id' => $row->blog->id]) . '">' . substr($row->blog->title, 0, 25) . '..</a>' : '<a href="' . route('admin.blogs.view', ['id' => $row->blog->id]) . '">' . $row->blog->title . '</a>',
                        "status" => ' <div class="d-flex justify-content-center align-items-center form-check form-switch"><input data-id="' . $row->id . '" style="width: 60px;height: 25px;" class="form-check-input status-toggle" type="checkbox" id="flexSwitchCheckDefault" ' . ($row->status ? "checked" : "") . '  ></div>',
                        "created_at" => $row->created_at ? Carbon::parse($row->created_at)->setTimezone('Asia/Kolkata')->toDateTimeString() : '',
                        "actions" => $html,
                    );
                }

                $response = array(
                    "draw" => intval($draw),
                    "iTotalRecords" => $totalRecords,
                    "iTotalDisplayRecords" => $totalRecordswithFilter,
                    "aaData" => $data_arr,
                );

                echo json_encode($response);
            } else {
                return view('admin.comments.index-blog', ['id' => $id]);
            }
        } else {
            return redirect()->back()->with('error', 'Somthing Went Wrong..');
        }
    }

    public function create()
    {
        $blogs = Blog::where('status', 1)->get();
        if($blogs->isEmpty()){
            return redirect()->route('admin.blogs.create')->with('message', 'Please Create At Least One Blog..');
        }
        return view('admin.comments.create', ['blogs' => $blogs]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|max:40',
            'email' => 'required|email',
            'comment' => 'required',
            'blog' => 'required|exists:blogs,id',
            'image' => 'file|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);

        $Comment = new Comment();
        $Comment->name = $request['name'];
        $Comment->email = $request['email'];
        $Comment->comment = $request['comment'];
        $Comment->blog_id = $request['blog'];
        $Comment->parent_id = isset($request['parent_id']) && $request['parent_id'] ? $request['parent_id'] : null;
        if ($request->image) {
            if ($request->old_image && file_exists(public_path($request->old_image))) {
                unlink(public_path($request->old_image));
            }
            $folderPath = public_path('custom-assets/upload/admin/images/comments/images/');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
            $file = $request->file('image');
            $imageoriginalname = str_replace(" ", "-", $file->getClientOriginalName());
            $imageName = rand(1000, 9999) . time() . $imageoriginalname;
            $file->move($folderPath, $imageName);
            $Comment->image = 'custom-assets/upload/admin/images/comments/images/' . $imageName;
        }
        $Comment->save();
        if ($Comment) {
            return redirect()->route('admin.comments.index')->with('message', 'Comment Added Sucssesfully..');
        } else {
            return redirect()->back()->with('error', 'Somthing Went Wrong..');
        }
    }

    public function view($id)
    {
        $Comment = Comment::find($id);
        if ($Comment) {
            return view('admin.comments.view', ['Comment' => $Comment]);
        } else {
            return redirect()->back()->with('error', 'Comment Not Found..!');
        }
    }

    public function edit($id)
    {
        $Comment = Comment::find($id);
        if ($Comment) {
            $blogs = Blog::where('status', 1)->get();
            if($blogs->isEmpty()){
                return redirect()->route('admin.blogs.create')->with('message', 'Please Create At Least One Blog..');
            }
            return view('admin.comments.edit', ['Comment' => $Comment, 'blogs' => $blogs]);
        } else {
            return redirect()->back()->with('error', 'Comment Not Found..!');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:40',
            'email' => 'required|email',
            'comment' => 'required',
            'blog' => 'required|exists:blogs,id',
            'image' => 'file|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);

        $Comment = Comment::find($request->id);
        if ($Comment) {
            $Comment->name = $request['name'];
            $Comment->email = $request['email'];
            $Comment->comment = $request['comment'];
            $Comment->blog_id = $request['blog'];
            $Comment->parent_id = isset($request['parent_id']) && $request['parent_id'] ? $request['parent_id'] : null;
            if ($request->image) {
                if ($request->old_image && file_exists(public_path($request->old_image))) {
                    unlink(public_path($request->old_image));
                }
                $folderPath = public_path('custom-assets/upload/admin/images/comments/images/');
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                }
                $file = $request->file('image');
                $imageoriginalname = str_replace(" ", "-", $file->getClientOriginalName());
                $imageName = rand(1000, 9999) . time() . $imageoriginalname;
                $file->move($folderPath, $imageName);
                $Comment->image = 'custom-assets/upload/admin/images/comments/images/' . $imageName;

                if ($request->old_image && file_exists(public_path($request->old_image))) {
                    unlink(public_path($request->old_image));
                }
            }
            $Comment = $Comment->update();
            if ($Comment) {
                return redirect()->route('admin.comments.index')->with('message', 'Comment Updated Sucssesfully..');
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong..');
            }
        } else {
            return redirect()->back()->with('error', 'Comment Not Found..!');
        }
    }

    public function delete($id)
    {
        if ($id) {
            $Comment = Comment::find($id);
            if ($Comment->image && file_exists(public_path($Comment->image))) {
                unlink(public_path($Comment->image));
            }
            $Comment = $Comment->delete();
            if ($Comment) {
                return redirect()->route('admin.comments.index')->with('message', 'Comment Deleted Sucssesfully..');
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong..!');
            }
        } else {
            return redirect()->back()->with('error', 'Comment Not Found..!');
        }
    }


    public function statusToggle(Request $request)
    {
        if ($request->id) {
            $Comment = Comment::find($request->id);
            $Comment->status = $request->status;
            $Comment = $Comment->update();
            if ($Comment) {
                return response()->json(['success' => 'Status Updated successfully.']);
            } else {
                return response()->json(['error' => 'Somthing Went Wrong..!']);
            }
        } else {
            return response()->json(['error' => 'Comment Not Found..!']);
        }
    }
}
