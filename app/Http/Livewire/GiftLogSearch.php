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

use App\Models\Gift;
use App\Traits\LivewireSort;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @property \Illuminate\Contracts\Pagination\LengthAwarePaginator $gifts
 */
class GiftLogSearch extends Component
{
    use LivewireSort;
    use WithPagination;

    #TODO: Update URL attributes once Livewire 3 fixes upstream bug. See: https://github.com/livewire/livewire/discussions/7746

    #[Url(history: true)]
    public string $sender = '';

    #[Url(history: true)]
    public string $receiver = '';

    #[Url(history: true)]
    public string $comment = '';

    #[Url(history: true)]
    public string $sortField = 'created_at';

    #[Url(history: true)]
    public string $sortDirection = 'desc';

    #[Url(history: true)]
    public int $perPage = 25;

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Gift>
     */
    #[Computed]
    final public function gifts(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Gift::with([
            'sender'    => fn ($query) => $query->withTrashed()->with('group'),
            'recipient' => fn ($query) => $query->withTrashed()->with('group'),
        ])
            ->when($this->sender, fn ($query) => $query->whereRelation('sender', 'username', '=', $this->sender))
            ->when($this->receiver, fn ($query) => $query->whereRelation('recipient', 'username', '=', $this->receiver))
            ->when($this->comment, fn ($query) => $query->where('comment', 'LIKE', '%'.$this->comment.'%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    final public function render(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.gift-log-search', [
            'gifts' => $this->gifts,
        ]);
    }
}
