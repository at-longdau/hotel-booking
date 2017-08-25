<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Hotel;
use App\Model\Place;
use App\Model\Service;
use App\Model\Image;
use App\Model\HotelService;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hotels = Hotel::
            select('id', 'name', 'address', 'star', 'place_id', 'created_at')
            ->with(['place' => function ($query) {
                $query->select('id', 'name');
            }])
            ->with(['rooms' => function ($query) {
                $query->select('hotel_id', 'id');
            }])
            ->paginate(Hotel::ROW_LIMIT);

        return view('backend.hotels.index', compact('hotels'));
    }

    /**
     * Show a detail of the hotel.
     *
     * @param int $id id of hotel
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $columns = [
            'id',
            'name',
            'address',
            'star',
            'introduce',
            'place_id'
        ];

        $with['place'] = function ($query) {
            $query->select('id', 'name');
        };
        $with['rooms'] = function ($query) {
            $query->select('hotel_id', 'id', 'name');
        };
        $with['images'] = function ($query) {
            $query->select();
        };
        $with['hotelServices'] = function ($query) {
            $query->select('id', 'hotel_id', 'service_id');
        };
        $with['hotelServices.service'] = function ($query) {
            $query->select('id', 'name');
        };

        $hotel = Hotel::select($columns)->with($with)->findOrFail($id);

        return view('backend.hotels.show', compact('hotel'));
    }
}
