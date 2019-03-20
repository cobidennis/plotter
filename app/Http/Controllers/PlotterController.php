<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PlotterControllerRequest;
class PlotterController extends Controller
{
    /**
     * Create a new PlotterController Instance
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Displays the a map plloter index page that defaults to crawley
     * @param null
     * @return Illuminate\Http\Response
     */
    public function index(){
        return view('plotter.index');
    }

    /**
     * Validates form inputs from an AJAX HTTP Request
     * @param Illuminate\Http\Request
     * @return Illuminate\Http\Response
     */
    public function validateCoord(PlotterControllerRequest $request){
        return response()->json([$request->all(),200]);
    }
}
