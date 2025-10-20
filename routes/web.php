<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IntroPageController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SystemLogController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\DoctorDashboardController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MessageController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Intro / Landing
Route::get('/', [IntroPageController::class, 'intropage']);

// Auth
Route::get('/login', [LoginController::class, 'showlogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showregister'])->name('register');  
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// -------------------- ADMIN --------------------
Route::prefix('admin')->group(function () {
    Route::get('/admin-dashboard', [AdminDashboardController::class, 'index'])->name('admin.admin-dashboard');
    Route::resource('/users', UserController::class);
    Route::resource('/notifications', NotificationController::class);
    Route::get('/view-appointment', [AdminDashboardController::class, 'viewallappointments'])->name('admin.view-appointment');
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::get('/logs', [SystemLogController::class, 'index'])->name('admin.logs');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/doctors/store', [DoctorController::class, 'store'])->name('doctors.store');
    Route::get('/admin/create-doctors', [DoctorController::class, 'create'])->name('admin.create-doctors');
    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('admin.settings');
    Route::post('/appointments/book', [AdminDashboardController::class, 'bookAppointment'])->name('admin.book-appointment');
    Route::post('/available-slots/store', [AdminDashboardController::class, 'storeSlot'])->name('doctor.store-slot');
    Route::get('/admin/slots', [AdminDashboardController::class, 'setappointment'])->name('admin.set-available-slots');
    Route::post('/admin/slots/archive', [AdminDashboardController::class, 'archiveSlot'])->name('admin.slots.archive');
    Route::put('/admin/slots/{id}/restore', [AdminDashboardController::class, 'restoreSlot'])->name('admin.slots.restore');
 
    Route::put('/doctors/{id}', [AdminController::class, 'update'])->name('doctors.update');
    Route::delete('/doctors/{id}', [AdminController::class, 'destroy'])->name('doctors.destroy');

    Route::get('/summary-report', [AdminDashboardController::class, 'summaryreport'])->name('admin.summary-report');
       
    Route::get('/admin/report/pdf', [AdminDashboardController::class, 'exportPDF'])->name('admin.report.pdf');
    Route::get('/admin/report/excel', [AdminDashboardController::class, 'exportExcel'])->name('admin.report.excel');


 Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('admin.notifications.read');
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('admin.notifications.delete');

    Route::patch('/admin/doctors/{doctor}/toggle-absence', [AdminDashboardController::class, 'toggleAbsence'])->name('doctors.toggleAbsence');

});



         Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])
        ->name('admin.notifications.delete');

    Route::get('/admin/manage-notifications', [NotificationController::class, 'managenotifications'])->name('admin.manage-notifications');

   Route::post('/doctor/notifications/{id}/read', [DoctorDashboardController::class, 'markAsRead'])
    ->name('doctor.notifications.read');

