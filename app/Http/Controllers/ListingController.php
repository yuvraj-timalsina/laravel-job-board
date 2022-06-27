<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tags = Tag::orderBy('name')->get();
        $listings = Listing::with('tags')->get();


        if ($request->has('s')) {
            $query = strtolower($request->get('s'));

            $listings = $listings->filter(function ($listing) use ($query) {
                if (Str::contains(strtolower($listing->title), $query)) {
                    return true;
                }
                if (Str::contains(strtolower($listing->company), $query)) {
                    return true;
                }
                if (Str::contains(strtolower($listing->location), $query)) {
                    return true;
                }
                return false;
            });
        }
        if ($request->has('tag')) {
            $tag = $request->get('tag');
            $listings = $listings->filter(function ($listing) use ($tag) {
                return $listing->tags->contains('slug', $tag);
            });
        }

        return view('listings.index', compact('listings', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('listings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreListingRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // process the listing creation form
        $validationArray = [
            'title' => 'required',
            'company' => 'required',
            'logo' => 'image|max:2048',
            'location' => 'required',
            'apply_link' => 'required|url',
            'content' => 'required',
            'payment_method_id' => 'required'
        ];
        if (!Auth::check()) {
            $validationArray = array_merge($validationArray, [
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed|min:5',
                'name' => 'required'
            ]);
        }
        $request->validate($validationArray);

        // check if user is signed in else create one and authenticate
        $user = Auth::user();

        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $user->createAsStripeCustomer();

            Auth::login($user);
        }
        //process gthe mpayment and create listing
        try {
            $amount = 9900; // $99.00 USD in cents
            if ($request->filled('is_highlighted')) {
                $amount += 1900;
            }
            $user->charge($amount, $request->payment_method_id);

            $md = new \ParsedownExtra();

            $listing = $user->listings()->create([
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . rand(1111, 9999),
                'company' => $request->company,
                'logo' => basename($request->file('logo')->store('logo')),
                'location' => $request->location,
                'apply_link' => $request->apply_link,
                'content' => $md->text($request->content),
                'is_highlighted' => $request->filled('is_highlighted'),
                'is_active' =>true
            ]);

            foreach (explode(',', $request->tags) as $requestTag) {
                $tag = Tag::firstOrCreate([
                    'slug' => Str::slug(trim($requestTag))
                ],
                    [
                        'name' => ucwords(trim($requestTag))
                    ]);

                $tag->listings()->attach($listing->id);
            }
            return redirect(to: route('dashboard'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error'=>$e->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Listing $listing
     * @return \Illuminate\Http\Response
     */
    public function show(Listing $listing)
    {
        return view('listings.show', compact('listing'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Listing $listing
     * @return \Illuminate\Http\Response
     */
    public function edit(Listing $listing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateListingRequest $request
     * @param \App\Models\Listing $listing
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateListingRequest $request, Listing $listing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Listing $listing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Listing $listing)
    {
        //
    }

    public function apply(Listing $listing, Request $request)
    {
        $listing->clicks()->create([
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip()
        ]);
        return redirect()->to($listing->apply_link);
    }
}
