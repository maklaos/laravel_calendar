<?php

namespace App\Http\Controllers;

use App\Calendar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('calendar.index')->with([
            'events' => Calendar::where('user_id', Auth::user()->id)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $media = $request->file('file') ? $request->file('file')->store('public/media') : null;

        $request->merge([
            'notify' => $request->notify == 'on' ? 1 : 0,
            'user_id' => Auth::user()->id,
            'media' => str_replace('public/', '', $media)
        ]);

        $request->validate([
            'name' => 'required',
            'event-start-date' => 'required|date',
            'event-end-date' => 'required|date',
            'file' => 'mimetypes:image/jpeg,image/png,audio/mpeg'
        ]);

        $calendar = Calendar::create($request->all());
        return $calendar->id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function show(Calendar $calendar)
    {
        return response()->json($calendar);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function edit(Calendar $calendar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calendar $calendar)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Calendar  $calendar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calendar $calendar)
    {
        Storage::disk('public')->delete($calendar->media);
        $calendar->delete();
    }
}
