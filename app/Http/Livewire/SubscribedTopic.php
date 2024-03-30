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

use App\Models\Topic;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class SubscribedTopic extends Component
{
    use WithPagination;

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<Topic>
     */
    #[Computed]
    final public function topics(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Topic::query()
            ->select('topics.*')
            ->with([
                'user.group',
                'latestPoster',
                'forum',
                'reads' => fn ($query) => $query->whereBelongsto(auth()->user()),
            ])
            ->whereRelation('subscribedUsers', 'users.id', '=', auth()->id())
            ->authorized(canReadTopic: true)
            ->orderBy('last_post_created_at')
            ->paginate(25, ['*'], 'subscribedTopicsPage');
    }

    final public function updatedSubscribedTopicsPage(): void
    {
        $this->dispatch('paginationChanged');
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.subscribed-topic', [
            'topics' => $this->topics,
        ]);
    }
}
