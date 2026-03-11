@extends('admin.layouts.app')

@section('title', 'Article History')

@section('content')
    <p><a href="{{ route('admin.articles.show', $article) }}">Back to article</a></p>
    <h1>History: {{ $article->title }}</h1>

    <table>
        <thead>
            <tr>
                <th>Time</th>
                <th>Event</th>
                <th>Causer</th>
                <th>Old</th>
                <th>New</th>
                <th>Restore</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($activities as $activity)
            @php
                $old = $activity->properties['old'] ?? [];
                $new = $activity->properties['attributes'] ?? [];
            @endphp
            <tr>
                <td>{{ $activity->created_at?->toDateTimeString() }}</td>
                <td>{{ $activity->event ?? 'updated' }}</td>
                <td>{{ $activity->causer?->name ?? 'system' }}</td>
                <td><pre>{{ json_encode($old, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></td>
                <td><pre>{{ json_encode($new, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre></td>
                <td>
                    @if (!empty($old))
                        <form action="{{ route('admin.articles.history.restore', [$article, $activity]) }}" method="POST" onsubmit="return confirm('Restore from this log entry?');">
                            @csrf
                            <button type="submit">Restore</button>
                        </form>
                    @else
                        <span class="muted">N/A</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No activity log found for this article.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $activities->links() }}
    </div>
@endsection
