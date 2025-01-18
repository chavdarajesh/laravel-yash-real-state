<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\Admin\NewsletterMail as MailNewsletterMail;
use App\Models\Newsletter;
use App\Models\NewsletterContent;
use App\Models\NewsletterMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class NewsletterMailController extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = NewsletterContent::all();
            return DataTables::of($data)
                ->addColumn('id', function ($row) {
                    return '<strong>' . $row->id . '</strong>';
                })
                ->addColumn('content', function ($row) {
                    return strlen($row->content) > 25 ? substr($row->content, 0, 25) . '..' : $row->content;
                })
                ->addColumn('status', function ($row) {
                    $data['id'] = $row->id;
                    $data['status'] = $row->status;
                    return View::make('admin.newslettermails.status', ['data'=>$data])->render();
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? Carbon::parse($row->created_at)->setTimezone('Asia/Kolkata')->toDateTimeString() : '';
                })
                ->addColumn('actions', function ($row) {
                    $data['id'] = $row->id;
                    return View::make('admin.newslettermails.actions', ['data' => $data])->render();
                })
                ->rawColumns(['id', 'actions', 'status', 'created_at', 'content'])
                ->make(true);
        } else {
            return view('admin.newslettermails.index');
        }
    }
    public function create()
    {
        return view('admin.newslettermails.create');
    }
    public function save(Request $request)
    {
        $request->validate([
            'content' => 'required',
        ]);
        $NewsletterContent = new NewsletterContent();
        $NewsletterContent->content = $request['content'];
        $NewsletterContent->created_by = Auth::user()->id;
        $NewsletterContent->updated_by = Auth::user()->id;
        $NewsletterContent->save();

        if ($NewsletterContent) {
            return redirect()->route('admin.newslettermails.index')->with('message', 'Newsletter Mail Sent Sucssesfully..');
        } else {
            return redirect()->back()->with('error', 'Somthing Went Wrong..');
        }
    }
    public function view($id)
    {
        $NewsletterContent = NewsletterContent::find($id);
        if ($NewsletterContent) {
            return view('admin.newslettermails.view', ['NewsletterContent' => $NewsletterContent]);
        } else {
            return redirect()->back()->with('error', 'NewsletterContent Not Found..!');
        }
    }

    public function edit($id)
    {
        $NewsletterContent = NewsletterContent::find($id);
        if ($NewsletterContent) {

            return view('admin.newslettermails.edit', ['NewsletterContent' => $NewsletterContent]);
        } else {
            return redirect()->back()->with('error', 'NewsletterContent Not Found..!');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'content' => 'required',
        ]);
        $NewsletterContent = NewsletterContent::find($request->id);
        if ($NewsletterContent) {
            $NewsletterContent->content = $request['content'];
            $NewsletterContent->updated_by = Auth::user()->id;
            $NewsletterContent->update();
            if ($NewsletterContent) {
                return redirect()->route('admin.newslettermails.index')->with('message', 'NewsletterContent Updated Sucssesfully..');
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong..');
            }
        } else {
            return redirect()->back()->with('error', 'NewsletterContent Not Found..!');
        }
    }

    public function delete($id)
    {
        if ($id) {
            $NewsletterContent = NewsletterContent::find($id);
            $NewsletterContent = $NewsletterContent->delete();
            if ($NewsletterContent) {
                return redirect()->route('admin.newslettermails.index')->with('message', 'Newsletter Mail Deleted Sucssesfully..');
            } else {
                return redirect()->back()->with('error', 'Somthing Went Wrong..!');
            }
        } else {
            return redirect()->back()->with('error', 'NewsletterContent Not Found..!');
        }
    }

    public function  sendMail($id)
    {
        $NewsletterContent = NewsletterContent::find($id);
        if ($NewsletterContent) {
            $emailAddresses = Newsletter::where('status', 1)->whereNotNull('email')->pluck('email')->toArray();
            foreach ($emailAddresses as $email) {
                $encryptemail = encrypt($email);
                $data = [
                    'encryptemail' => $encryptemail,
                    'content' => $NewsletterContent['content'],
                ];
                Mail::to($email)->send(new MailNewsletterMail($data));
                $newsletterMail = new NewsletterMail(['email' => $email]);
                $NewsletterContent->mails()->save($newsletterMail);
            }
            return redirect()->route('admin.newslettermails.view', ['id' => $NewsletterContent->id])->with('message', 'Mails Sent Sucssesfully..');;
        } else {
            return redirect()->back()->with('error', 'NewsletterContent Not Found..!');
        }
    }
}
