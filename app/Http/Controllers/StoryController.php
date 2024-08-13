<?php

namespace App\Http\Controllers;

use App\Models\CardGroup;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class StoryController extends Controller {

  public function index(Request $request) {
    $stories = Story::get()->toArray();
    return Inertia::render('Admin/Story/Index', [
      "stories" => $stories,
    ]);
  }

  public function show(Story $story) {
    $cardGroups = CardGroup::where('story_id', $story->id)->with('cards')->get()->toArray();
    return Inertia::render('Admin/Story/Page', [
      "story" => $story,
      "cardGroups" => $cardGroups,
    ]);
  }

  public function updatePDF(Story $story, Request $request) {
    if ($request->hasFile('pdf')) {
      $request->validate([
        'pdf' => ['required', 'file', 'mimes:pdf', 'max:50000']]
      );
      $path = $request->file('pdf')->store('public/stories');
      $story->pdf = Storage::url($path);
      $story->save();
    }
    return;
  }

  public function store(Request $request) {
    $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'string|nullable|max:2000',
    ]);

    if ($request->id) {
      Story::find($request->id)->update([
        'name' => $request->name,
        'description' => $request->description,
      ]);
    } else {
      auth()->user()->stories()->create([
        'name' => $request->name,
        'description' => $request->description,
      ]);
    }

  }

  public function delete(Story $story) {
    // if (auth()->user()->id !== $story->user_id) {
    //   abort(403, 'Not Authorized');
    // }

    $story->delete();
  }
}
