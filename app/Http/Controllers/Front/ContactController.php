<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\Admin\CareerEnquiry;
use App\Mail\Admin\ContactEnquiry;
use App\Models\Front\ContactMessage;
use Illuminate\Http\Request;
use App\Models\ContactSetting;
use App\Models\CareerMessage;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    //
    public function contact()
    {
        $ContactSetting = ContactSetting::where('static_id', 1)->where('status', 1)->first();
        return view('front.pages.contact', ['ContactSetting' => $ContactSetting]);
    }
    public function contactMessageSave(Request $request)
    {
        $request->validate([
            'name' => 'required|max:40',
            'company_name' => 'required',
            'email' => 'required|email',
            'phone_full' => 'required',
            'address' => 'required',
            'message' => 'required',
        ]);

        $ContactMessage = new ContactMessage();
        $ContactMessage->name = $request['name'];
        $ContactMessage->email = $request['email'];
        $ContactMessage->phone = $request['phone_full'];
        $ContactMessage->company_name = $request['company_name'];
        $ContactMessage->c_code = $request['c_code'];
        $ContactMessage->address = $request['address'];
        $ContactMessage->message = $request['message'];
        $ContactMessage->save();

        $data = [
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone_full'],
            'address' => $request['address'],
            'company_name' => $request['company_name'],
            'c_code' => $request['c_code'],
            'message' => $request['message'],
            'id' => $ContactMessage->id,
            'created_at' => $ContactMessage->created_at ? Carbon::parse($ContactMessage->created_at)->setTimezone('Asia/Kolkata')->toDateTimeString() : '',
        ];
        // $email = SiteSetting::where('key','career_enquiry_email')->first();
        // if (isset($email) && $email && $email->value && $email->value != null && $email->value != '') {
        //     $mail = Mail::to($email->value)->send(new ContactEnquiry($data));
        // }
        if ($ContactMessage) {
            return redirect()->back()->with('message', 'Thanks for contacting us. We will contact you ASAP!..');
        } else {
            return redirect()->back()->with('error', 'Somthing Went Wrong..');
        }
    }
}
