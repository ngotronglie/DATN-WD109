namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $blogId)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'blog_id' => $blogId,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'parent_id' => $request->parent_id, // nếu có trả lời bình luận
        ]);

        return back()->with('success', 'Bình luận đã được gửi!');
    }
}
