<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\BlogService;
use App\Services\FooterService;
use App\Services\NavigationService;

class BlogController extends Controller
{
    public function index()
    {
        $blogService = app(BlogService::class);

        $posts = $blogService->posts();
        $categories = $blogService->categories();
        $featuredPosts = $blogService->featuredPosts();

        $footer = app(FooterService::class)->footerData();
        $navigation = app(NavigationService::class)->navigationData();

        return view('site.pages.blog.index', compact(
            'posts',
            'categories',
            'featuredPosts',
            'footer',
            'navigation'
        ));
    }

    public function show(string $slug)
    {
        $post = app(BlogService::class)->findPostBySlug($slug);

        $footer = app(FooterService::class)->footerData();
        $navigation = app(NavigationService::class)->navigationData();

        return view('site.pages.blog.show', compact(
            'post',
            'footer',
            'navigation'
        ));
    }

    public function category(string $slug)
    {
        $blogService = app(BlogService::class);

        $category = $blogService->findCategoryBySlug($slug);
        $posts = $blogService->postsByCategory($category);
        $categories = $blogService->categories();

        $footer = app(FooterService::class)->footerData();
        $navigation = app(NavigationService::class)->navigationData();

        return view('site.pages.blog.category', compact(
            'category',
            'posts',
            'categories',
            'footer',
            'navigation'
        ));
    }
}