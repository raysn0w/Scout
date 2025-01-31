@extends('domains.layout')

@section('breadcrumbs', Breadcrumbs::render('domains.scans.index', $domain))

@section('page')
    @component('components.card', ['class' => 'bg-white', 'flush' => true])
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <h5 class="mb-0">Recent Scans</h5>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <colgroup>
                        <col style="width:1%;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="pl-4"></th>
                            <th>Started</th>
                            <th>Completed</th>
                            <th class="text-center">Synchronized</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($scans as $scan)
                            <tr>
                                <td class="pl-4 align-middle">
                                    @component('components.status', ['status' => $scan->success])

                                    @endcomponent
                                </td>
                                <td class="align-middle text-nowrap">
                                    @if($scan->started_at)
                                        <span title="{{ $scan->started_at }}">
                                            {{ $scan->started_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <em class="text-muted">Not Started</em>
                                    @endif
                                </td>
                                <td class="align-middle text-nowrap">
                                    @if($scan->completed_at)
                                        <span title="{{ $scan->completed_at }}">
                                            {{ $scan->completed_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <em class="text-muted">Not completed</em>
                                    @endif
                                </td>
                                <td class="text-center align-middle">{{ $scan->synchronized }}</td>
                                <td class="align-middle">
                                    @if($scan->message)
                                        <pre class="mb-0 text-wrap">
                                            <code>{{ $scan->message }}</code>
                                        </pre>
                                    @else
                                        <em class="text-muted">None</em>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endcomponent

    <div class="row my-4">
        <div class="col">
            <div class="d-flex justify-content-center">
                {{ $scans->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
