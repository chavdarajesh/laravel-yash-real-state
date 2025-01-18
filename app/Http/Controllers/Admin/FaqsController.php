<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faqs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class FaqsController extends Controller
{



    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Faqs::all();
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
                    return View::make('admin.faqs.status', ['data'=>$data])->render();
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? Carbon::parse($row->created_at)->setTimezone('Asia/Kolkata')->toDateTimeString() : '';
                })
                ->addColumn('actions', function ($row) {
                    $data['id'] = $row->id;
                    return View::make('admin.faqs.actions', ['data' => $data])->render();
                })
                ->rawColumns(['id', 'actions', 'status', 'created_at', 'title', 'description'])
                ->make(true);
        } else {
            return view('admin.faqs.index');
        }
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function save(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $Faqs = new Faqs();
        $Faqs->title = $request['title'];
        $Faqs->description = $request['description'];
        $Faqs->status = 1;
        $Faqs->created_by = Auth::user()->id;
        $Faqs->updated_by = Auth::user()->id;
        $Faqs = $Faqs->save();
        if ($Faqs) {
            return redirect()->route('admin.faqs.index')->with('message', 'Faq Added Sucssesfully..');
        } else {
            return redirect()->back()->with('error', 'Somthing Went Wrong..');
        }
    }

    public function view($id)
    {
        $Faq = Faqs::find($id);
        if ($Faq) {
            return view('admin.faqs.view', ['Faq' => $Faq]);
        } else {
            return redirect()->back()->with('error', 'Faq Not Found..!');
        }
    }

    public function edit($id)
    {
        $Faq = Faqs::find($id);
        if ($Faq) {
            return view('admin.faqs.edit', ['Faq' => $Faq]);
        } else {
            return redirect()->back()->with('error', 'Faq Not Found..!');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $Faqs = Faqs::find($request->id);
        if ($Faqs) {
            $Faqs->title = $request['title'];
            $Faqs->description = $request['description'];
            $Faqs->updated_by = Auth::user()->id;
            $Faqs = $Faqs->update();
            if ($Faqs) {
                return redirect()->route('admin.faqs.index')->with('message', 'Faqs Updated Sucssesfully..');
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong..');
            }
        } else {
            return redirect()->back()->with('error', 'Faq Not Found..!');
        }
    }

    public function delete($id)
    {
        if ($id) {
            $Faqs = Faqs::find($id);
            $Faqs = $Faqs->delete();
            if ($Faqs) {
                return redirect()->route('admin.faqs.index')->with('message', 'Faqs Deleted Sucssesfully..');
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong..!');
            }
        } else {
            return redirect()->back()->with('error', 'Faq Not Found..!');
        }
    }

    public function statusToggle(Request $request)
    {
        if ($request->id) {
            $Faqs = Faqs::find($request->id);
            $Faqs->status = $request->status;
            $Faqs = $Faqs->update();
            if ($Faqs) {
                return response()->json(['success' => 'Status Updated successfully.']);
            } else {
                return response()->json(['error' => 'Somthing Went Wrong..!']);
            }
        } else {
            return response()->json(['error' => 'Faq Not Found..!']);
        }
    }
}
