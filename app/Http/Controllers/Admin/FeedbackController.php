<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Feedback;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedbackField = [
            'id',
            'full_name',
            'email',
            'content'
        ];
        $feedbacks = Feedback::select($feedbackField)
            ->orderBy('id', 'DESC')
            ->paginate(Feedback::ROW_LIMIT);
        return view("backend.feedbacks.index", compact('feedbacks'));
    }
}
