<?php

namespace App\Http\Controllers;

use App\LdapDomain;
use App\LdapNotifier;
use App\Http\Requests\LdapNotifierRequest;

class DomainNotifiersController
{
    /**
     * Displays all the setup LDAP notifications for the domain.
     *
     * @param LdapDomain $domain
     *
     * @return \Illuminate\View\View
     */
    public function index(LdapDomain $domain)
    {
        $notifiers = $domain->notifiers()
            ->with('conditions')
            ->custom()
            ->latest()
            ->paginate(25);

        $systemNotifiers = $domain->notifiers()->system()->get();

        return view('domains.notifiers.index', compact('domain', 'notifiers', 'systemNotifiers'));
    }

    /**
     * Displays the form for creating a new domain notifier.
     *
     * @param LdapDomain   $domain
     * @param LdapNotifier $notifier
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(LdapDomain $domain, LdapNotifier $notifier)
    {
        return view('domains.notifiers.create', compact('domain', 'notifier'));
    }

    /**
     * Creates a new domain notifier.
     *
     * @param LdapNotifierRequest $request
     * @param LdapDomain          $domain
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LdapNotifierRequest $request, LdapDomain $domain)
    {
        $request->persist(new LdapNotifier(), $domain);

        flash()->success('Added domain notifier');

        return redirect()->route('domains.notifiers.index', $domain);
    }

    /**
     * Displays the form for editing the domain notifier.
     *
     * @param LdapDomain $domain
     * @param string     $notifierId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(LdapDomain $domain, $notifierId)
    {
        $notifier = $domain->notifiers()->findOrFail($notifierId);

        return view('domains.notifiers.edit', compact('domain', 'notifier'));
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
