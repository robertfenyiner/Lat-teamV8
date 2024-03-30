<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     Roardom <roardom@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Http\Livewire;

use App\Models\Scopes\ApprovedScope;
use App\Models\Torrent;
use App\Models\User;
use App\Traits\LivewireSort;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UserUploads extends Component
{
    use LivewireSort;
    use WithPagination;

    public ?User $user = null;

    #TODO: Update URL attributes once Livewire 3 fixes upstream bug. See: https://github.com/livewire/livewire/discussions/7746

    #[Url(history: true)]
    public int $perPage = 25;

    #[Url(history: true)]
    public string $name = '';

    #[Url(history: true)]
    public string $personalRelease = 'any';

    /**
     * @var string[]
     */
    #[Url(history: true)]
    public array $status = [];

    #[Url(history: true)]
    public string $sortField = 'created_at';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    #[Url(history: true)]
    public bool $showMorePrecision = false;

    final public function mount(int $userId): void
    {
        $this->user = User::find($userId);
    }

    final public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Torrent>
     */
    #[Computed]
    final public function uploads(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $uploads = Torrent::query()
            ->withCount('thanks')
            ->withSum('tips', 'bon')
            ->withoutGlobalScope(ApprovedScope::class)
            ->where('created_at', '>=', $this->user->created_at) // Unneeded, but increases performances
            ->where('user_id', '=', $this->user->id)
            ->when(
                $this->name,
                fn ($query) => $query
                    ->where('name', 'like', '%'.str_replace(' ', '%', $this->name).'%')
            )
            ->when(!empty($this->status), fn ($query) => $query->whereIntegerInRaw('status', $this->status))
            ->when($this->personalRelease === 'include', fn ($query) => $query->where('personal_release', '=', 1))
            ->when($this->personalRelease === 'exclude', fn ($query) => $query->where('personal_release', '=', 0))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return $uploads->setCollection($uploads->getCollection()->groupBy(fn ($torrent) => $torrent->created_at->format('Y-m')));
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.user-uploads', [
            'uploads' => $this->uploads,
        ]);
    }
}
