<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class TagController extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Tag::all();
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
                    return View::make('admin.tags.status', ['data'=>$data])->render();
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? Carbon::parse($row->created_at)->setTimezone('Asia/Kolkata')->toDateTimeString() : '';
                })
                ->addColumn('actions', function ($row) {
                    $data['id'] = $row->id;
                    return View::make('admin.tags.actions', ['data' => $data])->render();
                })
                ->rawColumns(['id', 'actions', 'status', 'created_at', 'name'])
                ->make(true);
        } else {
            return view('admin.tags.index');
        }
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $Tag = new Tag();
        $Tag->name = $request['name'];
        $Tag->created_by = Auth::user()->id;

        $Tag->status = 1;
        $Tag = $Tag->save();
        if ($Tag) {
            return redirect()->route('admin.tags.index')->with('message', 'Tag Added Sucssesfully..');
        } else {
            return redirect()->back()->with('error', 'Somthing Went Wrong..');
        }
    }

    public function view($id)
    {
        $Tag = Tag::find($id);
        if ($Tag) {
            return view('admin.tags.view', ['Tag' => $Tag]);
        } else {
            return redirect()->back()->with('error', 'Tag Not Found..!');
        }
    }

    public function edit($id)
    {
        $Tag = Tag::find($id);
        if ($Tag) {
            return view('admin.tags.edit', ['Tag' => $Tag]);
        } else {
            return redirect()->back()->with('error', 'Tag Not Found..!');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $Tag = Tag::find($request->id);
        if ($Tag) {
            $Tag->name = $request['name'];
            $Tag->updated_by = Auth::user()->id;
            $Tag = $Tag->update();
            if ($Tag) {
                return redirect()->route('admin.tags.index')->with('message', 'Tag Updated Sucssesfully..');
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong..');
            }
        } else {
            return redirect()->back()->with('error', 'Tag Not Found..!');
        }
    }

    public function delete($id)
    {
        if ($id) {
            $Tag = Tag::find($id);
            $Tag = $Tag->delete();
            if ($Tag) {
                return redirect()->route('admin.tags.index')->with('message', 'Tag Deleted Sucssesfully..');
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong..!');
            }
        } else {
            return redirect()->back()->with('error', 'Tag Not Found..!');
        }
    }

    public function statusToggle(Request $request)
    {
        if ($request->id) {
            $Tag = Tag::find($request->id);
            $Tag->status = $request->status;
            $Tag = $Tag->update();
            if ($Tag) {
                return response()->json(['success' => 'Status Updated successfully.']);
            } else {
                return response()->json(['error' => 'Somthing Went Wrong..!']);
            }
        } else {
            return response()->json(['error' => 'Tag Not Found..!']);
        }
    }
}
