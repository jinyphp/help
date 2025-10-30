<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 통합된 Help, FAQ, QnA 시스템 마이그레이션
 *
 * 기존 site_faq_cate, site_faq 테이블과 새로운 구조의 테이블들을 통합
 */
return new class extends Migration
{
    public function up()
    {
        // 기존 site_faq_cate 테이블이 있다면 새로운 구조로 마이그레이션
        if (Schema::hasTable('site_faq_cate')) {
            // 기존 데이터를 새로운 구조로 이동하는 로직은 별도로 처리
            // 여기서는 새로운 테이블 구조만 정의
        }

        // FAQ 카테고리 (기존 site_faq_cate를 확장)
        if (!Schema::hasTable('help_categories')) {
            Schema::create('help_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('code', 50)->unique()->nullable();
                $table->text('description')->nullable();
                $table->string('icon', 100)->nullable();
                $table->string('image')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->string('manager')->nullable();
                $table->enum('type', ['faq', 'help', 'qna', 'guide'])->default('faq');
                $table->timestamps();
            });
        }

        // FAQ (기존 site_faq를 확장)
        if (!Schema::hasTable('help_faqs')) {
            Schema::create('help_faqs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->nullable()->constrained('help_categories')->onDelete('set null');
                $table->text('question');
                $table->text('answer');
                $table->string('tags', 500)->nullable();
                $table->integer('priority')->default(0);
                $table->integer('view_count')->default(0);
                $table->integer('helpful_count')->default(0);
                $table->integer('like_count')->default(0);
                $table->boolean('is_featured')->default(false);
                $table->enum('status', ['draft', 'review', 'published', 'hidden'])->default('draft');
                $table->boolean('is_active')->default(true);
                $table->string('image')->nullable();
                $table->string('manager')->nullable();
                $table->integer('sort_order')->default(0);
                $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }

        // QnA 질문
        if (!Schema::hasTable('help_qna_questions')) {
            Schema::create('help_qna_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('category_id')->nullable()->constrained('help_categories')->onDelete('set null');
                $table->string('title');
                $table->text('content');
                $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
                $table->enum('status', ['pending', 'processing', 'answered', 'resolved', 'closed'])->default('pending');
                $table->boolean('is_anonymous')->default(false);
                $table->boolean('is_private')->default(false);
                $table->foreignId('assignee_id')->nullable()->constrained('users')->onDelete('set null');
                $table->tinyInteger('satisfaction_score')->nullable();
                $table->integer('view_count')->default(0);
                $table->timestamps();
            });
        }

        // QnA 답변
        if (!Schema::hasTable('help_qna_answers')) {
            Schema::create('help_qna_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('question_id')->constrained('help_qna_questions')->onDelete('cascade');
                $table->foreignId('answerer_id')->constrained('users')->onDelete('cascade');
                $table->text('content');
                $table->boolean('is_official')->default(false);
                $table->boolean('is_best')->default(false);
                $table->integer('helpful_count')->default(0);
                $table->timestamps();
            });
        }

        // 도움말 문서 (기존 site_help를 확장)
        if (!Schema::hasTable('help_documents')) {
            Schema::create('help_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->nullable()->constrained('help_categories')->onDelete('set null');
                $table->string('title');
                $table->longText('content');
                $table->text('excerpt')->nullable();
                $table->string('tags', 500)->nullable();
                $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
                $table->integer('reading_time')->nullable();
                $table->integer('view_count')->default(0);
                $table->integer('like_count')->default(0);
                $table->boolean('is_active')->default(true);
                $table->string('image')->nullable();
                $table->string('manager')->nullable();
                $table->integer('sort_order')->default(0);
                $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamp('last_reviewed_at')->nullable();
                $table->enum('status', ['draft', 'review', 'published', 'archived'])->default('draft');
                $table->timestamps();
            });
        }

        // 도움말 평가 테이블
        if (!Schema::hasTable('help_ratings')) {
            Schema::create('help_ratings', function (Blueprint $table) {
                $table->id();
                $table->morphs('ratable'); // help_faqs, help_documents, help_qna_answers 등
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->tinyInteger('rating')->comment('1-5 점수');
                $table->text('comment')->nullable();
                $table->timestamps();

                $table->unique(['ratable_type', 'ratable_id', 'user_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('help_ratings');
        Schema::dropIfExists('help_documents');
        Schema::dropIfExists('help_qna_answers');
        Schema::dropIfExists('help_qna_questions');
        Schema::dropIfExists('help_faqs');
        Schema::dropIfExists('help_categories');
    }
};