<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use App\Models\ContactSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class ContactController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = ContactMessage::all();
            return DataTables::of($data)
                ->addColumn('id', function ($row) {
                    return '<strong>' . $row->id . '</strong>';
                })
                ->addColumn('subject', function ($row) {
                    return strlen($row->subject) > 25 ? substr($row->subject, 0, 25) . '..' : $row->subject;
                })
                ->addColumn('message', function ($row) {
                    return strlen($row->message) > 25 ? substr($row->message, 0, 25) . '..' : $row->message;
                })
                ->addColumn('status', function ($row) {
                    $data['id'] = $row->id;
                    $data['status'] = $row->status;
                    return View::make('admin.contact.messages.status', ['data'=>$data])->render();
                })
                ->addColumn('actions', function ($row) {
                    $data['id'] = $row->id;
                    return View::make('admin.contact.messages.actions', ['data' => $data])->render();
                })
                ->rawColumns(['id', 'actions', 'subject', 'message'])
                ->make(true);
        } else {
            return view('admin.contact.messages.index');
        }
    }

    public function delete($id)
    {
        if ($id) {
            $ContactMessage = ContactMessage::find($id);
            $ContactMessage = $ContactMessage->delete();
            if ($ContactMessage) {
                return redirect()->route('admin.contact.messages.index')->with('message', 'Contact Message Deleted Sucssesfully..');
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong..!');
            }
        } else {
            return redirect()->back()->with('error', 'Contact Message Not Found..!');
        }
    }

    public function view($id)
    {
        $ContactMessage = ContactMessage::find($id);
        if ($ContactMessage) {
            return view('admin.contact.messages.view', ['ContactMessage' => $ContactMessage]);
        } else {
            return redirect()->back()->with('error', 'Contact Message Not Found..!');
        }
    }

    public function indexContactSettings()
    {
        $ContactSetting = ContactSetting::where('static_id', 1)->where('status', 1)->first();
        return view('admin.contact.settings.index', ['ContactSetting' => $ContactSetting]);
    }
    public function saveContactSettings(Request $request)
    {
        $request->validate([
            'email' => 'email',
            // 'phone' => 'min:10'
            // 'whatsapp_number' => 'min:10'
        ]);

        $ContactSetting = ContactSetting::find($request->id);
        $ContactSetting->email = $request['email'];
        $ContactSetting->phone = $request['phone'];
        $ContactSetting->whatsapp_number = $request['whatsapp_number'];
        $ContactSetting->location = $request['location'];
        $ContactSetting->map_iframe = $request['map_iframe'];
        $ContactSetting->timing = $request['timing'];
        $ContactSetting->created_by = Auth::user()->id;
        $ContactSetting->updated_by = Auth::user()->id;
        $ContactSetting->update();
        if ($ContactSetting) {
            return redirect()->route('admin.contact.settings.index')->with('message', 'ContactSetting Saved Sucssesfully..');
        } else {
            return redirect()->back()->with('error', 'Somthing Went Wrong..');
        }
    }
}
