<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function contact(Request $request){
        $data = [
            'pageTitle' => 'Contact',
        ];
        return view('pages.contact', $data);
    }

    public function sendContact(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'category' => 'required|string',
        'other_category' => 'nullable|string',
        'message' => 'required|string|max:1000',
    ]);

    $category = $request->category === 'other' ? $request->other_category : $request->category;

    $mailData = [
        'data' => [
            'name' => $request->name,
            'email' => $request->email,
            'category' => $category,
            'message' => $request->message,
        ]
    ];

    try {
        Mail::mailer('cmail')->send('email-template.contact', $mailData, function ($message) use ($request) {
            $message->to('testing@mailtrap.io')
                   ->subject('New Contact Message')
                   ->from($request->email, $request->name);
        });

        return redirect()->route('contact')->with('success', 'Your message has been sent!');
    } catch (\Exception $e) {
        return redirect()->route('contact')->with('fail', 'Failed to send message. Please try again.');
    }
}
}


