@extends('domains.layout')

@section('breadcrumbs', Breadcrumbs::render('domains.changes.show', $domain, $attribute))

@section('page')
    @component('components.card', ['flush' => true])
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <h5 class="mb-0">
                    <strong>{{ ucfirst($attribute) }}</strong> changes
                </h5>
            </div>

            @forelse($changes as $change)
                <div class="list-group-item">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="row flex-column">
                                <div class="col">
                                    @include('domains.objects.partials.icon', ['object' => $change->object])

                                    <a href="{{ route('domains.objects.show', [$domain, $change->object]) }}" class="font-weight-bold">
                                        {{ $change->object->name }}
                                    </a>
                                </div>

                                <div class="col text-muted small">
                                    {{ $change->object->dn }}
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <a
                                href="{{ route('domains.objects.changes.show', [$domain, $change->object, $change->attribute]) }}"
                                title="{{ $change->ldap_updated_at }}"
                            >
                                {{ $change->ldap_updated_at->diffForHumans() }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty

            @endforelse
        </div>
    @endcomponent

    <div class="row my-4">
        <div class="col">
            <div class="d-flex justify-content-center">
                {{ $changes->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
