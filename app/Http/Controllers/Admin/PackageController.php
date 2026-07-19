<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PackageController extends Controller
{
    public function index()
    {
        $packageRequests = PackageRequest::orderByDesc('created_at')->get();

        $unseenCount = PackageRequest::where('seen', false)->count();

        $plans = [
            'Starter' => [
                'subtitle' => 'For small brands and launch campaigns',
                'items' => ['1 concept', '1 AI video', '1 platform-ready format', '2 revisions'],
            ],
            'Growth' => [
                'subtitle' => 'For growing brands and recurring campaigns',
                'items' => ['3 concepts', '2 AI videos', 'Multi-format delivery', '4 revisions'],
            ],
            'Premium' => [
                'subtitle' => 'For full-scale product launches',
                'items' => ['5 concepts', '5 AI videos', 'Campaign asset suite', 'Unlimited revisions'],
            ],
        ];

        return view('admin.packages.index', compact('packageRequests', 'unseenCount', 'plans'));
    }

    public function markAllRead(Request $request)
    {
        PackageRequest::where('seen', false)
            ->update(['seen' => true]);

        return redirect()->route('admin.packages.index')->with('success', 'All package requests marked as read.');
    }

    public function reply(Request $request, PackageRequest $packageRequest)
    {
        $data = $request->validate([
            'reply_message' => ['required', 'string', 'max:4000'],
        ]);

        $packageRequest->reply_message = $data['reply_message'];
        $packageRequest->status = 'replied';
        $packageRequest->replied_at = now();
        $packageRequest->email_status = 'pending';
        $packageRequest->save();

        try {
            Mail::send('emails.package-reply', [
                'packageRequest' => $packageRequest,
                'replyMessage' => $data['reply_message'],
            ], function ($message) use ($packageRequest) {
                $message->to($packageRequest->email, $packageRequest->name)
                    ->subject('Response to your package request')
                    ->from(config('mail.from.address', 'noreply@example.com'), config('mail.from.name', 'aiZAP Creatives'));
            });

            $packageRequest->email_status = 'sent';
            $packageRequest->save();

            return redirect()->route('admin.packages.index')->with('success', 'Reply sent successfully.');
        } catch (\Throwable $exception) {
            $packageRequest->email_status = 'failed';
            $packageRequest->save();

            return redirect()->route('admin.packages.index')->with('error', 'Unable to send email. Reply was saved for this request.');
        }
    }

    public function destroy(PackageRequest $packageRequest)
    {
        $packageRequest->delete();

        return redirect()->route('admin.packages.index')->with('success', 'Package request removed.');
    }
}
