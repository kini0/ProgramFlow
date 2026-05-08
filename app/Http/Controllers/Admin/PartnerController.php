<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePartnerRequest;
use App\Models\Partner;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PartnerController extends Controller
{
    public function index()
    {
        return view('admin.partners.index', [
            'partners' => Partner::orderBy('name')->paginate(20),
        ]);
    }

    public function create()
    {
        return view('admin.partners.create', [
            'users' => User::role(UserRole::Partner->value)->orderBy('last_name')->get(),
        ]);
    }

    public function store(StorePartnerRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('partners', 'public');
        }
        unset($data['logo']);

        $partner = Partner::create($data);

        return redirect()->route('admin.partners.index')
            ->with('success', 'Partenaire créé : '.$partner->name);
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', [
            'partner' => $partner,
            'users'   => User::role(UserRole::Partner->value)->orderBy('last_name')->get(),
        ]);
    }

    public function update(StorePartnerRequest $request, Partner $partner)
    {
        $data = $request->validated();
        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('partners', 'public');
        }
        unset($data['logo']);

        $partner->update($data);
        return redirect()->route('admin.partners.index')->with('success', 'Partenaire mis à jour.');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return back()->with('success', 'Partenaire supprimé.');
    }
}
