<?php

use App\Livewire\AIChat;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ModuleDownloadController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\FacultyController as AdminFacultyController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ModuleController as AdminModuleController;
use App\Http\Controllers\Admin\ReportController;

// USER AUTHENTICATION ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])
        ->middleware('auth')
        ->name('');
});


// MODULE DOWNLOAD
Route::middleware('auth')->group(function () {
    Route::get('/modules/{id}/download', [ModuleDownloadController::class, 'downloadModule'])
        ->middleware('auth')
        ->name('modules.download');
});

// STUDENT ROUTES
Route::middleware(['auth'])->group(function () {

    Route::get('/student', [ModuleController::class, 'browseModules'])
        ->middleware(['auth', 'verified'])
        ->name('student');

    Route::get('/module/{module}', [ModuleController::class, 'viewModule'])
        ->middleware(['auth', 'verified'])
        ->name('view-module');



    Route::get('student-profile', [StudentController::class, 'showProfile'])
        ->middleware(['auth', 'verified'])
        ->name('student-profile');

    Route::get('/student/back', [StudentController::class, 'back'])
        ->middleware(['auth', 'verified'])
        ->name('student.back');

    Route::get('/student/change-password', [StudentController::class, 'changePasswordView'])
        ->middleware(['auth', 'verified'])
        ->name('student.change.password.view');

    Route::get('/student/history', \App\Livewire\DownloadHistory::class)
        ->middleware(['auth', 'verified'])
        ->name('student.history');
});