// -------------------- DOCTOR --------------------
Route::prefix('doctor')->group(function () {
    Route::get('/doctor-dashboard', [DoctorDashboardController::class, 'index'])->name('doctor.doctor-dashboard');

    Route::get('/appointments', [DoctorDashboardController::class, 'viewAppointments'])->name('doctor.appointments');
    Route::get('/patients/{id}', [DoctorDashboardController::class, 'viewPatient'])->name('doctor.view-patient');

    // Chat/Video
    Route::get('/consultation/{id}', [DoctorDashboardController::class, 'chatcall'])->name('doctor.consultation');

    // Prescription
    Route::get('/prescriptions/{id}', [PrescriptionController::class, 'index'])->name('doctor.prescriptions');
    Route::post('/prescriptions/store', [PrescriptionController::class, 'store'])->name('doctor.prescriptions.store');
    Route::get('/available-slots', [DoctorDashboardController::class, 'setSlot'])->name('doctor.available-slots');
    Route::get('/view-appointment', [DoctorDashboardController::class, 'viewappointment'])->name('doctor.view-appointment');
    Route::get('/view-patients', [DoctorDashboardController::class, 'viewpatients'])->name('doctor.view-patients');
    Route::get('/write-prescriptions', [DoctorDashboardController::class, 'writeprescripts'])->name('doctor.write-prescriptions');
    
    
    Route::get('/chat-call', [DoctorDashboardController::class, 'chatcall'])->name('doctor.chat-call');
    Route::post('/appointments/{id}/approve', [DoctorDashboardController::class, 'approveAppointment'])->name('doctor.appointments.approve');
    Route::post('/view-appointment/{id}/deny', [DoctorDashboardController::class, 'denyAppointment'])->name('doctor.view-appointment.deny');
    Route::post('/available-slots/store', [DoctorDashboardController::class, 'storeSlot'])->name('doctor.store-slot');

    Route::post('/doctor/prescriptions/store', [DoctorDashboardController::class, 'storePrescription'])->name('doctor.prescriptions.store');
    Route::get('/my-profile', [DoctorDashboardController::class, 'myprofile'])->name('doctor.my-profile');
    Route::post('/doctor/my-profile/update', [PatientDashboardController::class, 'updateProfile'])->name('doctor.update-profile');
});

    Route::post('/doctor/write-prescriptions/archive', [DoctorDashboardController::class, 'archivePrescription'])->name('doctor.write-prescriptions.archive');
    Route::post('/doctor/write-prescriptions/restore', [DoctorDashboardController::class, 'restorePrescription'])
    ->name('doctor.write-prescriptions.restore');

Route::get('/doctor/notifications', [DoctorDashboardController::class, 'fetchNotifications'])
     ->name('doctor.notifications.fetch');

Route::post('/doctor/notifications/{id}/read', [DoctorDashboardController::class, 'markNotificationRead'])
    ->name('doctor.notifications.read');

        Route::get('/notifications/fetch', [NotificationController::class, 'fetchNotifications'])->name('notifications.fetch');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

// -------------------- PATIENT --------------------

Route::prefix('patient')->group(function () {
    Route::get('/patient-dashboard', [PatientDashboardController::class, 'index'])->name('patient.patient-dashboard');

    Route::get('/view-appointment', [PatientDashboardController::class, 'viewappointment'])->name('patient.view-appointment');
    Route::get('/book-appointment', [PatientDashboardController::class, 'showbook'])->name('patient.book-appointment');
    Route::get('/video-call', [PatientDashboardController::class, 'chatcalls'])->name('patient.video-call');
    Route::get('/give-feedback', [PatientDashboardController::class, 'feedback'])->name('patient.give-feedback');
    Route::get('/view-precription', [PatientDashboardController::class, 'prescriptions'])->name('patient.view-precription');
    Route::post('/patient/appointments/store', [PatientDashboardController::class, 'store'])->name('patient.appointments.store');
    Route::post('/patient/appointments/{id}/cancel', [PatientDashboardController::class, 'cancel'])->name('patient.cancel');

    // Payments
    Route::get('/payment/{appointmentId}', [PaymentController::class, 'show'])->name('patient.payment');
    Route::post('/payment/{appointmentId}/process', [PaymentController::class, 'process'])->name('patient.payment.process');

    // Chat/Video
    Route::get('/consultation/{id}', [PatientDashboardController::class, 'chatcall'])->name('patient.consultation');

    // Prescriptions
    Route::get('/prescriptions', [PrescriptionController::class, 'prescriptions'])->name('patient.prescriptions');

    // Feedback
    Route::get('/feedback/{appointmentId}', [FeedbackController::class, 'create'])->name('patient.feedback');
    Route::post('/feedback/store', [FeedbackController::class, 'store'])->name('patient.feedback.store');
Route::get('/my-profile', [PatientDashboardController::class, 'myprofile'])->name('patient.my-profile');
Route::post('/patient/my-profile/update', [PatientDashboardController::class, 'updateProfile'])->name('patient.update-profile');
 
    Route::get('/patient/payment/{id}', [PaymentController::class, 'showForm'])->name('patient.payment.form');
    Route::post('/patient/payment/{id}', [PaymentController::class, 'process'])->name('patient.payment.process');
Route::get('/patient/payment/receipt/{id}', [PaymentController::class, 'receipt'])->name('patient.payment.receipt');

});

