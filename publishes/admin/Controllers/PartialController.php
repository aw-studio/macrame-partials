<?php

namespace Admin\Http\Controllers;

use Admin\Http\Resources\PartialResource;
use Admin\Ui\Page;
use App\Models\Partial;
use Illuminate\Http\Request;

class PartialController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Admin\Ui\Page            $page
     * @return \Illuminate\Http\Response
     */
    public function index(Page $page)
    {
        return $page
            ->page('Partial/Index')
            ->with('partials', PartialResource::collection(Partial::allFromTemplates()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Admin\Ui\Page            $page
     * @param  \App\Models\Partial       $partial
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page, Partial $partial)
    {
        return $page->page('Partial/Show')
            ->with('partials', PartialResource::collection(Partial::allFromTemplates()))
            ->with('partial', PartialResource::make($partial));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Request $request
     * @param  \App\Models\Partial        $partial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Partial $partial)
    {
        $validated = $request->validate([
            'attributes' => 'array',
            'name'       => 'sometimes|string',
        ]);

        $partial->update($validated);

        return redirect()->back();
    }
}
