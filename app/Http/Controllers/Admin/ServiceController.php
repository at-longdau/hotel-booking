<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\Service;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CreateServiceRequest;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::select('id', 'name')
            ->orderBy('id', 'DESC')->paginate(Service::ROW_LIMIT);
        return view("backend.services.index", compact('services'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id id of service
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('backend.services.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\UpdateRequest $request request to update
     * @param int                            $id      id of service
     *
     * @return \Illuminate\Http\Response
     */
    public function update(CreateServiceRequest $request, $id)
    {
        $service = Service::findOrFail($id);
        
        if ($service->update($request->all())) {
            flash(__('Update success'))->success();
            return redirect()->route('service.index');
        } else {
            flash(__('Update failure'))->error();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for creating a new service.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.services.create');
    }

    /**
     * Store a newly created service in storage.
     *
     * @param CreateServiceRequest $request request from view
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateServiceRequest $request)
    {
        $service = new Service($request->all());
        if ($service->save()) {
            flash(__('Create success'))->success();
            return redirect()->route('service.index');
        } else {
            flash(__('Create failure'))->error();
            return redirect()->back()->withInput();
        }

        return redirect()->route('service.index');
    }
}