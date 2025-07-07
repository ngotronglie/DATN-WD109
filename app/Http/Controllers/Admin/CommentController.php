namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Hiển thị danh sách bình luận
    public function index()
    {
        $comments = Comment::with(['blog', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    // Xóa bình luận
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Đã xóa bình luận thành công!');
    }
}
