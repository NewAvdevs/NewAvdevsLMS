<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\CertificateIssuedMail;

class CertificateService
{
    protected ProgressService $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * Generate certificate for user when course is 100% complete
     *
     * @param User $user
     * @param Course $course
     * @return Certificate|null
     */
    public function generateCertificate(User $user, Course $course): ?Certificate
    {
        // Check if already exists
        $existing = Certificate::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        // Check if course is 100% complete
        $progress = $this->progressService->getCourseProgress($user, $course);
        if ($progress < 100) {
            return null;
        }

        // Generate PDF
        $pdf = Pdf::loadView('certificates.template', [
            'user' => $user,
            'course' => $course,
            'issuedAt' => now(),
        ]);

        // Store PDF
        $fileName = "certificate_{$user->id}_{$course->id}_" . now()->timestamp . '.pdf';
        $path = "certificates/{$fileName}";
        Storage::disk('public')->put($path, $pdf->output());

        // Create certificate record
        $certificate = Certificate::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'certificate_path' => $path,
            'issued_at' => now(),
        ]);

        // Send email notification
        $this->sendCertificateEmail($user, $certificate, $course);

        return $certificate;
    }

    /**
     * Send certificate via email
     *
     * @param User $user
     * @param Certificate $certificate
     * @param Course $course
     * @return void
     */
    private function sendCertificateEmail(User $user, Certificate $certificate, Course $course): void
    {
        try {
            Mail::to($user->email)->send(new CertificateIssuedMail($user, $certificate, $course));
        } catch (\Exception $e) {
            \Log::error('Failed to send certificate email', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Download certificate PDF
     *
     * @param Certificate $certificate
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(Certificate $certificate)
    {
        return Storage::disk('public')->download($certificate->certificate_path);
    }

    /**
     * Verify certificate belongs to user
     *
     * @param User $user
     * @param Certificate $certificate
     * @return bool
     */
    public function issuedToUser(User $user, Certificate $certificate): bool
    {
        return $certificate->user_id === $user->id;
    }
}
