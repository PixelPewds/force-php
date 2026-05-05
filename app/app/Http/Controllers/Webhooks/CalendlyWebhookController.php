<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\ScheduleCall;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CalendlyWebhookController extends Controller
{
    /**
     * Handle incoming Calendly webhook events.
     */
    public function handle(Request $request)
    {
        try {
            $payload = $request->all();

            Log::info('Calendly Webhook Received', ['event' => $payload['event'] ?? 'unknown']);

            // Verify webhook signature (recommended for security)
            if ($request->hasHeader('Calendly-Webhook-Signature')) {
                if (!$this->verifyWebhookSignature($request)) {
                    Log::warning('Invalid webhook signature');
                    return response()->json(['message' => 'Invalid signature'], 401);
                }
            }

            // Handle different webhook events
            switch ($payload['event'] ?? null) {
                case 'invitee.created':
                    $this->handleInviteeCreated($payload);
                    break;
                case 'invitee.canceled':
                    $this->handleInviteeCanceled($payload);
                    break;
                default:
                    Log::info('Unhandled webhook event', ['event' => $payload['event'] ?? 'unknown']);
            }

            return response()->json(['message' => 'Webhook processed successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle invitee.created event from Calendly.
     */
    private function handleInviteeCreated(array $payload)
    {
        $inviteeData = $payload['payload'] ?? [];
        $inviteeEmail = $inviteeData['invitee']['email'] ?? null;

        if (!$inviteeEmail) {
            Log::warning('Invitee email missing in webhook payload');
            return;
        }

        // Find user by email
        $user = User::where('email', $inviteeEmail)->first();

        if (!$user) {
            Log::warning('User not found for email', ['email' => $inviteeEmail]);
            return;
        }

        // Extract data from webhook payload
        $scheduledAt = $inviteeData['scheduled_event']['start_time'] ?? null;
        $eventUri = $inviteeData['scheduled_event']['uri'] ?? null;
        $inviteeUri = $inviteeData['invitee']['uri'] ?? null;

        if (!$scheduledAt || !$eventUri) {
            Log::warning('Required fields missing in webhook payload', ['email' => $inviteeEmail]);
            return;
        }

        // Check if this booking already exists
        $existingCall = ScheduleCall::where('student_id', $user->id)
            ->where('calendly_event_url', $inviteeUri)
            ->first();

        if ($existingCall) {
            Log::info('Schedule call already exists', ['email' => $inviteeEmail]);
            return;
        }

        // Create schedule call record
        try {
            ScheduleCall::create([
                'student_id' => $user->id,
                'scheduled_at' => $scheduledAt,
                'status' => 'scheduled',
                'notes' => $inviteeData['invitee']['notes'] ?? null,
                'calendly_event_id' => basename($eventUri),
                'calendly_event_url' => $inviteeUri,
            ]);

            Log::info('Schedule call created from webhook', ['email' => $inviteeEmail]);
        } catch (\Exception $e) {
            Log::error('Failed to create schedule call from webhook', [
                'email' => $inviteeEmail,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle invitee.canceled event from Calendly.
     */
    private function handleInviteeCanceled(array $payload)
    {
        $inviteeData = $payload['payload'] ?? [];
        $inviteeUri = $inviteeData['invitee']['uri'] ?? null;

        if (!$inviteeUri) {
            Log::warning('Invitee URI missing in cancel webhook payload');
            return;
        }

        try {
            $scheduleCall = ScheduleCall::where('calendly_event_url', $inviteeUri)
                ->where('status', '!=', 'cancelled')
                ->first();

            if ($scheduleCall) {
                $scheduleCall->update(['status' => 'cancelled']);
                Log::info('Schedule call cancelled from webhook', ['invitee_uri' => $inviteeUri]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to cancel schedule call from webhook', [
                'invitee_uri' => $inviteeUri,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Verify webhook signature for security.
     * 
     * This is optional but recommended for production.
     */
    private function verifyWebhookSignature(Request $request): bool
    {
        $signature = $request->header('Calendly-Webhook-Signature');
        $body = $request->getContent();
        $secret = config('schedule-call.webhook_secret');

        if (!$secret) {
            return true; // Skip verification if no secret is configured
        }

        $expectedSignature = hash_hmac('sha256', $body, $secret);

        return hash_equals($expectedSignature, $signature);
    }
}
