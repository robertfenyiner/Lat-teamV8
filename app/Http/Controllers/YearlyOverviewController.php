<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 *
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Group;
use App\Models\History;
use App\Models\Post;
use App\Models\Thank;
use App\Models\Torrent;
use App\Models\TorrentRequest;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class YearlyOverviewController extends Controller
{
    /**
     * Get All Overviews.
     */
    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
    {
        // Site Years
        $siteYears = collect();

        for ($currentYear = (int) date('Y', strtotime('-1 year')); $currentYear >= date('Y', strtotime(config('other.birthdate'))); $currentYear--) {
            $siteYears->push($currentYear);
        }

        return view('stats.yearly_overviews.index', [
            'siteYears' => $siteYears,
        ]);
    }

    /**
     * Get A Year Overview.
     */
    public function show(int $year): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        // Year Validation
        $currentYear = now()->year;
        $birthYear = Carbon::parse(config('other.birthdate'))->year;

        abort_unless($birthYear <= $year && $year < $currentYear, 404);

        return view('stats.yearly_overviews.show', [
            'topMovies' => cache()->rememberForever(
                'yearly-overview:'.$year.':top-movies',
                fn () => Torrent::with('movie')
                    ->select([
                        'tmdb',
                        DB::raw('COUNT(h.id) as download_count'),
                        DB::raw('MIN(category_id) as category_id'),
                    ])
                    ->leftJoinSub(
                        History::query()
                            ->whereNotNull('completed_at')
                            ->where('history.created_at', '>=', $year.'-01-01 00:00:00')
                            ->where('history.created_at', '<=', $year.'-12-31 23:59:59'),
                        'h',
                        fn ($join) => $join->on('torrents.id', '=', 'h.torrent_id')
                    )
                    ->where('tmdb', '!=', 0)
                    ->whereIn('category_id', Category::select('id')->where('movie_meta', '=', true))
                    ->groupBy('tmdb')
                    ->orderByDesc('download_count')
                    ->take(10)
                    ->get()
            ),
            'bottomMovies' => cache()->rememberForever(
                'yearly-overview:'.$year.':bottom-movies',
                fn () => Torrent::with('movie')
                    ->select([
                        'tmdb',
                        DB::raw('COUNT(h.id) as download_count'),
                        DB::raw('MIN(category_id) as category_id'),
                    ])
                    ->leftJoinSub(
                        History::query()
                            ->whereNotNull('completed_at')
                            ->where('history.created_at', '>=', $year.'-01-01 00:00:00')
                            ->where('history.created_at', '<=', $year.'-12-31 23:59:59'),
                        'h',
                        fn ($join) => $join->on('torrents.id', '=', 'h.torrent_id')
                    )
                    ->where('tmdb', '!=', 0)
                    ->whereIn('category_id', Category::select('id')->where('movie_meta', '=', true))
                    ->groupBy('tmdb')
                    ->orderBy('download_count')
                    ->take(5)
                    ->get()
            ),
            'topTv' => cache()->rememberForever(
                'yearly-overview:'.$year.':top-tv',
                fn () => Torrent::with('tv')
                    ->select([
                        'tmdb',
                        DB::raw('COUNT(h.id) as download_count'),
                        DB::raw('MIN(category_id) as category_id'),
                    ])
                    ->leftJoinSub(
                        History::query()
                            ->whereNotNull('completed_at')
                            ->where('history.created_at', '>=', $year.'-01-01 00:00:00')
                            ->where('history.created_at', '<=', $year.'-12-31 23:59:59'),
                        'h',
                        fn ($join) => $join->on('torrents.id', '=', 'h.torrent_id')
                    )
                    ->where('tmdb', '!=', 0)
                    ->whereIn('category_id', Category::select('id')->where('tv_meta', '=', true))
                    ->groupBy('tmdb')
                    ->orderByDesc('download_count')
                    ->take(10)
                    ->get()
            ),
            'bottomTv' => cache()->rememberForever(
                'yearly-overview:'.$year.':bottom-tv',
                fn () => Torrent::with('tv')
                    ->select([
                        'tmdb',
                        DB::raw('COUNT(h.id) as download_count'),
                        DB::raw('MIN(category_id) as category_id'),
                    ])
                    ->leftJoinSub(
                        History::query()
                            ->whereNotNull('completed_at')
                            ->where('history.created_at', '>=', $year.'-01-01 00:00:00')
                            ->where('history.created_at', '<=', $year.'-12-31 23:59:59'),
                        'h',
                        fn ($join) => $join->on('torrents.id', '=', 'h.torrent_id')
                    )
                    ->where('tmdb', '!=', 0)
                    ->whereIn('category_id', Category::select('id')->where('tv_meta', '=', true))
                    ->groupBy('tmdb')
                    ->orderBy('download_count')
                    ->take(5)
                    ->get()
            ),
            'uploaders' => cache()->remember(
                'yearly-overview:'.$year.':uploaders',
                3600,
                fn () => Torrent::with('user.group')
                    ->where('created_at', '>=', $year.'-01-01 00:00:00')
                    ->where('created_at', '<=', $year.'-12-31 23:59:59')
                    ->where('anon', '=', false)
                    ->select(DB::raw('user_id, COUNT(*) as value'))
                    ->groupBy('user_id')
                    ->orderByRaw('COALESCE(value, 0) DESC')
                    ->take(10)
                    ->get()
            ),
            'posters' => $posters = cache()->remember(
                'yearly-overview:'.$year.':posts',
                3600,
                fn () => Post::with('user.group')
                    ->where('created_at', '>=', $year.'-01-01 00:00:00')
                    ->where('created_at', '<=', $year.'-12-31 23:59:59')
                    ->select(DB::raw('user_id, COUNT(*) as value'))
                    ->groupBy('user_id')
                    ->orderByRaw('COALESCE(value, 0) DESC')
                    ->take(10)
                    ->get()
            ),
            'requesters' => cache()->remember(
                'yearly-overview:'.$year.':requesters',
                3600,
                fn () => TorrentRequest::with(['user.group'])
                    ->where('created_at', '>=', $year.'-01-01 00:00:00')
                    ->where('created_at', '<=', $year.'-12-31 23:59:59')
                    ->where('user_id', '!=', 1)
                    ->where('anon', '=', false)
                    ->select(DB::raw('user_id, COUNT(*) as value'))
                    ->groupBy('user_id')
                    ->orderByRaw('COALESCE(value, 0) DESC')
                    ->take(10)
                    ->get()
            ),
            'fillers' => cache()->remember(
                'yearly-overview:'.$year.':fillers',
                3600,
                fn () => TorrentRequest::with('filler.group')
                    ->where('filled_when', '>=', $year.'-01-01 00:00:00')
                    ->where('filled_when', '<=', $year.'-12-31 23:59:59')
                    ->where('filled_by', '!=', 1)
                    ->where('filled_anon', '=', false)
                    ->select(DB::raw('filled_by, COUNT(*) as value'))
                    ->groupBy('filled_by')
                    ->orderByRaw('COALESCE(value, 0) DESC')
                    ->take(10)
                    ->get()
            ),
            'commenters' => cache()->remember(
                'yearly-overview:'.$year.':commenters',
                3600,
                fn () => Comment::with('user.group')
                    ->where('created_at', '>=', $year.'-01-01 00:00:00')
                    ->where('created_at', '<=', $year.'-12-31 23:59:59')
                    ->where('user_id', '!=', 1)
                    ->where('anon', '=', false)
                    ->select(DB::raw('user_id, COUNT(*) as value'))
                    ->groupBy('user_id')
                    ->orderByRaw('COALESCE(value, 0) DESC')
                    ->take(10)
                    ->get()
            ),
            'thankers' => cache()->remember(
                'yearly-overview:'.$year.':thankers',
                3600,
                fn () => Thank::with('user.group')
                    ->where('created_at', '>=', $year.'-01-01 00:00:00')
                    ->where('created_at', '<=', $year.'-12-31 23:59:59')
                    ->where('user_id', '!=', 1)
                    ->select(DB::raw('user_id, COUNT(*) as value'))
                    ->groupBy('user_id')
                    ->orderByRaw('COALESCE(value, 0) DESC')
                    ->take(10)
                    ->get()
            ),
            'newUsers' => cache()->rememberForever(
                'yearly-overview:'.$year.':new-users',
                fn () => User::query()
                    ->where('created_at', '>=', $year.'-01-01 00:00:00')
                    ->where('created_at', '<=', $year.'-12-31 23:59:59')
                    ->count()
            ),
            'movieUploads' => cache()->rememberForever(
                'yearly-overview:'.$year.':movie-uploads',
                fn () => Torrent::query()
                    ->where('created_at', '>=', $year.'-01-01 00:00:00')
                    ->where('created_at', '<=', $year.'-12-31 23:59:59')
                    ->whereIn('category_id', Category::select('id')->where('movie_meta', '=', true))
                    ->count()
            ),
            'tvUploads' => cache()->rememberForever(
                'yearly-overview:'.$year.':tv-uploads',
                fn () => Torrent::query()
                    ->where('created_at', '>=', $year.'-01-01 00:00:00')
                    ->where('created_at', '<=', $year.'-12-31 23:59:59')
                    ->whereIn('category_id', Category::select('id')->where('tv_meta', '=', true))
                    ->count()
            ),
            'totalUploads' => cache()->rememberForever(
                'yearly-overview:'.$year.':total-uploads',
                fn () => Torrent::query()
                    ->where('created_at', '>=', $year.'-01-01 00:00:00')
                    ->where('created_at', '<=', $year.'-12-31 23:59:59')
                    ->count()
            ),
            'totalDownloads' => cache()->rememberForever(
                'yearly-overview:'.$year.':total-downloads',
                fn () => History::query()
                    ->where('created_at', '>=', $year.'-01-01 00:00:00')
                    ->where('created_at', '<=', $year.'-12-31 23:59:59')
                    ->count()
            ),
            'staffers' => cache()->remember(
                'yearly-overview:'.$year.':staffers',
                3600,
                fn () => Group::query()
                    ->with('users.group')
                    ->where('is_modo', '=', 1)
                    ->orWhere('is_admin', '=', 1)
                    ->orderByDesc('position')
                    ->get()
            ),
            'birthYear' => $birthYear,
            'year'      => $year,
        ]);
    }
}
