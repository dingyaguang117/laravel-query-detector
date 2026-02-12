<?php

namespace BeyondCode\QueryDetector\Tests;

use Route;
use BeyondCode\QueryDetector\QueryDetector;
use BeyondCode\QueryDetector\Tests\Models\Author;
use BeyondCode\QueryDetector\Tests\Support\SuppressedQueryService;

class SuppressQueryDetectionTest extends TestCase
{
    /** @test */
    public function it_suppresses_n1_detection_with_attribute()
    {
        Route::get('/', function () {
            (new SuppressedQueryService())->getAuthorsWithProfile();
        });

        $this->get('/');

        $queries = app(QueryDetector::class)->getDetectedQueries();

        $this->assertCount(0, $queries);
    }

    /** @test */
    public function it_still_detects_n1_without_attribute()
    {
        Route::get('/', function () {
            (new SuppressedQueryService())->getAuthorsWithProfileUnsuppressed();
        });

        $this->get('/');

        $queries = app(QueryDetector::class)->getDetectedQueries();

        $this->assertCount(1, $queries);
        $this->assertSame(Author::count(), $queries[0]['count']);
        $this->assertSame(Author::class, $queries[0]['model']);
        $this->assertSame('profile', $queries[0]['relation']);
    }

    /** @test */
    public function it_suppresses_n1_detection_on_morph_relations()
    {
        Route::get('/', function () {
            (new SuppressedQueryService())->getPostsWithComments();
        });

        $this->get('/');

        $queries = app(QueryDetector::class)->getDetectedQueries();

        $this->assertCount(0, $queries);
    }

    /** @test */
    public function it_suppresses_n1_detection_on_multiple_relations()
    {
        Route::get('/', function () {
            (new SuppressedQueryService())->getAuthorsWithMultipleRelations();
        });

        $this->get('/');

        $queries = app(QueryDetector::class)->getDetectedQueries();

        $this->assertCount(0, $queries);
    }

    /** @test */
    public function it_suppresses_n1_detection_in_nested_calls()
    {
        Route::get('/', function () {
            (new SuppressedQueryService())->outerMethod();
        });

        $this->get('/');

        $queries = app(QueryDetector::class)->getDetectedQueries();

        $this->assertCount(0, $queries);
    }

    /** @test */
    public function it_only_suppresses_attributed_method_not_others()
    {
        Route::get('/', function () {
            $service = new SuppressedQueryService();
            $service->getAuthorsWithProfile(); // suppressed
            $service->getAuthorsWithProfileUnsuppressed(); // not suppressed
        });

        $this->get('/');

        $queries = app(QueryDetector::class)->getDetectedQueries();

        $this->assertCount(1, $queries);
        $this->assertSame(Author::class, $queries[0]['model']);
        $this->assertSame('profile', $queries[0]['relation']);
    }
}
