<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'access_rules:company_owner,admin'])->group(function () {
    
    Route::get('my-company', [CompanyController::class, 'show'])->name('my-company.show');
    Route::get('my-company/edit', [CompanyController::class, 'edit'])->name('my-company.edit');
    Route::put('my-company', [CompanyController::class, 'updateOwned'])->name('my-company.update');
    Route::get('my-job-vacancies', [JobVacancyController::class, 'index'])->name('my-job-vacancies.index');
    Route::get('my-job-vacancies/{jobVacancy}/edit', [JobVacancyController::class, 'editVacancyByOwner'])
        ->name('my-job-vacancies.edit');
    Route::get('my-job-vacancies/create', [JobVacancyController::class, 'create'])
        ->name('my-job-vacancies.create');
    Route::post('my-job-vacancies', [JobVacancyController::class, 'store'])
        ->name('my-job-vacancies.store');
    Route::put('my-job-vacancies/{jobVacancy}/update', [JobVacancyController::class, 'updateVacancyByOwner'])
        ->name('my-job-vacancies.update');
    Route::resource('job-applications', JobApplicationController::class);
    Route::get('/dashboard', [DashBoardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        if (Auth::user()->role === 'admin') {
            return to_route('dashboard');
        } elseif (Auth::user()->role === 'company_owner') {
            return to_route('my-company.show');
        } else {
            return abort(403);
        }
    });
    Route::middleware('access_rules:admin')->group(function () {
        Route::resource('companies', CompanyController::class);
        Route::resource('job-categories', JobCategoryController::class);
        Route::resource('users', UserController::class);
        Route::resource('job-vacancies', JobVacancyController::class);
        Route::put('job-category/{id}/restore', [JobCategoryController::class, 'restore'])->name('job-categories.restore');
        Route::put('company/{id}/restore', [CompanyController::class, 'restore'])->name('companies.restore');
        Route::put('job-application/{id}/restore', [JobApplicationController::class, 'restore'])->name('job-applications.restore');
        Route::put('job-vacancy/{id}/restore', [JobVacancyController::class, 'restore'])->name('job-vacancies.restore');
        Route::put('user/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route::get('/test-router', function () {
//     $prompt = "Write a one-sentence encouraging message for a new job seeker.";

//     $response = Http::withToken(env('OPENROUTER_API_KEY'))
//         ->withHeaders([
//             'HTTP-Referer' => 'http://localhost:8000', // Optional: for OpenRouter rankings
//             'X-Title' => 'My Job App', // Optional: name of your app
//         ])
//         ->post('https://openrouter.ai/api/v1/chat/completions', [
//             'model' => 'inclusionai/ling-3.0-flash-sante:free', // Example of a free model on OpenRouter
//             'messages' => [
//                 [
//                     'role' => 'user',
//                     'content' => $prompt
//                 ]
//             ]
//         ]);

//     if ($response->failed()) {
//         return "Error: " . $response->body();
//     }

//     $aiTextString = $response->json('choices.0.message.content');

//     return response($aiTextString, 200)
//         ->header('Content-Type', 'text/plain');
// });

Route::get('factory', function () {
    $ids = [];
    $users = User::all();
    foreach ($users as $user) {
        $ids[] = $user->id;
    }

    // return fake()->randomElement($ids);
    return fake()->randomFloat(1, 0, 10);
});

Route::get('test', function () {
    $companyId = Auth::user()->companies()->first()->id;
    // $mostActiveUsers = User::where('last_login_at', '>=', now()->subDays(30))
    //         ->where('role', 'job_seeker')->whereHas('jobVacancies', function ($vacancy) use ($companyId) {
    //             return $vacancy->where('company_id', $companyId);
    //         })->count();
    $mostActiveUsers = Company::count();

    return $mostActiveUsers;
});

require __DIR__.'/auth.php';
