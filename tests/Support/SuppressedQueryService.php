<?php

namespace BeyondCode\QueryDetector\Tests\Support;

use BeyondCode\QueryDetector\Attributes\SuppressQueryDetection;
use BeyondCode\QueryDetector\Tests\Models\Author;
use BeyondCode\QueryDetector\Tests\Models\Post;

class SuppressedQueryService
{
    #[SuppressQueryDetection]
    public function getAuthorsWithProfile()
    {
        $authors = Author::all();

        foreach ($authors as $author) {
            $author->profile;
        }
    }

    public function getAuthorsWithProfileUnsuppressed()
    {
        $authors = Author::all();

        foreach ($authors as $author) {
            $author->profile;
        }
    }

    #[SuppressQueryDetection]
    public function getPostsWithComments()
    {
        foreach (Post::all() as $post) {
            $post->comments;
        }
    }

    #[SuppressQueryDetection]
    public function getAuthorsWithMultipleRelations()
    {
        $authors = Author::all();

        foreach ($authors as $author) {
            $author->profile;
            $author->posts()->where(1)->get();
        }
    }

    #[SuppressQueryDetection]
    public function outerMethod()
    {
        $this->innerMethod();
    }

    public function innerMethod()
    {
        $authors = Author::all();

        foreach ($authors as $author) {
            $author->profile;
        }
    }
}
