<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\RatingComment;
use App\Model\User;
use App\Model\Hotel;

class RatingCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ratingComments = RatingComment::
            select('id', 'user_id', 'hotel_id', 'total_rating', 'created_at')
            ->with(['user' => function ($query) {
                $query->select('id', 'username', 'full_name');
            }])
            ->with(['hotel' => function ($query) {
                $query->select('id', 'name');
            }])
            ->orderby('id', 'DESC')->paginate(RatingComment::ROW_LIMIT);

        return view('backend.comments.index', compact('ratingComments'));
    }
}
