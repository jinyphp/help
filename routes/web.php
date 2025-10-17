<?php

use Illuminate\Support\Facades\Route;

/**
 * FAQ (자주 묻는 질문) 사용자 페이지 라우트
 *
 * @description
 * 사용자가 접근할 수 있는 FAQ 기능을 제공합니다.
 */
Route::middleware('web')->prefix('faq')->name('faq.')->group(function () {
    // FAQ 메인 페이지
    Route::get('/', \Jiny\Help\Http\Controllers\Site\Faq\IndexController::class)
        ->name('index');

    // FAQ 검색
    Route::get('/search', \Jiny\Help\Http\Controllers\Site\Faq\SearchController::class)
        ->name('search');

    // FAQ 카테고리별 목록
    Route::get('/category/{code}', \Jiny\Help\Http\Controllers\Site\Faq\CategoryController::class)
        ->name('category');
});

/**
 * Help Center (도움말 센터) 사용자 페이지 라우트
 *
 * @description
 * 사용자가 접근할 수 있는 Help Center 기능을 제공합니다.
 * Help, Guide, Support 모든 기능을 통합 제공합니다.
 */
Route::middleware('web')->prefix('help')->name('help.')->group(function () {
    // Help 메인 페이지
    Route::get('/', \Jiny\Help\Http\Controllers\Site\Help\IndexController::class)
        ->name('index');

    // Help 검색
    Route::get('/search', \Jiny\Help\Http\Controllers\Site\Help\SearchController::class)
        ->name('search');

    // Help FAQ
    Route::get('/faq', \Jiny\Help\Http\Controllers\Site\Help\Faq\IndexController::class)
        ->name('faq');

    // Help 가이드
    Route::get('/guide', \Jiny\Help\Http\Controllers\Site\Help\Guide\IndexController::class)
        ->name('guide');

    // Help 가이드 상세
    Route::get('/guide/{id}', \Jiny\Help\Http\Controllers\Site\Help\Guide\ShowController::class)
        ->name('guide.single')
        ->where('id', '[0-9]+');

    // Help 가이드 좋아요
    Route::post('/guide/{id}/like', \Jiny\Help\Http\Controllers\Site\Help\Guide\LikeController::class)
        ->name('guide.like')
        ->where('id', '[0-9]+');

    // Help 고객지원
    Route::match(['GET', 'POST'], '/support', \Jiny\Help\Http\Controllers\Site\Help\Support\IndexController::class)
        ->name('support');

    // 지원 요청 성공 페이지
    Route::get('/support/success', \Jiny\Help\Http\Controllers\Site\Help\Support\SuccessController::class)
        ->name('support.success');

    // 내 지원 요청 목록 (로그인 필요)
    Route::get('/support/my', \Jiny\Help\Http\Controllers\Site\Help\Support\MyController::class)
        ->name('support.my')
        ->middleware('auth');

    // 내 지원 요청 상세 보기 (로그인 필요)
    Route::get('/support/my/{id}', \Jiny\Help\Http\Controllers\Site\Help\Support\ShowController::class)
        ->name('support.show')
        ->middleware('auth')
        ->where('id', '[0-9]+');

    // 지원 요청 평가 제출 (로그인 필요)
    Route::post('/support/{id}/evaluate', \Jiny\Help\Http\Controllers\Site\Help\Support\EvaluationController::class)
        ->name('support.evaluate')
        ->middleware('auth')
        ->where('id', '[0-9]+');

    // 사용자 직접 지원 요청 종료 (로그인 필요)
    Route::post('/support/{id}/close', \Jiny\Help\Http\Controllers\Site\Help\Support\CloseController::class)
        ->name('support.close')
        ->where('id', '[0-9]+');

    // 고객 추가 문의 제출 (로그인 필요)
    Route::post('/support/{id}/reply', \Jiny\Help\Http\Controllers\Site\Help\Support\CustomerReplyController::class)
        ->name('support.customer.reply')
        ->middleware('auth')
        ->where('id', '[0-9]+');

    // 지원 요청 수정 (로그인 필요)
    Route::match(['GET', 'POST'], '/support/{id}/edit', \Jiny\Help\Http\Controllers\Site\Help\Support\EditController::class)
        ->name('support.edit')
        ->middleware('auth')
        ->where('id', '[0-9]+');

    // 지원 요청 삭제 (로그인 필요)
    Route::delete('/support/{id}/delete', \Jiny\Help\Http\Controllers\Site\Help\Support\DeleteController::class)
        ->name('support.delete')
        ->middleware('auth')
        ->where('id', '[0-9]+');

    // Help 카테고리별 목록
    Route::get('/category/{code}', \Jiny\Help\Http\Controllers\Site\Help\CategoryController::class)
        ->name('category');

    // Help 상세 페이지
    Route::get('/{id}', \Jiny\Help\Http\Controllers\Site\Help\DetailController::class)
        ->name('detail')
        ->where('id', '[0-9]+');

    // Help 좋아요 기능
    Route::post('/{id}/like', function($id) {
        // 좋아요 처리 로직
        DB::table('site_help')->where('id', $id)->increment('like');
        return response()->json(['success' => true]);
    })->name('like')
      ->where('id', '[0-9]+');
});