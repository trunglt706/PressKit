<?php

namespace App\Services;

use App\Repositories\ContactSubmissionRepository;
use Illuminate\Http\RedirectResponse;

class ContactSubmissionService
{
    public function __construct(
        private readonly ContactSubmissionRepository $contactSubmissionRepository
    ) {
    }

    /**
     * Store contact submission and enforce one submission per day by IP, email, or phone.
     */
    public function handleGuestSubmission(array $validatedData, string $clientIp): RedirectResponse
    {
        $submittedDate = now()->toDateString();

        $duplicate = $this->contactSubmissionRepository->findDailyDuplicate(
            $submittedDate,
            $clientIp,
            $validatedData['email'],
            $validatedData['phone']
        );

        if ($duplicate) {
            if ($duplicate->ip_address === $clientIp) {
                return back()->withErrors([
                    'daily_limit' => 'IP này đã gửi liên hệ trong hôm nay. Vui lòng thử lại vào ngày mai.',
                ])->withInput();
            }

            if ($duplicate->email === $validatedData['email']) {
                return back()->withErrors([
                    'email' => 'Email này đã gửi liên hệ trong hôm nay. Vui lòng thử lại vào ngày mai.',
                ])->withInput();
            }

            return back()->withErrors([
                'phone' => 'Số điện thoại này đã gửi liên hệ trong hôm nay. Vui lòng thử lại vào ngày mai.',
            ])->withInput();
        }

        $this->contactSubmissionRepository->create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'subject' => $validatedData['subject'],
            'message' => $validatedData['message'],
            'ip_address' => $clientIp,
            'submitted_date' => $submittedDate,
        ]);

        return redirect()
            ->route('contact')
            ->with('success', 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi trong thời gian sớm nhất.');
    }
}
