@extends('domains.layout')

@section('breadcrumbs', Breadcrumbs::render('domains.search.index', $domain))

@section('page')
    <form method="get" action="{{ route('partials.domains.search.index', $domain) }}" data-controller="form">
        @component('components.card', ['class' => 'mb-4'])
            @slot('header')
                <h5 class="mb-0">Search Domain</h5>
            @endslot

            <div class="form-group">
                <div class="input-group">
                    {{
                        Form::scoutSearch('term', request('term'), [
                            'data-target' => 'form.input',
                            'placeholder' => 'Search...',
                        ])
                    }}

                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <details>
                <summary onselectstart="return false">Search Options</summary>

                <div class="ml-3 mt-2">
                    {{
                        Form::scoutCheckbox('deleted', '1', request('deleted') == '1', [
                            'id' => 'search-deleted',
                            'label' => 'Include Deleted',
                            'data-target' => 'form.input',
                        ])
                    }}
                </div>
            </details>
        @endcomponent

        <div id="search-results"></div>
    </form>
@endsection
