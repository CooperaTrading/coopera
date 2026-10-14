<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactFormSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('contact');
    }

    public function send(ContactRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Mail::to(config('mail.from.address'))
            ->send(new ContactFormSubmission($data));

        return back()->with('contact_status', 'Thank you. Your message has been sent. We will respond shortly.');
    }
}
