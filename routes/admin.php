<?php

use Illuminate\Support\Facades\Route;

/**
 * Help Center 관리 라우트
 *
 * @description
 * Help Center 시스템을 관리하는 라우트입니다.
 * Help, FAQ, Support, Guide 모든 기능을 통합 관리합니다.
 */

/**
 * Help 관리 라우트
 *
 * @description
 * 도움말 시스템을 관리하는 라우트입니다.
 */
Route::prefix('help')->name('admin.cms.help.')->group(function () {
    // Help Center 대시보드
    Route::get('/', \Jiny\Help\Http\Controllers\Admin\Help\Dashboard\IndexController::class)
        ->name('dashboard');

    // Help 카테고리 관리
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', \Jiny\Help\Http\Controllers\Admin\Help\Category\IndexController::class)->name('index');
        Route::get('/create', \Jiny\Help\Http\Controllers\Admin\Help\Category\CreateController::class)->name('create');
        Route::post('/', \Jiny\Help\Http\Controllers\Admin\Help\Category\StoreController::class)->name('store');
        Route::get('/{id}/edit', \Jiny\Help\Http\Controllers\Admin\Help\Category\EditController::class)->name('edit');
        Route::put('/{id}', \Jiny\Help\Http\Controllers\Admin\Help\Category\UpdateController::class)->name('update');
        Route::delete('/{id}', \Jiny\Help\Http\Controllers\Admin\Help\Category\DestroyController::class)->name('destroy');
        Route::post('/update-order', \Jiny\Help\Http\Controllers\Admin\Help\Category\UpdateOrderController::class)->name('updateOrder');
    });

    // Help 문서 관리
    Route::prefix('docs')->name('docs.')->group(function () {
        Route::get('/', \Jiny\Help\Http\Controllers\Admin\Help\Docs\IndexController::class)->name('index');
        Route::get('/create', \Jiny\Help\Http\Controllers\Admin\Help\Docs\CreateController::class)->name('create');
        Route::post('/', \Jiny\Help\Http\Controllers\Admin\Help\Docs\StoreController::class)->name('store');
        Route::get('/{id}', \Jiny\Help\Http\Controllers\Admin\Help\Docs\ShowController::class)->name('show');
        Route::get('/{id}/edit', \Jiny\Help\Http\Controllers\Admin\Help\Docs\EditController::class)->name('edit');
        Route::put('/{id}', \Jiny\Help\Http\Controllers\Admin\Help\Docs\UpdateController::class)->name('update');
        Route::delete('/{id}', \Jiny\Help\Http\Controllers\Admin\Help\Docs\DestroyController::class)->name('destroy');
    });
});

/**
 * FAQ 관리 라우트
 *
 * @description
 * 자주 묻는 질문(FAQ)을 관리하는 라우트입니다.
 * Single Action Controllers 방식으로 구현됨
 */
Route::prefix('faq')->name('admin.cms.faq.')->group(function () {
    // FAQ 카테고리 관리 (Single Action Controllers)
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', \Jiny\Help\Http\Controllers\Admin\Faq\Categories\IndexController::class)->name('index');
        Route::get('/create', \Jiny\Help\Http\Controllers\Admin\Faq\Categories\CreateController::class)->name('create');
        Route::post('/', \Jiny\Help\Http\Controllers\Admin\Faq\Categories\StoreController::class)->name('store');
        Route::get('/{id}/edit', \Jiny\Help\Http\Controllers\Admin\Faq\Categories\EditController::class)->name('edit');
        Route::put('/{id}', \Jiny\Help\Http\Controllers\Admin\Faq\Categories\UpdateController::class)->name('update');
        Route::delete('/{id}', \Jiny\Help\Http\Controllers\Admin\Faq\Categories\DestroyController::class)->name('destroy');
        Route::post('/update-order', \Jiny\Help\Http\Controllers\Admin\Faq\Categories\UpdateOrderController::class)->name('updateOrder');
    });

    // FAQ 관리 (Single Action Controllers)
    Route::prefix('faqs')->name('faqs.')->group(function () {
        Route::get('/', \Jiny\Help\Http\Controllers\Admin\Faq\Faqs\IndexController::class)->name('index');
        Route::get('/create', \Jiny\Help\Http\Controllers\Admin\Faq\Faqs\CreateController::class)->name('create');
        Route::post('/', \Jiny\Help\Http\Controllers\Admin\Faq\Faqs\StoreController::class)->name('store');
        Route::get('/{id}', \Jiny\Help\Http\Controllers\Admin\Faq\Faqs\ShowController::class)->name('show');
        Route::get('/{id}/edit', \Jiny\Help\Http\Controllers\Admin\Faq\Faqs\EditController::class)->name('edit');
        Route::put('/{id}', \Jiny\Help\Http\Controllers\Admin\Faq\Faqs\UpdateController::class)->name('update');
        Route::delete('/{id}', \Jiny\Help\Http\Controllers\Admin\Faq\Faqs\DestroyController::class)->name('destroy');
        Route::post('/bulk-action', \Jiny\Help\Http\Controllers\Admin\Faq\Faqs\BulkActionController::class)->name('bulkAction');
        Route::post('/update-order', \Jiny\Help\Http\Controllers\Admin\Faq\Faqs\UpdateOrderController::class)->name('updateOrder');
    });
});

