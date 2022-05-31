<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'articles' => Article::paginate(10),
        ]);
    }

    public function destroy(string $articleId): RedirectResponse
    {
        Article::findOrFail($articleId)->delete();

        return redirect('articles');
    }
}
