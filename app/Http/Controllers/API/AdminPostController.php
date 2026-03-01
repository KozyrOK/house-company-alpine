<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;

class AdminPostController extends Controller
{
public function index()
{
$this->authorize('viewAny', Post::class);

return Post::with(['company:id,name','user:id,first_name'])
->latest()
->paginate();
}

public function show(Post $post)
{
$this->authorize('view', $post);

return $post->load(['company','user']);
}

public function destroy(Post $post)
{
$this->authorize('delete', $post);

$post->delete();

return response()->noContent();
}
}
