<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Category::all();
            return DataTables::of($data)
                ->addColumn('id', function ($row) {
                    return '<strong>' . $row->id . '</strong>';
                })
                ->addColumn('name', function ($row) {
                    return strlen($row->name) > 25 ? substr($row->name, 0, 25) . '..' : $row->name;
                })

                ->addColumn('status', function ($row) {
                    $data['id'] = $row->id;
                    $data['status'] = $row->status;
                    return View::make('admin.categorys.status', ['data'=>$data])->render();
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? Carbon::parse($row->created_at)->setTimezone('Asia/Kolkata')->toDateTimeString() : '';
                })
                ->addColumn('actions', function ($row) {
                    $data['id'] = $row->id;
                    return View::make('admin.categorys.actions', ['data' => $data])->render();
                })
                ->rawColumns(['id', 'actions', 'status', 'created_at', 'name'])
                ->make(true);
        } else {
            return view('admin.categorys.index');
        }
    }

    public function create()
    {
        return view('admin.categorys.create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $Category = new Category();
        $Category->name = $request['name'];
        $Category->created_by = Auth::user()->id;
        $Category->updated_by = Auth::user()->id;
        $Category->status = 1;
        $Category = $Category->save();
        if ($Category) {
            return redirect()->route('admin.categorys.index')->with('message', 'Category Added Sucssesfully..');
        } else {
            return redirect()->back()->with('error', 'Somthing Went Wrong..');
        }
    }

    public function view($id)
    {
        $Category = Category::find($id);
        if ($Category) {
            return view('admin.categorys.view', ['Category' => $Category]);
        } else {
            return redirect()->back()->with('error', 'Category Not Found..!');
        }
    }

    public function edit($id)
    {
        $Category = Category::find($id);
        if ($Category) {
            return view('admin.categorys.edit', ['Category' => $Category]);
        } else {
            return redirect()->back()->with('error', 'Category Not Found..!');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $Category = Category::find($request->id);
        if ($Category) {
            $Category->name = $request['name'];
            $Category->updated_by = Auth::user()->id;
            $Category = $Category->update();
            if ($Category) {
                return redirect()->route('admin.categorys.index')->with('message', 'Category Updated Sucssesfully..');
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong..');
            }
        } else {
            return redirect()->back()->with('error', 'Category Not Found..!');
        }
    }

    public function delete($id)
    {
        if ($id) {
            $Category = Category::find($id);
            $Category = $Category->delete();
            if ($Category) {
                return redirect()->route('admin.categorys.index')->with('message', 'Category Deleted Sucssesfully..');
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong..!');
            }
        } else {
            return redirect()->back()->with('error', 'Category Not Found..!');
        }
    }

    public function statusToggle(Request $request)
    {
        if ($request->id) {
            $Category = Category::find($request->id);
            $Category->status = $request->status;
            $Category = $Category->update();
            if ($Category) {
                return response()->json(['success' => 'Status Updated successfully.']);
            } else {
                return response()->json(['error' => 'Somthing Went Wrong..!']);
            }
        } else {
            return response()->json(['error' => 'Category Not Found..!']);
        }
    }
}
