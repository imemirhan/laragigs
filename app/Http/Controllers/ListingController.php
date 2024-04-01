<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;

class ListingController extends Controller
{
    //Show all listings
    public function index(Request $request)
    {

        return view('listings.index', [
            'listings' => Listing::latest()->filter(request(['tag', 'search']))->simplePaginate(4)
        ]);
    }

    //Show single listing
    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    //Create a new listing
    public function create()
    {
        return view('listings.create');
    }

    //Store listing data
    public function store(Request $request)
    {
        $formFields = $request->validate(
            [
                'title' => 'required',
                'company' => ['required', Rule::unique(
                    'listings',
                    'company'
                )],
                'location' => 'required',
                'website' => 'required',
                'email' => ['required', 'email'],
                'tags' => 'required',
                'description' => 'required'
            ]
        );

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store(
                'logos',
                'public'
            );
        }

        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);


        return redirect('/')->with('success', 'Listing Created
        Successfully!');
    }

    public function edit(Listing $listing)
    {
        return view('listings.edit', ['listing' => $listing]);
    }

    //Update Listing Data
    public function update(Request $request, Listing $listing)
    {
        //Make sure logged in user is the owner
        if ($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }
        $formFields = $request->validate(
            [
                'title' => 'required',
                'company' => ['required'],
                'location' => 'required',
                'website' => 'required',
                'email' => ['required', 'email'],
                'tags' => 'required',
                'description' => 'required'
            ]
        );

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store(
                'logos',
                'public'
            );
        }

        $listing->update($formFields);


        return back()->with('success', 'Listing Updated Successfully');
    }

    //Delete an Existing Listing
    public function destroy(Listing $listing)
    {
        //Make sure logged in user is the owner
        if ($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action');
        }
        $listing->delete();
        return redirect('/')->with('success', 'Listing deleted successfully');
    }

    //Manage Listings
    public function manage()
    {
        return view('listings.manage', ['listings' => auth()->user()->listings()->get()]);
    }
}
