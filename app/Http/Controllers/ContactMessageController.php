<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:64'],
            'message' => ['required', 'string', 'max:2000'],
            'request_type' => ['nullable', 'string', 'in:contact,book_call'],
        ]);

        ContactMessage::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'message' => $data['message'],
            'request_type' => $data['request_type'] ?? 'contact',
            'status' => 'pending',
        ]);

        $message = $data['request_type'] === 'book_call'
            ? 'Thanks! We will contact you soon to schedule your call.'
            : 'Your message has been sent. We will contact you soon.';

        return back()->with('status', $message);
    }

    public function adminIndex()
    {
        $messages = ContactMessage::orderByDesc('created_at')->get();

        return view('admin.contacts.index', compact('messages'));
    }

    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $data = $request->validate([
            'reply_subject' => ['required', 'string', 'max:255'],
            'reply_message' => ['required', 'string', 'max:4000'],
            'recipient_email' => ['required', 'email', 'max:255'],
        ]);

        $contactMessage->reply_subject = $data['reply_subject'];
        $contactMessage->reply_message = $data['reply_message'];
        $contactMessage->status = 'replied';
        $contactMessage->replied_at = now();
        $contactMessage->email_status = 'pending';
        $contactMessage->save();

        try {
            Mail::send('emails.contact-reply', [
                'contactMessage' => $contactMessage,
                'replyMessage' => $data['reply_message'],
            ], function ($message) use ($contactMessage, $data) {
                $message->to($data['recipient_email'], $contactMessage->name)
                    ->subject($data['reply_subject'])
                    ->from(config('mail.from.address', 'noreply@example.com'), config('mail.from.name', 'aiZAP Creatives'));
            });

            $contactMessage->email_status = 'sent';
            $contactMessage->save();

            return redirect()->route('admin.contacts.index')->with('success', 'Reply sent successfully.');
        } catch (\Throwable $exception) {
            $contactMessage->email_status = 'failed';
            $contactMessage->save();

            return redirect()->route('admin.contacts.index')->with('error', 'Unable to send email. The reply was saved locally.');
        }
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Contact message deleted.');
    }
}
