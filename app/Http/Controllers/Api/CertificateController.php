<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Course;
use App\Services\CertificateService;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    protected CertificateService $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function index(Request $request)
    {
        $certificates = auth()->user()->certificates()->with('course')->get();
        return response()->json($certificates);
    }

    public function show(Certificate $certificate)
    {
        $this->authorize('view', $certificate);
        return response()->json($certificate->load('course'));
    }

    public function download(Certificate $certificate)
    {
        $this->authorize('view', $certificate);
        return $this->certificateService->download($certificate);
    }

    public function generateForCourse(Request $request, Course $course)
    {
        $user = auth()->user();
        $certificate = $this->certificateService->generateCertificate($user, $course);

        if (!$certificate) {
            return response()->json([
                'error' => 'Course must be 100% complete to generate certificate',
            ], 422);
        }

        return response()->json([
            'message' => 'Certificate generated successfully',
            'certificate' => $certificate,
        ]);
    }
}
