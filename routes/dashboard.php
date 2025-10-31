<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
| This file contains the dashboard routes
| Protected by middleware and user role admin
|
*/

Route::get('/',[Controllers\DashboardController::class,'index'])->name('dashboard');
Route::resource('roles',Controllers\RoleController::class);

Route::prefix('users')->group(function(){
    Route::resource('/user-management',Controllers\UserController::class)->names('users');
    Route::resource('profile',Controllers\ProfileController::class);
    Route::put('profile/password/{id}',[Controllers\ProfileController::class,'password'])->name('profile.password');

    Route::resource('physicians',Controllers\PhysicianController::class);
    Route::resource('clients',Controllers\ClientController::class);
    Route::resource('clients.contacts',Controllers\ClientContactController::class);
    Route::resource('nurses',Controllers\NurseController::class);
    Route::resource('patients',Controllers\PatientController::class);


    Route::delete('deleteImage/{id}',[Controllers\UserController::class,'deleteImage']);
});


Route::resource('enroll_patient',Controllers\EnrollPatientController::class);
Route::get('enroll_patient/physicians/{sum_program_id?}',[Controllers\EnrollPatientController::class,'physicians'])->name('enroll_patient.physicians');
Route::get('enroll_patient/nurses/{sum_program_id?}',[Controllers\EnrollPatientController::class,'nurses'])->name('enroll_patient.nurses');
Route::get('enroll_patient/coordinators/{sum_program_id?}',[Controllers\EnrollPatientController::class,'coordinators'])->name('enroll_patient.coordinators');
Route::get('enroll_patient/generate_patient_no/{country_id?}',[Controllers\EnrollPatientController::class,'generate_patient_no'])->name('enroll_patient.generate_patient_no');


Route::resource('dashboard_consent',Controllers\PatientConsentDashboardController::class);
Route::resource('countries',Controllers\CountryController::class);
Route::resource('hospitals',Controllers\HospitalController::class);
Route::resource('doctors',Controllers\DoctorController::class);
Route::resource('documenttypes',Controllers\DocumentTypeController::class);
Route::resource('specialties',Controllers\SpecialtyController::class);
Route::resource('programs',Controllers\ProgramController::class);
Route::get('programs/drugs/{client_id?}',[Controllers\ProgramController::class,'drugs'])->name('programs.drugs');
Route::get('programs/program_drugs/{program_id?}',[Controllers\ProgramController::class,'program_drugs'])->name('programs.program_drugs');
Route::resource('programs.contacts',Controllers\ProgramContactController::class);
Route::resource('programs.form-fields',Controllers\ProgramFormFieldController::class)->parameters([
    'form-fields' => 'form_field',
]);


Route::resource('programs.sub_programs',Controllers\SubProgramController::class);
Route::get('sub_programs/get_by_country/{country_id?}',[Controllers\SubProgramController::class,'getByCountryId'])->name('sub_programs_get_by_country');
Route::resource('programs.distributors',Controllers\DistributorController::class);
Route::resource('sub_programs.questions',Controllers\QuestionController::class);
Route::resource('sub_programs.service-providers',Controllers\SubProgramServiceProviderController::class);
Route::put('sub_programs/{sub_program_id}/service-providers/{id}/destroy-and-replace',[Controllers\SubProgramServiceProviderController::class,'destroy_and_replace'])->name('sub_programs.service-providers.destroy_and_replace');

Route::get('sub_programs/{sub_program_id}/timeline',[Controllers\SubProgramController::class,'showTimeLine'])->name('sub_programs.timeline.index');
Route::get('sub_programs/{sub_program_id}/visits/{date}/view',[Controllers\VisitScheduleController::class,'show'])->name('view_visit');
Route::get('sub_programs/{sub_program_id}/show',[Controllers\SubProgramController::class,'showByProgramId'])->name('show_sub_programs_by_program_id');
Route::get('sub_programs/{sub_program_id}/visits/{date}/create',[Controllers\VisitScheduleController::class,'create'])->name('create_visit');

Route::resource('sub_programs.patients',Controllers\SubProgramPatientController::class);
Route::resource('patients.visits',Controllers\PatientVisitController::class);
Route::get('sub_programs/{sub_program_id}/sub-program-patient/{sub_program_patient_id}/visits',[Controllers\PatientVisitController::class,'index'])->name('sub_programs.patients.visits.index');

Route::get('patients/sub-programs/{patient_id?}/',[Controllers\SubProgramPatientController::class,'get_sub_programs_by_patient_id'])->name('sub_programs.patients.get_sub_programs_by_patient_id');

Route::resource('sub_programs.visits',Controllers\VisitController::class);

Route::resource('sub_programs.foc',Controllers\FOCVisitController::class);
Route::resource('sub_programs.foc_time_line',Controllers\FOCVisitTimeLineController::class);
Route::resource('foc',Controllers\FOCController::class);



Route::resource('pharmacies',Controllers\PharmacyController::class);

Route::resource('drugs',Controllers\DrugController::class);


Route::resource('service-providers',Controllers\ServiceProviderController::class);
Route::resource('service-providers.certificates',Controllers\CertificateController::class);
Route::resource('service-providers.service_provider_types',Controllers\ServiceProviderTypeController::class);
Route::resource('service-types',Controllers\ServiceTypeController::class);




Route::resource('calls-and-visits-completed',Controllers\VisitCallCompletedController::class);
Route::resource('calls-and-visits-upcoming',Controllers\VisitCallUpcomingController::class);
Route::resource('safety-reports',Controllers\PatientSafetyReportController::class);
Route::get('safety-reports/sub-program-patients/{sub_program_id?}',[Controllers\PatientSafetyReportController::class,'sub_program_patients'])->name('safety-reports.sub_program_patients');
