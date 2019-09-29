@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h3 class="flex-shrink-1 pr-2">{{ $domain->name }}</h3>

        <div class="flex-grow-1 text-muted">
            {{ $domain->base_dn }}
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            @isset($object)
                @component('components.card', ['class' => 'mb-4', 'flush' => true])
                    <div class="list-group list-group-flush">
                        <div class="list-group-item font-weight-bold">
                            <h5 class="mb-0">{{ $object->name }}</h5>
                        </div>

                        <a
                            href="{{ route('domains.objects.show', [$domain, $object]) }}"
                            class="list-group-item list-group-item-action font-weight-bold {{ request()->routeIs('domains.objects.show') ? 'active' : null }}"
                        >
                            <i class="far fa-eye"></i> Overview
                        </a>

                        <a href="#" class="list-group-item list-group-item-action font-weight-bold">
                            <i class="far fa-bell"></i> Notifications
                        </a>

                        <a
                            href="{{ route('domains.objects.attributes.index', [$domain, $object]) }}"
                            class="list-group-item list-group-item-action font-weight-bold {{ request()->routeIs('domains.objects.attributes.*') ? 'active' : null }}"
                        >
                            <div class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fa fa-list-ul"></i> {{ __('Attributes') }}
                                </span>

                                <span class="badge badge-primary">{{ $counts['object']['attributes'] }}</span>
                            </div>
                        </a>

                        <a
                            href="{{ route('domains.objects.changes.index', [$domain, $object]) }}"
                            class="list-group-item list-group-item-action font-weight-bold {{ request()->routeIs('domains.objects.changes.*') ? 'active' : null }}"
                        >
                            <div class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fa fa-sync"></i> {{ __('Changes') }}
                                </span>

                                <span class="badge badge-primary">{{ $counts['object']['changes'] }}</span>
                            </div>
                        </a>
                    </div>
                @endcomponent
            @endisset

            @component('components.card', ['class' => 'mb-4', 'flush' => true])
                <div class="list-group list-group-flush">
                    <div class="list-group-item font-weight-bold">
                        <h5 class="mb-0">Domain</h5>
                    </div>

                    <a
                        href="{{ route('domains.show', $domain) }}"
                        class="list-group-item list-group-item-action font-weight-bold {{ request()->routeIs('domains.show') ? 'active' : null }}"
                    >
                        <i class="far fa-eye"></i> {{ __('Overview') }}
                    </a>

                    <a
                        href="{{ route('domains.notifiers.index', $domain) }}"
                        class="list-group-item list-group-item-action font-weight-bold {{ request()->routeIs('domains.notifiers.*') ? 'active' : null }}"
                    >
                        <i class="far fa-bell"></i> Notifications
                    </a>

                    <a
                        href="{{ route('domains.search.index', $domain) }}"
                        class="list-group-item list-group-item-action font-weight-bold {{ request()->routeIs('domains.search.*') ? 'active' : null }}"
                    >
                        <i class="fas fa-search"></i> {{ __('Search') }}
                    </a>

                    <a
                        href="{{ route('domains.scans.index', $domain) }}"
                        class="list-group-item list-group-item-action font-weight-bold {{ request()->routeIs('domains.scans.*') ? 'active' : null }}"
                    >
                        <i class="fas fa-heartbeat"></i> {{ __('Recent Scans') }}
                    </a>

                    <a
                        href="{{ route('domains.objects.index', $domain) }}"
                        class="list-group-item list-group-item-action font-weight-bold {{ request()->routeIs('domains.objects.index') ? 'active' : null }}"
                    >
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-cubes"></i> {{ __('All Objects') }}
                            </span>

                            <span class="badge badge-primary">{{ $counts['objects'] }}</span>
                        </div>
                    </a>

                    <a
                        href="{{ route('domains.changes.index', $domain) }}"
                        class="list-group-item list-group-item-action font-weight-bold {{ request()->routeIs('domains.changes.*') ? 'active' : null }}"
                    >
                        <div class="d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-sync"></i> {{ __('All Changes') }}
                            </span>

                            <span class="badge badge-primary">{{ $counts['changes'] }}</span>
                        </div>
                    </a>

                    <a
                        href="{{ route('domains.edit', $domain) }}"
                        class="list-group-item list-group-item-action font-weight-bold {{ request()->routeIs('domains.edit') ? 'active' : null }}"
                    >
                        <i class="far fa-edit"></i> {{ __('Edit') }}
                    </a>

                    <a
                        href="{{ route('domains.delete', $domain) }}"
                        class="list-group-item list-group-item-action font-weight-bold"
                    >
                        <i class="far fa-trash-alt"></i> {{ __('Delete') }}
                    </a>
                </div>
            @endcomponent
        </div>

        <div class="col-md-9">
            @yield('page')
        </div>
    </div>
@endsection
