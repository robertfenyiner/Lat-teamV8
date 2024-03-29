<div style="display: flex; flex-direction: column; gap: 1rem">
    <section class="panelV2" x-data>
        <header class="panel__header">
            <h2 class="panel__heading">UNIT3D Backup Manager</h2>
            <div class="panel__actions">
                <button
                    id="create-backup"
                    class="panel__action form__button form__button--text"
                    x-on:click="backup()"
                >
                    {{ __('backup.create_a_new_backup') }}
                </button>
                <a
                    class="panel__action form__button form__button--text"
                    id="create-backup-only-db"
                    x-on:click.prevent="backup('only-db')"
                >
                    {{ __('backup.create_a_new_db_backup') }}
                </a>
                <a
                    class="panel__action form__button form__button--text"
                    id="create-backup-only-files"
                    x-on:click.prevent="backup('only-files')"
                >
                    {{ __('backup.create_a_new_files_backup') }}
                </a>
                <button
                    class="form__standard-icon-button"
                    wire:loading.attr="disabled"
                    wire:click="$refresh"
                >
                    <i
                        class="{{ config('other.font-awesome') }} fa-sync"
                        wire:loading.class="fa-spin"
                    ></i>
                </button>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">Disk</th>
                        <th scope="col">Healthy</th>
                        <th scope="col">Amount of backups</th>
                        <th scope="col">Newest backup</th>
                        <th scope="col">Used storage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->backupStatuses as $backupStatus)
                        <tr>
                            <td>{{ $backupStatus['disk'] }}</td>
                            <td>
                                @if ($backupStatus['healthy'])
                                    <i
                                        class="{{ config('other.font-awesome') }} fa-check-circle text-success"
                                    ></i>
                                @else
                                    <i
                                        class="{{ config('other.font-awesome') }} fa-times-circle text-danger"
                                    ></i>
                                @endif
                            </td>
                            <td>{{ $backupStatus['amount'] }}</td>
                            <td>{{ $backupStatus['newest'] }}</td>
                            <td>{{ $backupStatus['usedStorage'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('backup.existing_backups') }}
        </h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col">{{ __('backup.location') }}</th>
                        <th scope="col">{{ __('backup.date') }}</th>
                        <th scope="col">{{ __('backup.file_size') }}</th>
                        <th scope="col">{{ __('backup.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->backups as $backup)
                        <tr>
                            <td>{{ $backup['path'] }}</td>
                            <td>{{ $backup['date'] }}</td>
                            <td>{{ $backup['size'] }}</td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action">
                                        <a
                                            class="form__button form__button--text"
                                            target="_blank"
                                            wire:click.prevent="downloadBackup('{{ $backup['path'] }}')"
                                        >
                                            {{ __('common.download') }}
                                        </a>
                                    </li>
                                    <li class="data-table__action" x-data="dialog">
                                        <button
                                            class="form__button form__button--text"
                                            x-bind="showDialog"
                                        >
                                            {{ __('common.delete') }}
                                        </button>
                                        <dialog class="dialog" x-bind="dialogElement">
                                            <h3 class="dialog__heading">Delete backup</h3>
                                            <form class="dialog__form" x-bind="dialogForm">
                                                @csrf
                                                <p class="form__group">
                                                    Are you sure you want to delete the backup
                                                    created at {{ $backup['date'] }} ?
                                                </p>
                                                <p class="form__group">
                                                    <button
                                                        wire:click="deleteBackup({{ $loop->index }}); $refresh;"
                                                        formmethod="dialog"
                                                        formnovalidate
                                                        class="form__button form__button--filled"
                                                    >
                                                        {{ __('common.delete') }}
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
                                    </li>
                                </menu>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No backups present</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
        document.addEventListener('livewire:init', function () {
          const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
          })
          @this.on('showErrorToast', function (message) {
            Toast.fire({
              text: message,
              duration: 10000,
              gravity: 'bottom',
              position: 'right',
              backgroundColor: 'red',
              className: 'toastify-custom',
            })
          })
        })
        function backup(option = '') {
          @this.createBackup(option)
          Swal.fire({
            title: '<strong style=" color: rgb(17,17,17);">Success</strong>',
            icon: 'success',
            html: 'Creating a new backup in the background...' + (option ? ' (' + option + ')' : ''),
            showCloseButton: true,
          })
        }
    </script>
</div>
