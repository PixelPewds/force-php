<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Models\StripeUser;
use App\Models\User;
use App\Models\StripeUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Exception;
use Auth;
use App\Facades\Notification;

class StripeUserController extends Controller
{
    // Configure this with your Stripe Payment Link URL
    public function check($type){
        if($type == 1){
            return view('stripe.assessment',compact('type'));
        }
        return view('stripe.cohort',compact('type'));
    }

    /**
    * Initiate Payment - Store data and redirect to Stripe Payment Link
    */
    public function initiatePayment(Request $request)
    {
        // return $request->all();
        try {
            // Validate all incoming data
            $validated = $request->validate([
                'parentName' => 'required|string|max:255',
                'relation' => 'required|string|max:255',
                'education' => 'required|string|max:255',
                'whatsapp' => 'required|string|max:20',
                'email' => 'required|unique:users',
                'address1' => 'required|string',
                'address2' => 'nullable|string',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'zip' => 'required|string|max:20',
                'country' => 'required|string|max:100',
                'studentName' => 'required|string|max:255',
                'grade' => 'required|string|max:50',
                'gender' => 'required|string|max:20',
                'school' => 'required|string|max:255',
                'board' => 'required|string|max:255',
                'careerClarity' => 'required|string|max:255',
                'goals' => 'required',
                'comments' => 'nullable|string',
                'type' => 'required'
            ]);

            // Use database transaction for consistency
            DB::beginTransaction();

            try {
                $stripeUser = StripeUser::create([
                    'parent_name' => $validated['parentName'],
                    'relation' => $validated['relation'],
                    'education' => $validated['education'],
                    'phone' => $validated['whatsapp'],
                    'email' => $validated['email'],
                    'address' => $validated['address1'],
                    'address2' => $validated['address2'],
                    'city' => $validated['city'],
                    'state' => $validated['state'],
                    'zip' => $validated['zip'],
                    'country' => $validated['country'],
                    'student_name' => $validated['studentName'],
                    'grade' => $validated['grade'],
                    'gender' => $validated['gender'],
                    'school' => $validated['school'],
                    'board' => $validated['board'],
                    'career_clarity' => $validated['careerClarity'],
                    'goals' => $validated['goals'],
                    'comments' => $validated['comments'],
                    'program' => $validated['program']??"",
                    'timestamp' => $validated['timestamp']??NOW(),
                    'payment_status' => 'pending',
                    'client_reference_id' => $stripeUser->id ?? uniqid('stripe_', true),
                    'type' => $request->type
                ]);

                // Store the generated ID as client_reference_id
                $stripReferenceId = 'force_' . $stripeUser->id . '_' . time();
                $stripeUser->update([
                    'client_reference_id' => $stripReferenceId,
                ]);

                session(['client_reference_id' => $stripReferenceId]);

                DB::commit();

                $paymentLink = $this->buildPaymentLink($stripeUser, $request->country, $request->type??1);

                return redirect()->away($paymentLink);
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate payment',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Success Page - User redirected here after successful Stripe payment
     * GET /api/stripe/success
     * 4242 4242 4242 4242
     */
    public function success(Request $request)
    {
        try {
            // Get session_id from query parameters if available
            $sessionId = $request->query('session_id');
            $clientReferenceId = $request->query('client_reference_id')??session('client_reference_id')??"";
            session()->forget('client_reference_id');
            return view('students.congrats.pay-success', compact('clientReferenceId'));            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing success page',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel Page - User cancelled payment
     * GET /api/stripe/cancel
     */
    public function cancel(Request $request)
    {
        try {
            $clientReferenceId = $request->query('client_reference_id');
            $sessionId = $request->query('session_id');

            // Optionally update status to cancelled
            if ($clientReferenceId) {
                StripeUser::where('client_reference_id', $clientReferenceId)
                    ->update(['payment_status' => 'cancelled']);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment was cancelled. You can try again anytime.',
                'data' => [
                    'session_id' => $sessionId,
                    'client_reference_id' => $clientReferenceId,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing cancellation',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Webhook Handler - Source of truth for payment status
     * POST /api/stripe/webhook
     *
     * This endpoint is called by Stripe when payment is completed.
     * It updates the StripeUser record and creates user account.
     */
    public function webhook(Request $request)
    {
        try {
            $payload = $request->json()->all();

            // Validate webhook payload
            if (!isset($payload['type'])) {
                return response()->json(['success' => false, 'message' => 'Invalid webhook'], 400);
            }

            // Listen for checkout.session.completed event
            if ($payload['type'] === 'checkout.session.completed') {
                return $this->handleSessionCompleted($payload);
            }

            // Acknowledge other events
            return response()->json(['received' => true]);
        } catch (Exception $e) {
            \Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Webhook processing error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle checkout.session.completed webhook event
     */
    private function handleSessionCompleted(array $payload): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $payload['data']['object'] ?? null;

            if (!$data) {
                return response()->json(['success' => false], 400);
            }

            // Extract important data from webhook
            $clientReferenceId = $data['client_reference_id'] ?? null;
            $sessionId = $data['id'] ?? null;
            $paymentStatus = $data['payment_status'] ?? null;
            $customerEmail = $data['customer_email'] ?? null;

            if (!$clientReferenceId) {
                \Log::error('Webhook missing client_reference_id');
                return response()->json(['success' => false], 400);
            }

            // Find StripeUser by client_reference_id
            $stripeUser = StripeUser::where('client_reference_id', $clientReferenceId)->first();

            if (!$stripeUser) {
                \Log::error('StripeUser not found for reference: ' . $clientReferenceId);
                return response()->json(['success' => false], 404);
            }

            // Only process if payment is successful
            if ($paymentStatus === 'paid') {
                DB::beginTransaction();

                try {
                    // Update StripeUser with success status
                    $stripeUser->update([
                        'payment_status' => 'success',
                        'stripe_session_id' => $sessionId,
                    ]);

                    // Create or update user account for login access
                    $this->createOrUpdateUser($stripeUser);

                    activity()
                        ->performedOn($stripeUser)
                        ->causedBy($stripeUser)
                        ->withProperties(['id' => $stripeUser->id])
                        ->log('Payment confirmed for: ' . $stripeUser->email);


                    Notification::notifyMessage(
                        user: $stripeUser->id,
                        title: 'Payment',
                        message: 'Payment confirmed for: ' . $stripeUser->email,
                        actionUrl: null
                    );

                    DB::commit();

                    \Log::info('Payment confirmed for: ' . $stripeUser->email);
                } catch (Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }

            return response()->json(['received' => true]);
        } catch (Exception $e) {
            \Log::error('Error handling session completed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create or Update User in users table for login access
     */
    private function createOrUpdateUser(StripeUser $stripeUser): bool
    {
        try {
            $password = 'force';

            // Check if user already exists by email
            $user = User::where('email', $stripeUser->email)->first();

            if ($user) {
                // Update existing user
               $user = $user->update([
                    'name' => $stripeUser->student_name,
                    'mobile' => $stripeUser->phone,
                    'address' => $stripeUser->address,
                    'gender' => $stripeUser->gender,
                    'status' => 'Y',
                ]);
            } else {
                // Create new user
               $user = User::create([
                    'name' => $stripeUser->student_name,
                    'email' => $stripeUser->email,
                    'password' => $password,
                    'mobile' => '1234'.rand(100000,999999),
                    'address' => $stripeUser->address,
                    'gender' => $stripeUser->gender,
                    'status' => 'Y',
                    'created_by' => 1,
                ]);
                $studentRole = Role::where('name', 'Student')->first();
                if (!$studentRole) {
                    return redirect()->back()->with('danger', 'Student role does not exist. Please create it first.');
                }
                $user->assignRole($studentRole);
            }
          
            return true;
        } catch (Exception $e) {
            \Log::error('Failed to create/update user for StripeUser ' . $stripeUser->id . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Build Stripe Payment Link with client_reference_id parameter
     */
    private function buildPaymentLink(StripeUser $stripeUser, $conutry,  $type)
    {
        $stripe = StripeUrl::first();
        $baseLink = $stripe->exploration_pay;
        if($conutry == 'India'){
            $baseLink = $stripe->exploration_pay_ind;
        }
        if($type == 1){
            $baseLink = $stripe->assessment_pay;
            if($conutry == 'India'){
                $baseLink = $stripe->assessment_pay_ind;
            }
        }
        $separator = strpos($baseLink, '?') !== false ? '&' : '?';
        return $baseLink . $separator . 'client_reference_id=' . urlencode($stripeUser->client_reference_id);
    }

    public function addStripeUrl(Request $request){
        $validated = $request->validate([
            'assessment_pay' => 'required|string|max:255',
            'exploration_pay' => 'required|string|max:255',
            'assessment_pay_ind' => 'required|string|max:255',
            'exploration_pay_ind' => 'required|string|max:255',
        ]);
        $message = "Added";
        if($request->stripe_id){
            $stripeURL = StripeUrl::find($request->stripe_id);
            $stripeURL->update(array_merge($request->all(),
                [
                    'updated_by' => Auth::user()->id
                ]
            ));
            $message = "Updated";
        }else{
            StripeUrl::create(array_merge($request->all(),
                [
                    'created_by' => Auth::user()->id
                ]
            ));
        }
       
        return redirect()->route('getStripeUrl')
            ->with('success', 'Stripe payment URL '. $message .' successfully');
    }

    public function getStripeUrl(){
        $stripes = StripeUrl::get();
        return view('admin.stripe.list', compact('stripes'));
    }

    public function createStripeUrl(){
        $stripe = StripeUrl::first();
        return view('admin.stripe.create', compact('stripe'));
    }

    public function getStudentList(){
        $stripeUsers = StripeUser::orderBy('id','desc')->get();
        return view('admin.stripe.stipe-list', compact('stripeUsers'));
    }
}