// DOWNLOAD ROUTES
Route::middleware(['auth'])->group(function () {
    Route::get('/download/file/{token}', function ($token) {
        $downloadInfo = session()->get('pending_download_' . $token);

        if (!$downloadInfo) {
            abort(404, 'Download session expired.');
        }

        $filePath = $downloadInfo['file_path'];
        $fileName = $downloadInfo['file_name'];

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        // Clear the session
        session()->forget('pending_download_' . $token);

        return response()->download($filePath, $fileName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    })->name('download.file');
});

// FACULTY ROUTES
Route::middleware('auth')->group(function () {
    Route::get('faculty/home', [FacultyController::class, 'homeView'])
        ->middleware(['auth', 'verified'])
        ->name('faculty.home');

    Route::get('/faculty/module/{module}', [ModuleController::class, 'viewModule'])
        ->middleware(['auth', 'verified'])
        ->name('faculty.view-module');

    Route::get('faculty-profile', [FacultyController::class, 'showProfile'])
        ->middleware(['auth', 'verified'])
        ->name('faculty-profile');

    Route::get('faculty/module/edit/{module}', [FacultyController::class, 'editModuleView'])
        ->middleware(['auth', 'verified'])
        ->name('faculty.module.edit');

    Route::get('facilty/change-password', [FacultyController::class, 'changePasswordView'])
        ->middleware(['auth', 'verified'])
        ->name('faculty.change.password');

    Route::post('faculty/module/create', [ModuleController::class, 'createModule'])
        ->middleware(['auth', 'verified'])
        ->name('faculty.module.create');

    Route::delete('delete-module/{module}', [ModuleController::class, 'deleteModule'])
        ->middleware(['auth', 'verified'])
        ->name('delete-module');

    Route::put('faculty/module/update/{module}', [FacultyController::class, 'updateModule'])
        ->middleware(['auth', 'verified'])
        ->name('faculty.module.update');

    Route::get('faculty/create/module', [ModuleController::class, 'createModuleView'])
        ->middleware(['auth', 'verified'])
        ->name('faculty.module.create.view');

    Route::get('faculty/modules/create-multiple', [ModuleController::class, 'createMultipleModuleView'])
        ->name('faculty.module.create-multiple');
    Route::post('faculty/modules/create-multiple', [ModuleController::class, 'createMultipleModules'])
        ->name('faculty.module.store-multiple');
});

// ADMIN ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'dashboard'])
        ->middleware(['auth', 'verified'])
        ->name('admin.dashboard');

    Route::get('/admin/degree-program', [CourseController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('admin.degree-program');

    Route::get('/admin/degree-program/create', [CourseController::class, 'create'])
        ->middleware(['auth', 'verified'])
        ->name('admin.degree-program.create');

    Route::delete('/admin/degree-program/delete/{course}', [CourseController::class, 'destroy'])
        ->middleware(['auth', 'verified'])
        ->name('admin.degree-program.delete');

    Route::post('/admin/degree-program/create', [CourseController::class, 'store'])
        ->middleware(['auth', 'verified'])
        ->name('admin.create.degree-program');

    Route::put('/admin/degree-program/update/{course}', [CourseController::class, 'update'])
        ->middleware(['auth', 'verified'])
        ->name('admin.degree-program.update');

    Route::get('/admin/degree-program/edit/{course}', [CourseController::class, 'edit'])
        ->middleware(['auth', 'verified'])
        ->name('admin.degree-program.edit');

    Route::get('/admin/faculties', [AdminFacultyController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('admin.faculties');

    Route::get('/admin/modules', [AdminModuleController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('admin.modules');

    Route::get('/admin/students', [AdminStudentController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('admin.students');

    Route::get('/admin/departments', [DepartmentController::class, 'index'])
        ->middleware(['auth', 'verified'])
        ->name('admin.departments');

    Route::get('/admin/department/add', [DepartmentController::class, 'create'])
        ->middleware(['auth', 'verified'])
        ->name('admin.add.department');

    Route::delete('/admin/department/delete/{department}', [DepartmentController::class, 'destroy'])
        ->middleware(['auth', 'verified'])
        ->name('admin.delete.department');

    Route::get('/admin/department/edit/{department}', [DepartmentController::class, 'edit'])
        ->middleware(['auth', 'verified'])
        ->name('admin.department.edit');

    Route::put('/admin/department/update/{department}', [DepartmentController::class, 'update'])
        ->middleware(['auth', 'verified'])
        ->name('admin.department.update');

    Route::post('/admin/department/create', [DepartmentController::class, 'store'])
        ->middleware(['auth', 'verified'])
        ->name('admin.create.department');

    Route::put('/admin/module/update/{module}', [AdminModuleController::class, 'update'])
        ->middleware(['auth', 'verified'])
        ->name('admin.module.update');

    Route::patch('/admin/module/update-status/{module}', [AdminModuleController::class, 'updateStatus'])
        ->middleware(['auth', 'verified'])
        ->name('admin.module.update-status');

    Route::delete('/admin/delete-module/{module}', [ModuleController::class, 'deleteModule'])
        ->middleware(['auth', 'verified'])
        ->name('admin.delete-module');

    Route::get('/admin/module/create', [AdminModuleController::class, 'create'])
        ->middleware(['auth', 'verified'])
        ->name('admin.module.create');

    Route::post('/admin/module/create', [AdminModuleController::class, 'store'])
        ->middleware(['auth', 'verified'])
        ->name('admin.module.store');

    Route::post('/admin/module/create-multiple', [AdminModuleController::class, 'storeMultiple'])
        ->middleware(['auth', 'verified'])
        ->name('admin.module.store-multiple');


    // Wait, `AdminController::createMultipleModules` doesn't exist in my extraction,
    // let me fall back to AdminModuleController, even if I missed it, wait, AdminController didn't have createMultipleModules implementation in the context...
    // The web.php points to AdminController::class, 'createMultipleModules'
    // But AdminController didn't have it (or I missed it). I'll point to AdminModuleController assuming it'll be added or was inherited. Wait, `AdminController` had `createMultipleModules`? Let me check previous `AdminController.php` output. No, `createMultipleModules` was not in `AdminController`. Let's just point to `ModuleController::class` or leave it. Actually the old route was: `[AdminController::class, 'createMultipleModules']`
    // I will comment it out or point it to ModuleController, since ModuleController has `createMultipleModules`.

    Route::get('/admin/module/edit/{module}', [AdminModuleController::class, 'edit'])
        ->middleware(['auth', 'verified'])
        ->name('admin.module.edit');

    Route::delete('/admin/delete-student', [AdminStudentController::class, 'destroy'])
        ->middleware(['auth', 'verified'])
        ->name('admin.delete-student');

    Route::delete('/admin/delete-faculty', [AdminFacultyController::class, 'destroy'])
        ->middleware(['auth', 'verified'])
        ->name('admin.delete-faculty');

    Route::get('/admin-profile', [DashboardController::class, 'showProfile'])
        ->middleware(['auth', 'verified'])
        ->name('admin-profile');

    Route::get('/admin-change-password', [DashboardController::class, 'changePasswordView'])
        ->middleware(['auth', 'verified'])
        ->name('admin-change-password');

    Route::get('/admin/student/create', [AdminStudentController::class, 'create'])
        ->middleware(['auth', 'verified'])
        ->name('admin.student.create');

    Route::get('/admin-add-student', function () {
        return view('admin.partials.admin_add_student');
    })
        ->middleware(['auth', 'verified'])
        ->name('admin-add-student');

    Route::post('/admin/add-student', [AdminStudentController::class, 'store'])
        ->middleware(['auth', 'verified'])
        ->name('admin.add-student');

    Route::post('/admin/students/import', [AdminStudentController::class, 'import'])
        ->middleware(['auth', 'verified'])
        ->name('admin.students.import');

    Route::get('/admin/faculty/create', [AdminFacultyController::class, 'create'])
        ->middleware(['auth', 'verified'])
        ->name('admin.faculty.create');

    Route::post('/admin/add-faculty', [AdminFacultyController::class, 'store'])
        ->middleware(['auth', 'verified'])
        ->name('admin.add-faculty');

    Route::post('/admin/faculties/import', [AdminFacultyController::class, 'import'])
        ->middleware(['auth', 'verified'])
        ->name('admin.faculties.import');

    Route::get('/admin-edit-faculty/{faculty}', [AdminFacultyController::class, 'edit'])
        ->middleware(['auth', 'verified'])
        ->name('admin-edit-faculty');

    Route::put('/admin/admin/{admin}', [DashboardController::class, 'updateAdmin'])
        ->middleware(['auth', 'verified'])
        ->name('admin.update-admin');

    Route::put('/admin/faculty/{faculty}', [AdminFacultyController::class, 'update'])
        ->middleware(['auth', 'verified'])
        ->name('admin.update-faculty');

    Route::get('/admin-edit-student/{student}', [AdminStudentController::class, 'edit'])
        ->middleware(['auth', 'verified'])
        ->name('admin-edit-student');

    Route::put('/admin/student/{student}', [AdminStudentController::class, 'update'])
        ->middleware(['auth', 'verified'])
        ->name('admin.update-student');

    Route::delete('/admin/profile/photo', [DashboardController::class, 'deletePhoto'])
        ->middleware(['auth', 'verified'])
        ->name('admin.delete-photo');

    Route::get('/admin/students/print-all', [ReportController::class, 'printAllStudents'])
        ->middleware(['auth', 'verified'])
        ->name('admin.students.print');

    Route::get('/admin/faculties/print-all', [ReportController::class, 'printAllFaculties'])
        ->middleware(['auth', 'verified'])
        ->name('admin.faculties.print');

    Route::get('/admin/modules/print-all', [ReportController::class, 'printAllModules'])
        ->middleware(['auth', 'verified'])
        ->name('admin.modules.print');

    Route::get('/admin/departments/print-all', [ReportController::class, 'printAllDepartments'])
        ->middleware(['auth', 'verified'])
        ->name('admin.departments.print');

    Route::get('/admin/courses/print-all', [ReportController::class, 'printAllCourses'])
        ->middleware(['auth', 'verified'])
        ->name('admin.courses.print');

    Route::get('/admin/departments-module/print', [ReportController::class, 'printDepartmentsModule'])
        ->middleware(['auth', 'verified'])
        ->name('admin.departments-module.print');

    // Reports routes
    Route::get('/reports/summary', [ReportController::class, 'summary'])->name('reports.summary');
    Route::get('/reports/course/{course}', [ReportController::class, 'individual'])->name('reports.individual');
    Route::get('/reports/print/summary', [ReportController::class, 'printSummary'])->name('reports.print.summary');
    Route::get('/reports/print/course/{course}', [ReportController::class, 'printIndividual'])->name('reports.print.individual');
});

// USER PROFILE
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
});

// MODULE ROUTES
Route::post('/create_module', [ModuleController::class, 'createModule']);

// Chunked upload routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/upload-chunk', [ModuleController::class, 'uploadChunk'])->name('upload-chunk');
    Route::get('/upload-status', [ModuleController::class, 'uploadStatus'])->name('upload-status');
});

// API: Get degree programs by department (used for AJAX filtering)
Route::get('/api/departments/{department}/courses', [CourseController::class, 'getByDepartment'])
    ->name('api.departments.courses');

// API: Search students for enrollment
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/api/students/search', [ModuleController::class, 'searchStudents'])
        ->name('api.students.search');
    Route::post('/api/module/enroll-students', [ModuleController::class, 'enrollStudents'])
        ->name('api.module.enroll');
    Route::delete('/api/module/enrollment/{enrollment}', [ModuleController::class, 'removeEnrollment'])
        ->name('api.module.enrollment.remove');
});

require __DIR__ . '/auth.php';
