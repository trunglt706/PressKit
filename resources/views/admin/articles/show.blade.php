@extends('admin.layouts.app')

@section('title', $article->title)

@section('head')
    {!! \Artesaos\SEOTools\Facades\SEOTools::generate() !!}
@endsection

@section('content')
    <p><a href="{{ route('admin.articles.index') }}">Back to list</a></p>
    <p><a href="{{ route('admin.articles.history', $article) }}">View change history</a></p>
    <p><a href="{{ route('admin.articles.comments.index', $article) }}">Manage comments</a></p>

    <h1>{{ $article->title }}</h1>
    <p><strong>Category:</strong> {{ $article->category?->name ?? 'N/A' }}</p>
    <p><strong>Author:</strong> {{ $article->author?->name ?? 'N/A' }}</p>
    <p><strong>Status:</strong> {{ ucfirst($article->status?->value ?? '') }}</p>
    <p><strong>Workflow:</strong> {{ ucfirst($article->workflow_status ?? 'draft') }}</p>
    <p><strong>Slug:</strong> {{ $article->slug }}</p>

    <h2>Publish Flow</h2>
    @if ($article->workflow_status === 'draft')
        <form action="{{ route('admin.articles.submit-review', $article) }}" method="POST">
            @csrf
            <button type="submit">Submit For Review</button>
        </form>
    @endif

    @if ($article->workflow_status === 'review')
        <form action="{{ route('admin.articles.approve', $article) }}" method="POST">
            @csrf
            <button type="submit">Approve</button>
        </form>
    @endif

    @if (in_array($article->workflow_status, ['approved', 'published'], true))
        <form action="{{ route('admin.articles.publish', $article) }}" method="POST">
            @csrf
            <button type="submit">Publish</button>
        </form>
    @endif

    <p><strong>Submitted for review at:</strong> {{ $article->submitted_for_review_at?->toDateTimeString() ?? 'N/A' }}</p>
    <p><strong>Reviewed at:</strong> {{ $article->reviewed_at?->toDateTimeString() ?? 'N/A' }}</p>
    <p><strong>Published by:</strong> {{ $article->publisher?->name ?? 'N/A' }}</p>
    <p><strong>Static HTML path:</strong> {{ $article->static_html_path ?? 'N/A' }}</p>
    <p><strong>AMP HTML path:</strong> {{ $article->amp_html_path ?? 'N/A' }}</p>
    <p><strong>Last publish job at:</strong> {{ $article->last_published_job_at?->toDateTimeString() ?? 'N/A' }}</p>

    <h2>SEO Analysis</h2>
    <p><strong>Score:</strong> {{ $article->seo?->score ?? 0 }}/100</p>
    <p><strong>Analyzed at:</strong> {{ $article->seo?->last_analyzed_at?->toDateTimeString() ?? 'N/A' }}</p>

    @if (!empty($article->seo?->warnings))
        <h3>Warnings</h3>
        <ul>
            @foreach ($article->seo->warnings as $warning)
                <li>{{ $warning }}</li>
            @endforeach
        </ul>
    @endif

    @if (!empty($article->seo?->score_breakdown))
        <h3>Breakdown</h3>
        <ul>
            @foreach ($article->seo->score_breakdown as $criterion => $point)
                <li>{{ str_replace('_', ' ', ucfirst($criterion)) }}: {{ $point }}</li>
            @endforeach
        </ul>
    @endif

    <h2>Change Slug</h2>
    <form action="{{ route('admin.articles.slug.update', $article) }}" method="POST">
        @csrf
        @method('PATCH')
        <input type="text" name="slug" value="{{ old('slug', $article->slug) }}" style="width: 320px;" required />
        <button type="submit">Update slug</button>
    </form>
    <p><strong>Published at:</strong> {{ $article->published_at?->toDateTimeString() ?? 'N/A' }}</p>

    <h2>Excerpt</h2>
    <p>{{ $article->excerpt ?: 'N/A' }}</p>

    <h2>Featured Image</h2>
    @if ($article->getFirstMediaUrl('featured_image'))
        <img src="{{ $article->getFirstMediaUrl('featured_image') }}" alt="Featured image" style="max-width: 360px;" />
    @else
        <p>N/A</p>
    @endif

    <h2>Gallery</h2>
    @if ($article->getMedia('gallery')->isNotEmpty())
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            @foreach ($article->getMedia('gallery') as $media)
                <img src="{{ $media->getUrl('thumb') ?: $media->getUrl() }}" alt="Gallery image" style="width: 160px; height: auto;" />
            @endforeach
        </div>
    @else
        <p>N/A</p>
    @endif

    <h2>Attachments</h2>
    @if ($article->getMedia('attachments')->isNotEmpty())
        <ul>
            @foreach ($article->getMedia('attachments') as $media)
                <li><a href="{{ $media->getUrl() }}" target="_blank" rel="noopener">{{ $media->name }}</a></li>
            @endforeach
        </ul>
    @else
        <p>N/A</p>
    @endif

    @php($latestVersion = $article->versions()->latest('version_number')->first())
    <h2>Latest Version Files</h2>
    @if ($latestVersion && $latestVersion->getMedia('version_files')->isNotEmpty())
        <ul>
            @foreach ($latestVersion->getMedia('version_files') as $media)
                <li><a href="{{ $media->getUrl() }}" target="_blank" rel="noopener">{{ $media->name }}</a></li>
            @endforeach
        </ul>
    @else
        <p>N/A</p>
    @endif

    <h2>Content</h2>
    <div>{!! nl2br(e($article->content)) !!}</div>
@endsection