/**
 * Support (지원 요청) 관리 라우트
 *
 * @description
 * 고객 지원 요청을 관리하는 라우트입니다.
 */
Route::prefix('support')->name('admin.cms.support.')->group(function () {

    // 메인 대시보드 (Analytics Dashboard)
    Route::get('/', \Jiny\Help\Http\Controllers\Admin\Support\Dashboard\IndexController::class)->name('index');

    // 일괄 작업 (Bulk Action)
    Route::post('/bulk-action', \Jiny\Help\Http\Controllers\Admin\Support\Requests\BulkActionController::class)->name('bulkAction');

    // 지원 요청 관리 (Requests Management)
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', \Jiny\Help\Http\Controllers\Admin\Support\Requests\IndexController::class)->name('index');
        Route::get('/{id}', \Jiny\Help\Http\Controllers\Admin\Support\Requests\ShowController::class)->name('show')->where(['id' => '[0-9]+']);
        Route::match(['GET', 'POST'], '/{id}/edit', \Jiny\Help\Http\Controllers\Admin\Support\Requests\EditController::class)->name('edit')->where(['id' => '[0-9]+']);
        Route::delete('/{id}', \Jiny\Help\Http\Controllers\Admin\Support\Requests\DeleteController::class)->name('delete')->where(['id' => '[0-9]+']);
        Route::post('/bulk-action', \Jiny\Help\Http\Controllers\Admin\Support\Requests\BulkActionController::class)->name('bulkAction');

        // 파일 업로드 관리
        Route::prefix('{supportId}/files')->name('file.')->group(function () {
            Route::post('/upload', [\Jiny\Help\Http\Controllers\Admin\Support\FileUploadController::class, 'upload'])->name('upload');
            Route::get('/list', [\Jiny\Help\Http\Controllers\Admin\Support\FileUploadController::class, 'list'])->name('list');
            Route::get('/{fileIndex}/download', [\Jiny\Help\Http\Controllers\Admin\Support\FileUploadController::class, 'download'])->name('download');
            Route::delete('/{fileIndex}', [\Jiny\Help\Http\Controllers\Admin\Support\FileUploadController::class, 'delete'])->name('delete');
        });

        // 할당 관리
        Route::post('/{id}/self-assign', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\AssignmentController::class, 'selfAssign'])->name('selfAssign')->where(['id' => '[0-9]+']);
        Route::post('/{id}/assign', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\AssignmentController::class, 'assign'])->name('assign')->where(['id' => '[0-9]+']);
        Route::post('/{id}/transfer', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\AssignmentController::class, 'transfer'])->name('transfer')->where(['id' => '[0-9]+']);
        Route::post('/{id}/unassign', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\AssignmentController::class, 'unassign'])->name('unassign')->where(['id' => '[0-9]+']);
        Route::get('/{id}/assignment-history', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\AssignmentController::class, 'history'])->name('assignmentHistory')->where(['id' => '[0-9]+']);
        Route::get('/admins', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\AssignmentController::class, 'getAdmins'])->name('getAdmins');

        // 다중 할당 관리 (Multiple Assignment Management)
        Route::prefix('{id}/multiple-assignments')->name('multipleAssignment.')->where(['id' => '[0-9]+'])->group(function () {
            Route::post('/', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\MultipleAssignmentController::class, 'assign'])->name('assign');
            Route::get('/', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\MultipleAssignmentController::class, 'list'])->name('list');
            Route::delete('/{assignmentId}', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\MultipleAssignmentController::class, 'deactivate'])->name('deactivate')->where(['assignmentId' => '[0-9]+']);
            Route::put('/{assignmentId}/role', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\MultipleAssignmentController::class, 'changeRole'])->name('changeRole')->where(['assignmentId' => '[0-9]+']);
            Route::get('/available-admins', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\MultipleAssignmentController::class, 'getAvailableAdmins'])->name('getAvailableAdmins');
            Route::get('/history', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\MultipleAssignmentController::class, 'history'])->name('history');
        });

        // 상태 변경 (AJAX)
        Route::post('/{id}/update-status', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\StatusController::class, 'update'])->name('updateStatus')->where(['id' => '[0-9]+']);
        Route::post('/{id}/complete', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\StatusController::class, 'complete'])->name('complete')->where(['id' => '[0-9]+']);
        Route::post('/{id}/close', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\StatusController::class, 'close'])->name('close')->where(['id' => '[0-9]+']);
        Route::post('/{id}/reopen', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\StatusController::class, 'reopen'])->name('reopen')->where(['id' => '[0-9]+']);
        Route::get('/{id}/status-info', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\StatusController::class, 'getStatusInfo'])->name('statusInfo')->where(['id' => '[0-9]+']);

        // 답변 관리 (Reply Management)
        Route::prefix('{id}/replies')->name('reply.')->where(['id' => '[0-9]+'])->group(function () {
            Route::post('/', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\ReplyController::class, 'store'])->name('store');
            Route::get('/list', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\ReplyController::class, 'list'])->name('list');
            Route::put('/{replyId}', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\ReplyController::class, 'update'])->name('update')->where(['replyId' => '[0-9]+']);
            Route::delete('/{replyId}', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\ReplyController::class, 'destroy'])->name('destroy')->where(['replyId' => '[0-9]+']);
            Route::post('/{replyId}/read', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\ReplyController::class, 'markAsRead'])->name('markAsRead')->where(['replyId' => '[0-9]+']);
            Route::get('/{replyId}/download/{attachmentIndex}', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\ReplyController::class, 'downloadAttachment'])->name('download')->where(['replyId' => '[0-9]+', 'attachmentIndex' => '[0-9]+']);
        });

        // 평가 관리 (Evaluation Management)
        Route::prefix('{id}/evaluations')->name('evaluation.')->where(['id' => '[0-9]+'])->group(function () {
            Route::get('/', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\EvaluationController::class, 'index'])->name('index');
            Route::get('/create', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\EvaluationController::class, 'create'])->name('create');
            Route::post('/', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\EvaluationController::class, 'store'])->name('store');
            Route::get('/{evaluationId}', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\EvaluationController::class, 'show'])->name('show')->where(['evaluationId' => '[0-9]+']);
        });

        // 평가 통계 (Evaluation Statistics)
        Route::prefix('evaluations')->name('evaluation.')->group(function () {
            Route::get('/admin-stats/{adminId?}', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\EvaluationController::class, 'getAdminStats'])->name('adminStats')->where(['adminId' => '[0-9]+']);
            Route::get('/ranking', [\Jiny\Help\Http\Controllers\Admin\Support\Requests\EvaluationController::class, 'getRanking'])->name('ranking');
        });
    });

    // 평가 통계 대시보드 (Evaluation Statistics Dashboard)
    Route::prefix('statistics/evaluations')->name('statistics.evaluations.')->group(function () {
        Route::get('/', [\Jiny\Help\Http\Controllers\Admin\Support\Statistics\EvaluationStatsController::class, 'index'])->name('index');
        Route::get('/ranking', [\Jiny\Help\Http\Controllers\Admin\Support\Statistics\EvaluationStatsController::class, 'ranking'])->name('ranking');
        Route::get('/stats/{adminId?}', [\Jiny\Help\Http\Controllers\Admin\Support\Statistics\EvaluationStatsController::class, 'getStats'])->name('getStats')->where(['adminId' => '[0-9]+']);
        Route::post('/compare', [\Jiny\Help\Http\Controllers\Admin\Support\Statistics\EvaluationStatsController::class, 'compareAdmins'])->name('compare');
        Route::get('/report', [\Jiny\Help\Http\Controllers\Admin\Support\Statistics\EvaluationStatsController::class, 'generateReport'])->name('report');
    });

    // 내보내기 (Export)
    Route::get('/export', \Jiny\Help\Http\Controllers\Admin\Support\Export\IndexController::class)->name('export');

    // 템플릿 관리 (Templates Management)
    Route::prefix('templates')->name('templates.')->group(function () {
        Route::get('/', \Jiny\Help\Http\Controllers\Admin\Support\Templates\IndexController::class)->name('index');
        Route::get('/search', [\Jiny\Help\Http\Controllers\Admin\Support\TemplateController::class, 'search'])->name('search');
        Route::get('/{templateKey}', [\Jiny\Help\Http\Controllers\Admin\Support\TemplateController::class, 'show'])->name('show');
        Route::get('/{templateKey}/preview', [\Jiny\Help\Http\Controllers\Admin\Support\TemplateController::class, 'preview'])->name('preview');
        Route::post('/process', [\Jiny\Help\Http\Controllers\Admin\Support\TemplateController::class, 'process'])->name('process');
    });

    // 지원 요청 유형 관리 (Support Types Management)
    Route::prefix('types')->name('types.')->group(function () {
        Route::get('/', \Jiny\Help\Http\Controllers\Admin\Support\Types\IndexController::class)->name('index');
        Route::get('/create', \Jiny\Help\Http\Controllers\Admin\Support\Types\CreateController::class)->name('create');
        Route::post('/', \Jiny\Help\Http\Controllers\Admin\Support\Types\StoreController::class)->name('store');
        Route::get('/{id}', \Jiny\Help\Http\Controllers\Admin\Support\Types\ShowController::class)->name('show')->where('id', '[0-9]+');
        Route::get('/{id}/edit', \Jiny\Help\Http\Controllers\Admin\Support\Types\EditController::class)->name('edit')->where('id', '[0-9]+');
        Route::put('/{id}', \Jiny\Help\Http\Controllers\Admin\Support\Types\UpdateController::class)->name('update')->where('id', '[0-9]+');
        Route::delete('/{id}', \Jiny\Help\Http\Controllers\Admin\Support\Types\DestroyController::class)->name('destroy')->where('id', '[0-9]+');
        Route::post('/bulk-action', \Jiny\Help\Http\Controllers\Admin\Support\Types\BulkActionController::class)->name('bulk-action');
        Route::post('/update-order', \Jiny\Help\Http\Controllers\Admin\Support\Types\UpdateOrderController::class)->name('update-order');
    });

    // 분석 및 통계 (Additional Analytics - 확장용)
    Route::prefix('analytics')->name('analytics.')->group(function () {
        // 추가 분석 기능이 필요한 경우 여기에 추가
        // Route::get('/reports', \Jiny\Help\Http\Controllers\Admin\Support\Analytics\ReportsController::class)->name('reports');
        // Route::get('/trends', \Jiny\Help\Http\Controllers\Admin\Support\Analytics\TrendsController::class)->name('trends');
    });
});

