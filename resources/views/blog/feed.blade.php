<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>{{ cms('branding.title', config('app.name')) }} — Blog</title>
        <link>{{ route('blog.index') }}</link>
        <description>Artikel manajemen rumah sakit, rekam medis elektronik, BPJS &amp; SatuSehat.</description>
        <language>id-ID</language>
        <atom:link href="{{ route('blog.feed') }}" rel="self" type="application/rss+xml" />
        @foreach($posts as $post)
        <item>
            <title>{{ $post->title }}</title>
            <link>{{ route('blog.show', $post) }}</link>
            <guid isPermaLink="true">{{ route('blog.show', $post) }}</guid>
            <pubDate>{{ $post->published_at?->toRfc2822String() }}</pubDate>
            <description><![CDATA[{{ Str::limit(strip_tags($post->excerpt ?: $post->content), 300) }}]]></description>
            @if($post->author)<author>{{ $post->author->name }}</author>@endif
        </item>
        @endforeach
    </channel>
</rss>
