<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Friend;
use App\Models\Shout;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    protected function listJson()
	{
		// TODO: XlXi: Group shouts.
		$postsQuery = Shout::getPosts()
							->orderByDesc('id')
							->cursorPaginate(15);
		
		/* */
		
		$prevCursor = $postsQuery->previousCursor();
		$nextCursor = $postsQuery->nextCursor();
		
		$posts = [
			'data' => [],
			'prev_cursor' => ($prevCursor ? $prevCursor->encode() : null),
			'next_cursor' => ($nextCursor ? $nextCursor->encode() : null)
		];
		
		foreach($postsQuery as $post) {
			// TODO: XlXi: icons/colors
			// TODO: XlXi: groups
			
			$poster = [];
			if($post['poster_type'] == 'user') {
				$user = User::where('id', $post['poster_id'])->first();
				
				// TODO: XlXi: user profile link
				$poster = [
					'type' => 'User',
					'name' => $user->username,
					'thumbnail' => 'https://www.gtoria.local/images/testing/headshot.png',
					'url' => 'https://gtoria.local/todo123'
				];
			}
			
			/* */
			
			$postDate = $post['updated_at'];
			if(Carbon::now()->greaterThan($postDate->copy()->addDays(2)))
				$postDate = $postDate->isoFormat('lll');
			else
				$postDate = $postDate->calendar();
			
			/* */
			
			array_push($posts['data'], [
				'postId' => $post['id'],
				'poster' => $poster,
				'content' => $post['content'],
				'time' => $postDate
			]);
		}
		
		return response($posts);
	}
	
	protected function share(Request $request)
	{
		$validated = $request->validate([
			'content' => ['required', 'max:200']
		]);
		
		$shout = new Shout();
		$shout->poster_id = Auth::id();
		$shout->poster_type = 'user';
		$shout->content = $validated['content'];
		$shout->save();
		
		return response(['success' => true]);
	}
}
