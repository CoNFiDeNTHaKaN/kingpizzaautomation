<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class PageController extends Controller
{
    public function show(Request $request, $path) {
      $viewName = "pages.{$path}";
      if (View::exists($viewName)) {
        return view($viewName);
      } else {
        abort(404);
      }
    }
}
