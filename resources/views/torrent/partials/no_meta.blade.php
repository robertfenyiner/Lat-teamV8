<section class="meta">
    @if (file_exists(public_path() . '/files/img/torrent-banner_' . $torrent->id . '.jpg'))
        <img
            class="meta__backdrop"
            src="{{ url('/files/img/torrent-banner_' . $torrent->id . '.jpg') }}"
            alt="Backdrop"
        />
    @endif

    <span class="meta__poster-link">
        <img
            src="{{ file_exists(public_path() . '/files/img/torrent-cover_' . $torrent->id . '.jpg') ? url('/files/img/torrent-cover_' . $torrent->id . '.jpg') : 'https://via.placeholder.com/400x600' }}"
            class="meta__poster"
        />
    </span>
    <div class="meta__actions">
        <a class="meta__dropdown-button" href="#">
            <i class="{{ config('other.font-awesome') }} fa-ellipsis-v"></i>
        </a>
        <ul class="meta__dropdown">
            <li>
                <a
                    href="{{ route('torrents.create', ['category_id' => $category->id, 'title' => rawurlencode($meta->title ?? '') ?? 'Unknown', 'imdb' => $torrent?->imdb ?? '', 'tmdb' => $meta?->id ?? '']) }}"
                >
                    {{ __('common.upload') }}
                </a>
            </li>
            <li>
                <a
                    href="{{ route('requests.create', ['title' => rawurlencode($meta?->title ?? '') ?? 'Unknown', 'imdb' => $torrent?->imdb ?? '', 'tmdb' => $meta?->id ?? '']) }}"
                >
                    Request similar
                </a>
            </li>
        </ul>
    </div>
    <ul class="meta__ids">
        @if (isset($torrent) && $torrent->imdb > 0)
            <li class="meta__imdb">
                <a
                    class="meta-id-tag"
                    href="https://www.imdb.com/title/tt{{ \str_pad((int) $torrent->imdb, \max(\strlen((int) $torrent->imdb), 7), '0', STR_PAD_LEFT) }}"
                    title="Internet Movie Database"
                    target="_blank"
                >
                    IMDB:
                    {{ \str_pad((int) $torrent->imdb, \max(\strlen((int) $torrent->imdb), 7), '0', STR_PAD_LEFT) }}
                </a>
            </li>
        @endif

        @if (isset($torrent) && $torrent->tmdb > 0)
            <li class="meta__tmdb">
                <a
                    class="meta-id-tag"
                    href="https://www.themoviedb.org/movie/{{ $torrent->tmdb }}"
                    title="The Movie Database"
                    target="_blank"
                >
                    TMDB: {{ $torrent->tmdb }}
                </a>
            </li>
        @endif

        @if (isset($torrent) && $torrent->mal > 0)
            <li class="meta__mal">
                <a
                    class="meta-id-tag"
                    href="https://myanimelist.net/anime/{{ $torrent->mal }}"
                    title="MyAnimeList"
                    target="_blank"
                >
                    MAL: {{ $torrent->mal }}
                </a>
            </li>
        @endif

        @if (isset($torrent) && $torrent->tvdb > 0)
            <li class="meta__tvdb">
                <a
                    class="meta-id-tag"
                    href="https://www.thetvdb.com/?tab=series&id={{ $torrent->tvdb }}"
                    title="MyAnimeList"
                    target="_blank"
                >
                    TVDB: {{ $torrent->tvdb }}
                </a>
            </li>
        @endif
    </ul>
    <div class="meta__chips">
        <section class="meta__chip-container">
            @if (isset($torrent->keywords) && $torrent->keywords->isNotEmpty())
                <article class="meta__keywords">
                    <a
                        class="meta-chip"
                        href="{{ route('torrents.index', ['view' => 'group', 'keywords' => $torrent->keywords->pluck('name')->join(', ')]) }}"
                    >
                        <i class="{{ config('other.font-awesome') }} fa-tag meta-chip__icon"></i>
                        <h2 class="meta-chip__name">Keywords</h2>
                        <h3 class="meta-chip__value">
                            {{ $torrent->keywords->pluck('name')->join(', ') }}
                        </h3>
                    </a>
                </article>
            @endif
        </section>
    </div>
</section>
