<section class="panelV2" x-data="{ tab: @entangle('warningTab').live }">
    <header class="panel__header">
        <h2 class="panel__heading">{{ __('user.warnings') }}</h2>
        @if (auth()->user()->group->is_modo)
            <div class="panel__actions" x-data="userWarnings">
                <div class="panel__action" x-data="dialogLivewire">
                    <button class="form__button form__button--text" x-bind="showDialog">
                        {{ __('common.add') }}
                    </button>
                    <dialog class="dialog" x-bind="dialogElement">
                        <h3 class="dialog__heading">Warn user: {{ $user->username }}</h3>
                        <form class="dialog__form" x-bind="dialogForm">
                            <p class="form__group">
                                <textarea
                                    id="warn_reason"
                                    class="form__textarea"
                                    name="message"
                                    maxlength="255"
                                    required
                                    wire:model="message"
                                ></textarea>
                                <label class="form__label form__label--floating" for="warn_reason">
                                    Reason
                                </label>
                            </p>
                            <p class="form__group">
                                <button
                                    class="form__button form__button--filled"
                                    wire:click="store"
                                    x-bind="submitDialogForm"
                                >
                                    {{ __('common.save') }}
                                </button>
                                <button
                                    formmethod="dialog"
                                    formnovalidate
                                    class="form__button form__button--outlined"
                                >
                                    {{ __('common.cancel') }}
                                </button>
                            </p>
                        </form>
                    </dialog>
                </div>
                <form class="panel__action">
                    @csrf
                    <button
                        x-on:click.prevent="massDestroy"
                        data-b64-deletion-message="{{ base64_encode('Are you sure you want to delete all warnings?') }}"
                        class="form__button form__button--text"
                    >
                        {{ __('user.delete-all') }}
                    </button>
                </form>
                <form class="panel__action">
                    @csrf
                    <button
                        x-on:click.prevent="massDeactivate"
                        data-b64-deletion-message="{{ base64_encode('Are you sure you want to deactivate all warnings?') }}"
                        class="form__button form__button--text"
                    >
                        {{ __('user.deactivate-all') }}
                    </button>
                </form>
            </div>
        @endif
    </header>
    <menu class="panel__tabs">
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'automated' && 'panel__tab--active'"
            x-on:click="tab = 'automated'"
        >
            Automated ({{ $automatedWarningsCount ?? 0 }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'manual' && 'panel__tab--active'"
            x-on:click="tab = 'manual'"
        >
            Manual ({{ $manualWarningsCount ?? 0 }})
        </li>
        <li
            class="panel__tab"
            role="tab"
            x-bind:class="tab === 'deleted' && 'panel__tab--active'"
            x-on:click="tab = 'deleted'"
        >
            Soft Deleted ({{ $deletedWarningsCount ?? 0 }})
        </li>
    </menu>
    <div class="data-table-wrapper" x-data="userWarnings">
        <table class="data-table">
            <thead>
                <tr>
                    <th wire:click="sortBy('warned_by')" role="columnheader button">
                        {{ __('user.warned-by') }}
                        @include('livewire.includes._sort-icon', ['field' => 'warned_by'])
                    </th>
                    @if ($warningTab !== 'manual')
                        <th wire:click="sortBy('torrent_id')" role="columnheader button">
                            {{ __('torrent.torrent') }}
                            @include('livewire.includes._sort-icon', ['field' => 'torrent_id'])
                        </th>
                    @endif

                    <th wire:click="sortBy('reason')" role="columnheader button">
                        {{ __('common.reason') }}
                        @include('livewire.includes._sort-icon', ['field' => 'reason'])
                    </th>
                    <th wire:click="sortBy('created_at')" role="columnheader button">
                        {{ __('user.created-on') }}
                        @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                    </th>
                    <th wire:click="sortBy('expires_on')" role="columnheader button">
                        {{ __('user.expires-on') }}
                        @include('livewire.includes._sort-icon', ['field' => 'expires_on'])
                    </th>
                    <th wire:click="sortBy('active')" role="columnheader button">
                        {{ __('user.active') }}
                        @include('livewire.includes._sort-icon', ['field' => 'active'])
                    </th>
                    <th>{{ __('common.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($warnings as $warning)
                    <tr x-ref="warning" data-warning-id="{{ $warning->id }}">
                        <td>
                            <x-user_tag :user="$warning->staffuser" :anon="false" />
                        </td>
                        @if ($warningTab !== 'manual')
                            <td>
                                @isset($warning->torrenttitle)
                                    <a
                                        href="{{ route('torrents.show', ['id' => $warning->torrenttitle->id]) }}"
                                    >
                                        {{ $warning->torrenttitle->name }}
                                    </a>
                                @else
                                    n/a
                                @endisset
                            </td>
                        @endif

                        <td>{{ $warning->reason }}</td>
                        <td>
                            <time
                                datetime="{{ $warning->created_at }}"
                                title="{{ $warning->created_at }}"
                            >
                                {{ $warning->created_at }}
                            </time>
                        </td>
                        <td>
                            <time
                                datetime="{{ $warning->expires_on }}"
                                title="{{ $warning->expires_on }}"
                            >
                                {{ $warning->expires_on }}
                            </time>
                        </td>
                        <td>
                            @if ($warning->active)
                                <i
                                    class="{{ config('other.font-awesome') }} fa-check text-green"
                                ></i>
                            @else
                                <i
                                    class="{{ config('other.font-awesome') }} fa-times text-red"
                                ></i>
                            @endif
                        </td>
                        @if (auth()->user()->group->is_modo)
                            <td>
                                <menu class="data-table__actions">
                                    @if ($warningTab === 'deleted')
                                        <li class="data-table__action">
                                            <form>
                                                @csrf
                                                @method('PATCH')
                                                <button
                                                    x-on:click.prevent="restoreWarning"
                                                    data-b64-deletion-message="{{ base64_encode('Are you sure you want to restore this warning: ' . $warning->reason . '?') }}"
                                                    class="form__button form__button--text"
                                                >
                                                    {{ __('user.restore') }}
                                                </button>
                                            </form>
                                        </li>
                                    @else
                                        @if ($warning->created_at->addDays(config('hitrun.expire'))->isFuture())
                                            @if ($warning->active)
                                                <li class="data-table__action">
                                                    <form>
                                                        @csrf
                                                        <button
                                                            x-on:click.prevent="deactivateWarning"
                                                            data-b64-deletion-message="{{ base64_encode('Are you sure you want to deactivate this warning: ' . $warning->reason . '?') }}"
                                                            class="form__button form__button--text"
                                                        >
                                                            {{ __('user.deactivate') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            @else
                                                <li class="data-table__action">
                                                    <form>
                                                        @csrf
                                                        <button
                                                            x-on:click.prevent="reactivateWarning"
                                                            data-b64-deletion-message="{{ base64_encode('Are you sure you want to reactivate this warning: ' . $warning->reason . '?') }}"
                                                            class="form__button form__button--text"
                                                        >
                                                            {{ __('user.reactivate') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif
                                        @endif

                                        <li class="data-table__action">
                                            <form>
                                                @csrf
                                                <button
                                                    x-on:click.prevent="destroyWarning"
                                                    data-b64-deletion-message="{{ base64_encode('Are you sure you want to delete this warning: ' . $warning->reason . '?') }}"
                                                    class="form__button form__button--text"
                                                >
                                                    {{ __('common.delete') }}
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                </menu>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td
                            colspan="{{ 6 + (int) ($warningTab !== 'manual') + (int) auth()->user()->group->is_modo }}"
                        >
                            {{ __('user.no-warning') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $warnings->links('partials.pagination') }}
    </div>
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
        document.addEventListener('alpine:init', () => {
            Alpine.data('userWarnings', () => ({
                massDestroy() {
                    this.confirmAction(() => this.$wire.massDestroy());
                },
                massDeactivate() {
                    this.confirmAction(() => this.$wire.massDeactivate());
                },
                destroyWarning() {
                    this.confirmAction(() =>
                        this.$wire.destroy(this.$refs.warning.dataset.warningId),
                    );
                },
                reactivateWarning() {
                    this.confirmAction(() =>
                        this.$wire.reactivate(this.$refs.warning.dataset.warningId),
                    );
                },
                deactivateWarning() {
                    this.confirmAction(() =>
                        this.$wire.deactivate(this.$refs.warning.dataset.warningId),
                    );
                },
                restoreWarning() {
                    this.confirmAction(() =>
                        this.$wire.restore(this.$refs.warning.dataset.warningId),
                    );
                },
                confirmAction(onConfirm) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: atob(this.$el.dataset.b64DeletionMessage),
                        icon: 'warning',
                        showConfirmButton: true,
                        showCancelButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            onConfirm();
                        }
                    });
                },
            }));
        });
    </script>
</section>