Route::get('/appointments/slots/{year}/{month}', [PatientDashboardController::class, 'getSlots'])
    ->name('appointments.getSlots');

Route::get('/appointments/render-calendar/{year}/{month}', [PatientDashboardController::class, 'renderCalendar']);

Route::get('/appointments/day-slots/{date}', [PatientDashboardController::class, 'getDaySlots'])
    ->name('appointments.daySlots');

Route::get('/patient/notifications', [PatientDashboardController::class, 'notifications'])
     ->name('patient.notifications');
     
// -------------------- GLOBAL APPROVE/DENY --------------------
Route::put('/doctor/appointments/{id}/approve', [DoctorDashboardController::class, 'approveAppointment'])
    ->name('doctor.appointments.approve');

Route::post('/doctor/appointments/{id}/deny', [DoctorDashboardController::class, 'denyAppointment'])
    ->name('doctor.view-appointment.deny');

    // Fake GCash routes
Route::get('/gcash/mock/checkout/{id}', function($id) {
    $appt = \App\Models\Appointment::findOrFail($id);
    return view('mock.gcash-checkout', compact('appt'));
})->name('gcash.mock.checkout');

// Fake GCash "pay now" route
Route::post('/patient/payment/{id}/gcash/mock/pay', function($id) {
    $appt = \App\Models\Appointment::findOrFail($id);

    $appt->payment()->create([
        'user_id' => auth()->id(),
        'appointment_id' => $appt->id,
        'payment_method' => 'gcash',
        'amount' => $appt->amount ?? 500,
        'payment_status' => 'success',
        'reference_number' => strtoupper('GC-' . uniqid()),
    ]);

    return redirect()->route('patient.view-appointment')
        ->with('success', 'Mock GCash payment successful!');
})->name('gcash.mock.pay');

    Route::get('/messages/fetch/{receiver_id}', [MessageController::class, 'fetch']);
    Route::post('/messages/send', [MessageController::class, 'send'])->name('messages.send');
    Route::post('/messages/mark-read/{sender_id}', [MessageController::class, 'markRead']);
    Route::get('/messages/unread-counts', [MessageController::class, 'unreadCounts']);

Route::get('/messages/unread-counts', function () {
    $counts = \App\Models\Message::select('sender_id')
        ->where('receiver_id', auth()->id())
        ->where('is_read', false)
        ->get()
        ->groupBy('sender_id')
        ->map->count();
    return response()->json($counts);
});

Route::post('/messages/mark-read/{senderId}', function ($senderId) {
    \App\Models\Message::where('sender_id', $senderId)
        ->where('receiver_id', auth()->id())
        ->update(['is_read' => true]);
    return response()->json(['status' => 'ok']);
});

Route::get('/meeting/{uuid}', [PatientDashboardController::class, 'join'])->name('meeting.join');

Route::post('/doctor/start-call/{id}', [DoctorDashboardController::class, 'startCall'])->name('doctor.startCall');

Route::get('/patient/notifications/fetch', [NotificationController::class, 'fetch'])
    ->name('patient.notifications.fetch');

Route::post('/admin/update-patient-info', [AdminDashboardController::class, 'updatePatientInfo'])
    ->name('admin.updatePatientInfo');

Route::get('/doctor/notifications/fetch', [NotificationController::class, 'fetchdocnotif'])
    ->name('doctor.notifications.fetch');


Route::get('/doctor/chat/{receiver_id}', [ChatController::class, 'index'])->name('chat.index');
Route::post('/doctor/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');

Route::get('/patient/prescriptions/download/{id}', [PrescriptionController::class, 'downloadPrescription'])->name('patient.prescriptions.download');

Route::get('doctor/signature', [DoctorDashboardController::class, 'editSignature'])->name('doctor.signature.edit');
Route::post('doctor/myprofile', [DoctorDashboardController::class, 'updateSignature'])->name('doctor.signature.update');
