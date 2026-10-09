<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\PaymentAlreadyProcessedException;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PaymentVerificationController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['pending', 'verified', 'rejected'])],
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => [
                'nullable',
                'date_format:Y-m-d',
                Rule::when($request->filled('start_date'), ['after_or_equal:start_date']),
            ],
        ]);

        $payments = Payment::query()
            ->with([
                'member:id,name,email',
                'admin:id,name',
                'membershipPackage:id,name',
                'ptPackage:id,name',
            ])
            ->when(
                filled($filters['search'] ?? null),
                function ($query) use ($filters): void {
                    $search = trim($filters['search']);

                    $query->where(function ($query) use ($search): void {
                        $query->where('invoice_id', 'like', "%{$search}%")
                            ->orWhereHas('member', function ($memberQuery) use ($search): void {
                                $memberQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    });
                },
            )
            ->when(
                filled($filters['status'] ?? null),
                fn ($query) => $query->where('status', $filters['status']),
            )
            ->when(
                filled($filters['start_date'] ?? null),
                fn ($query) => $query->whereDate('transfer_date', '>=', $filters['start_date']),
            )
            ->when(
                filled($filters['end_date'] ?? null),
                fn ($query) => $query->whereDate('transfer_date', '<=', $filters['end_date']),
            )
            ->latest('transfer_date')
            ->paginate(15)
            ->withQueryString();

        if ($request->expectsJson()) {
            $payments->through(fn (Payment $payment): array => $this->paymentSummary($payment));

            return response()->json($payments);
        }

        return view('admin.verifikasi-pembayaran.index', compact('payments'));
    }

    public function show(int $id): JsonResponse
    {
        $payment = Payment::query()
            ->with([
                'member:id,name,email',
                'admin:id,name',
                'membershipPackage:id,name',
                'ptPackage:id,name',
                'verificationLogs' => fn ($query) => $query->latest(),
                'verificationLogs.admin:id,name',
            ])
            ->findOrFail($id);

        return response()->json([
            'data' => [
                ...$this->paymentSummary($payment),
                'bank_sender' => $payment->bank_sender,
                'bank_destination' => $payment->bank_destination,
                'proof_image_url' => $payment->proof_image_url,
                'rejection_reason' => $payment->rejection_reason,
                'verified_at' => $payment->verified_at?->toISOString(),
                'verified_by' => $payment->admin,
                'verification_logs' => $payment->verificationLogs->map(fn ($log): array => [
                    'action' => $log->action,
                    'notes' => $log->notes,
                    'created_at' => $log->created_at?->toISOString(),
                    'admin' => $log->admin,
                ]),
            ],
        ]);
    }

    public function approve(
        Request $request,
        PaymentVerificationService $service,
        int $id,
    ): JsonResponse {
        try {
            $service->approvePayment(
                Payment::query()->findOrFail($id),
                (int) $request->user()->getAuthIdentifier(),
            );
        } catch (PaymentAlreadyProcessedException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diverifikasi.',
        ]);
    }

    public function reject(
        Request $request,
        PaymentVerificationService $service,
        int $id,
    ): JsonResponse {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        try {
            $service->rejectPayment(
                Payment::query()->findOrFail($id),
                $validated['reason'],
                (int) $request->user()->getAuthIdentifier(),
            );
        } catch (PaymentAlreadyProcessedException $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage(),
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil ditolak.',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function paymentSummary(Payment $payment): array
    {
        return [
            'id' => $payment->id,
            'invoice_id' => $payment->invoice_id,
            'member' => $payment->member,
            'package' => [
                'id' => $payment->package_id,
                'type' => $payment->package_type,
                'name' => $payment->package_type === 'membership'
                    ? $payment->membershipPackage?->name
                    : $payment->ptPackage?->name,
            ],
            'amount' => $payment->amount,
            'transfer_date' => $payment->transfer_date?->toISOString(),
            'status' => $payment->status,
        ];
    }
}
