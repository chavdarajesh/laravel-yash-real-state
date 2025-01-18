<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Blog::all();
            return DataTables::of($data)
                ->addColumn('id', function ($row) {
                    return '<strong>' . $row->id . '</strong>';
                })
                ->addColumn('title', function ($row) {
                    return strlen($row->title) > 25 ? substr($row->title, 0, 25) . '..' : $row->title;
                })
                ->addColumn('description', function ($row) {
                    return strlen($row->description) > 25 ? substr($row->description, 0, 25) . '..' : $row->description;
                })
                ->addColumn('status', function ($row) {
                    $data['id'] = $row->id;
                    $data['status'] = $row->status;
                    return View::make('admin.blogs.status', ['data' => $data])->render();
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? Carbon::parse($row->created_at)->setTimezone('Asia/Kolkata')->toDateTimeString() : '';
                })
                ->addColumn('actions', function ($row) {
                    $data['id'] = $row->id;
                    return View::make('admin.blogs.actions', ['data' => $data])->render();
                })
                ->rawColumns(['id', 'actions', 'status', 'created_at', 'title', 'description'])
                ->make(true);
        } else {
            return view('admin.blogs.index');
        }
    }
    public function create()
    {
        $tags = Tag::where('status', 1)->get();
        $categorys = Category::where('status', 1)->get();
        if ($categorys->isEmpty()) {
            return redirect()->route('admin.categorys.create')->with('message', 'Please Create At Least One Category..');
        }
        if ($tags->isEmpty()) {
            return redirect()->route('admin.tags.create')->with('message', 'Please Create At Least One Tag..');
        }
        return view('admin.blogs.create', ['categorys' => $categorys, 'tags' => $tags]);
    }
    public function save(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'file|image|mimes:jpeg,png,jpg,gif|max:5000',
            'category' => 'required|exists:categories,id',
            'tags' => 'required',
        ]);
        $Blog = new Blog();
        $Blog->title = $request['title'];
        $Blog->description = $request['description'];
        $Blog->author = Auth::user()->name;
        $Blog->user_id = Auth::user()->id;
        $Blog->published_date = $request['published_date'] ? $request['published_date'] : date('Y-m-d');
        $Blog->category_id = $request['category'];
        $Blog->status = 1;
        $Blog->created_by = Auth::user()->id;
        $Blog->updated_by = Auth::user()->id;
        if ($request->image) {
            if ($request->old_image && file_exists(public_path($request->old_image))) {
                unlink(public_path($request->old_image));
            }
            $folderPath = public_path('custom-assets/upload/admin/images/blogs/images/');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
            $file = $request->file('image');
            $imageoriginalname = str_replace(" ", "-", $file->getClientOriginalName());
            $imageName = rand(1000, 9999) . time() . $imageoriginalname;
            $file->move($folderPath, $imageName);
            $Blog->image = 'custom-assets/upload/admin/images/blogs/images/' . $imageName;
        }
        $Blog->save();
        $tags = $request->input('tags');
        $Blog->tags()->attach($tags);
        if ($Blog) {
            return redirect()->route('admin.blogs.index')->with('message', 'Blog Added Sucssesfully..');
        } else {
            return redirect()->back()->with('error', 'Somthing Went Wrong..');
        }
    }
    public function view($id)
    {
        $Blog = Blog::find($id);
        if ($Blog) {
            return view('admin.blogs.view', ['Blog' => $Blog]);
        } else {
            return redirect()->back()->with('error', 'Blog Not Found..!');
        }
    }

    public function edit($id)
    {
        $Blog = Blog::find($id);
        if ($Blog) {
            $tags = Tag::where('status', 1)->get();
            $categorys = Category::where('status', 1)->get();
            $users = User::where('status', 1)->where('is_verified', 1)->get();
            if ($categorys->isEmpty()) {
                return redirect()->route('admin.categorys.create')->with('message', 'Please Create At Least One Category..');
            }
            if ($tags->isEmpty()) {
                return redirect()->route('admin.tags.create')->with('message', 'Please Create At Least One Tag..');
            }
            return view('admin.blogs.edit', ['Blog' => $Blog, 'categorys' => $categorys, 'tags' => $tags, 'users' => $users]);
        } else {
            return redirect()->back()->with('error', 'Blog Not Found..!');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'file|image|mimes:jpeg,png,jpg,gif|max:5000',
            'author' => 'required|exists:users,id,status,1,is_verified,1',
            'category' => 'required|exists:categories,id',
            'tags' => 'required',
        ]);
        $Blog = Blog::find($request->id);
        if ($Blog) {
            $Blog->title = $request['title'];
            $Blog->description = $request['description'];
            $Blog->author = Auth::user()->name;
            $Blog->user_id = $request['author'];
            $Blog->published_date = $request['published_date'] ? $request['published_date'] : date('Y-m-d');
            $Blog->category_id = $request['category'];
            $Blog->updated_by = Auth::user()->id;
            if ($request->image) {
                $folderPath = public_path('custom-assets/upload/admin/images/blogs/images/');
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                }
                $file = $request->file('image');
                $imageoriginalname = str_replace(" ", "-", $file->getClientOriginalName());
                $imageName = rand(1000, 9999) . time() . $imageoriginalname;
                $file->move($folderPath, $imageName);
                $Blog->image = 'custom-assets/upload/admin/images/blogs/images/' . $imageName;
                if ($request->old_image && file_exists(public_path($request->old_image))) {
                    unlink(public_path($request->old_image));
                }
            }
            $Blog->update();

            $tags = $request->input('tags');
            $Blog->tags()->sync($tags);

            if ($Blog) {
                return redirect()->route('admin.blogs.index')->with('message', 'Blog Updated Sucssesfully..');
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong..');
            }
        } else {
            return redirect()->back()->with('error', 'Blog Not Found..!');
        }
    }

    public function delete($id)
    {
        if ($id) {
            $Blog = Blog::find($id);
            if ($Blog->image && file_exists(public_path($Blog->image))) {
                unlink(public_path($Blog->image));
            }
            $Blog = $Blog->delete();
            if ($Blog) {
                return redirect()->route('admin.blogs.index')->with('message', 'Blog Deleted Sucssesfully..');
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong..!');
            }
        } else {
            return redirect()->back()->with('error', 'Blog Not Found..!');
        }
    }

    public function statusToggle(Request $request)
    {
        if ($request->id) {
            $Blog = Blog::find($request->id);
            $Blog->status = $request->status;
            $Blog = $Blog->update();
            if ($Blog) {
                return response()->json(['success' => 'Status Updated Successfully.']);
            } else {
                return response()->json(['error' => 'Somthing Went Wrong..!']);
            }
        } else {
            return response()->json(['error' => 'Blog Not Found..!']);
        }
    }
}