/**
 * Support 할당 관리 라우트
 *
 * @description
 * 기술지원 요청 할당 시스템을 관리하는 라우트입니다.
 */
Route::prefix('../support')->name('admin.support.')->group(function () {
    // 내 할당 요청
    Route::get('/requests/my-assignments', \Jiny\Help\Http\Controllers\Admin\Support\Requests\MyAssignmentsController::class)->name('requests.my-assignments');

    // 자동 할당 설정 관리
    Route::prefix('auto-assignments')->name('auto-assignments.')->group(function () {
        Route::get('/', [\Jiny\Help\Http\Controllers\Admin\Support\AutoAssignmentController::class, 'index'])->name('index');
        Route::get('/create', [\Jiny\Help\Http\Controllers\Admin\Support\AutoAssignmentController::class, 'create'])->name('create');
        Route::post('/', [\Jiny\Help\Http\Controllers\Admin\Support\AutoAssignmentController::class, 'store'])->name('store');
        Route::get('/{id}', [\Jiny\Help\Http\Controllers\Admin\Support\AutoAssignmentController::class, 'show'])->name('show')->where('id', '[0-9]+');
        Route::get('/{id}/edit', [\Jiny\Help\Http\Controllers\Admin\Support\AutoAssignmentController::class, 'edit'])->name('edit')->where('id', '[0-9]+');
        Route::put('/{id}', [\Jiny\Help\Http\Controllers\Admin\Support\AutoAssignmentController::class, 'update'])->name('update')->where('id', '[0-9]+');
        Route::delete('/{id}', [\Jiny\Help\Http\Controllers\Admin\Support\AutoAssignmentController::class, 'destroy'])->name('destroy')->where('id', '[0-9]+');
        Route::post('/{id}/toggle', [\Jiny\Help\Http\Controllers\Admin\Support\AutoAssignmentController::class, 'toggle'])->name('toggle')->where('id', '[0-9]+');
    });
});