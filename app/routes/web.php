<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/force/course/{type}', [App\Http\Controllers\Stripe\StripeUserController::class, 'check'])->name('force.course');

// Stripe Payment Routes
Route::post('/stripe/initiate-payment', [App\Http\Controllers\Stripe\StripeUserController::class, 'initiatePayment'])->name('stripe.create');

Route::get('/stripe/success', [App\Http\Controllers\Stripe\StripeUserController::class, 'success'])->name('stripe.success');

Route::get('/stripe/cancel', [App\Http\Controllers\Stripe\StripeUserController::class, 'cancel'])->name('stripe.cancel');

Route::post('/stripe/webhook', [App\Http\Controllers\Stripe\StripeUserController::class, 'webhook'])->withoutMiddleware('csrf');


// Calendly Webhook Route (no auth required)
Route::post('/webhooks/calendly', 'Webhooks\\CalendlyWebhookController@handle')->name('webhooks.calendly');

// Notification Routes
Route::middleware('auth')->group(function () {
    Route::get('/notifications', 'NotificationsController@index')->name('notifications.index');
    Route::post('/notifications/{notification}/mark-as-read', 'NotificationsController@markAsRead')->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', 'NotificationsController@markAllAsRead')->name('notifications.mark-all-read');
    Route::post('/notifications/{notification}/mark-as-unread', 'NotificationsController@markAsUnread')->name('notifications.mark-unread');
    Route::delete('/notifications/{notification}', 'NotificationsController@delete')->name('notifications.delete');
    Route::delete('/notifications', 'NotificationsController@deleteAll')->name('notifications.delete-all');
    Route::get('/notifications/api/get', 'NotificationsController@getNotifications')->name('notifications.api.get');
});

Route::
        namespace('App\Http\Controllers\Admin')
    ->prefix('admin')
    ->middleware(['auth', 'role:Super Admin'])
    ->group(function () {
        Route::resource('users', 'UserController');
        Route::get('users-activity', 'UserController@activityLogs')->name('users-activity');
        Route::resource('forms', FormController::class);
        Route::resource('sections', SectionController::class);
        Route::resource('questions', QuestionController::class);
        Route::get('/review-form/{id}', 'QuestionController@showForm')->name('showForm');
        Route::get('/get-forms', 'QuestionController@getForms')->name('getForms');
        Route::resource('student', Student\StudentReportController::class);

        // Task Completion System Routes
        Route::resource('task-completion', Student\TaskCompletionSystemController::class);
        Route::post('task-completion/{taskCompletion}/add-remark', 'Student\TaskCompletionSystemController@addQuestionRemark')
            ->name('task-completion.add-remark');
        Route::get('task-completion-deadlines', 'Student\TaskCompletionSystemController@trackDeadlines')
            ->name('task-completion.deadlines');
        Route::post('task-completion/{taskCompletion}/respond-overall', 'Student\TaskCompletionSystemController@addOverallRemark')
            ->name('respond-admin-overall');

        // Resource Center Routes
        Route::resource('resource', 'Student\ResourceController');
        Route::post('resource/{resource}/clone', 'Student\ResourceController@clone')
            ->name('resource.clone');
        Route::post('resource/{resource}/add-remark', 'Student\ResourceController@addRemark')
            ->name('resource.add-remark');
        Route::get('resource-deadlines', 'Student\ResourceController@trackDeadlines')
            ->name('resource.deadlines');

        // Schedule Calls Management Routes
        Route::get('schedule-calls', 'Student\ScheduleCallAdminController@index')->name('admin.schedule-calls.index');
        Route::get('schedule-calls/{scheduleCall}', 'Student\ScheduleCallAdminController@show')->name('admin.schedule-calls.show');
        Route::put('schedule-calls/{scheduleCall}/update-status', 'Student\ScheduleCallAdminController@updateStatus')->name('admin.schedule-calls.update-status');

        //Student Bulk upload
        Route::get('/bulk-upload', 'UserController@bulkUploadForm')->name('bulk-upload-form');
        Route::post('/bulk-store', 'UserController@bulkStore')->name('bulk-store');
        Route::get('/download-template', 'UserController@downloadTemplate')->name('download-template');
    });

    Route::namespace('App\Http\Controllers\Stripe')
    ->prefix('admin')
    ->middleware(['auth', 'role:Super Admin'])
    ->group(function () {         
        Route::post('/add-stripe-pay', 'StripeUserController@addStripeUrl')->name('addStripeUrl');
        Route::get('/get-stripe-pay', 'StripeUserController@getStripeUrl')->name('getStripeUrl');
        Route::get('/stripe-pay', 'StripeUserController@createStripeUrl')->name('createStripeUrl');
        Route::get('/stripe-student-list', 'StripeUserController@getStudentList')->name('getStudentList');
    });

Route::
        namespace('App\Http\Controllers\Student')
    ->prefix('student')
    ->middleware(['auth', 'role:Super Admin|Student'])
    ->group(function () {

        Route::resource('studentprofile', 'StudentProfileController');

        Route::get(
            'form/{id}/start',
            'FormController@start'
        )->name('startForm');

        Route::post(
            'form/save-draft',
            'FormController@saveDraft'
        )->name('saveDraft');

        Route::post(
            'form/submit',
            'FormController@submit'
        )->name('form.submit');

        Route::get(
            'congrats',
            'FormController@congrats'
        )->name('form.congrats');

        Route::get(
            'form-details',
            'FormController@getForms'
        )->name('form-details');

        Route::resource('report', FormReportController::class);
        Route::resource('values-report', ValuesReportController::class);
        Route::resource('learning-report', LearningReportController::class);
        Route::resource('interest-report', InterestsReportController::class);

        // Task Completion Routes for Students
        Route::get('my-tasks', 'TaskCompletionController@myTasks')->name('task-completion.my-tasks');
        Route::get('task-completion/{taskCompletion}', 'TaskCompletionController@viewTask')
            ->name('task-completion.view');
        Route::post('task-completion/{taskCompletion}/mark-in-progress', 'TaskCompletionController@markInProgress')
            ->name('task-completion.mark-in-progress');
        Route::post('task-completion/{taskCompletion}/submit', 'TaskCompletionController@submitTask')
            ->name('task-completion.submit');
        Route::post('task-completion-remark/{remark}/respond', 'TaskCompletionController@respondToRemark')
            ->name('task-completion-remark.respond');
        Route::post('task-completion/{taskCompletion}/respond-overall', 'TaskCompletionController@respondToOverallRemarks')
            ->name('task-completion.respond-overall');

        // Resource Center Routes for Students
        Route::get('my-resources', 'ResourceCenterController@myResources')->name('resource.my-resources');
        Route::get('resource/{resource}', 'ResourceCenterController@viewResource')
            ->name('resource.view');
        // Route::post('resource/{resource}/mark-accessed', 'ResourceCenterController@markAccessed')
        //     ->name('resource.mark-accessed');
        Route::post('resource/{resource}/mark-completed', 'ResourceCenterController@markCompleted')
            ->name('resource.mark-completed');
        Route::post('resource-remark/{remark}/respond', 'ResourceCenterController@respondToRemark')
            ->name('resource-remark.respond');
        Route::post('resource/{resource}/respond-overall', 'ResourceCenterController@respondToOverallRemarks')
            ->name('resource.respond-overall');

        // Schedule Call Routes for Students
        Route::resource('schedule-calls', ScheduleCallController::class);
    });

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/service-home', [App\Http\Controllers\HomeController::class, 'index'])->name('service-home');