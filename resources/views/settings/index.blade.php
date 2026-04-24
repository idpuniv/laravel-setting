<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Paramètres</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .accordion-button:not(.collapsed) {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: #dee2e6;
        }

        .group-description {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>

<body>

    <div class="container py-4">
        <div class="mb-4">
            <h1>Paramètres</h1>
            <p class="text-muted">Gérez les paramètres de l'application et vos préférences personnelles.</p>
        </div>

        <div class="card shadow-sm">
            <div class="accordion" id="settingsAccordion">
                @foreach ($groups as $groupKey => $group)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse_{{ $groupKey }}" aria-expanded="false"
                                aria-controls="collapse_{{ $groupKey }}">
                                <div>
                                    <i class="{{ $group['icon'] }} me-2"></i>
                                    <strong>{{ $group['label'] }}</strong>
                                    @if (isset($group['description']))
                                        <br>
                                        <small class="text-muted">{{ $group['description'] }}</small>
                                    @endif
                                </div>
                            </button>
                        </h2>
                        <div id="collapse_{{ $groupKey }}" class="accordion-collapse collapse"
                            data-bs-parent="#settingsAccordion">
                            <div class="accordion-body">
                                @foreach ($group['fields'] as $fieldKey)
                                    @php
                                        $field = $fields[$fieldKey];
                                        $isSystemField = str_starts_with($fieldKey, 'system.');
                                        $cantEdit =
                                            $isSystemField &&
                                            !Auth::user()?->can(\App\Permissions\SystemPermissions::EDIT_SETTINGS);
                                    @endphp

                                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        {{-- Boolean : Switch --}}
                                        @if ($field['type'] === 'boolean')
                                            <div class="form-check form-switch">
                                                <input class="form-check-input settings-auto-save" type="checkbox"
                                                    role="switch" id="field_{{ Str::slug($fieldKey) }}"
                                                    data-key="{{ $fieldKey }}" value="1"
                                                    {{ $settings[$fieldKey] ? 'checked' : '' }}
                                                    {{ $cantEdit ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="field_{{ Str::slug($fieldKey) }}">
                                                    <strong>{{ $field['label'] }}</strong>
                                                </label>
                                                @if (isset($field['description']))
                                                    <div class="form-text">{{ $field['description'] }}</div>
                                                @endif
                                            </div>

                                            {{-- Select --}}
                                        @elseif($field['type'] === 'select')
                                            <label for="field_{{ Str::slug($fieldKey) }}" class="form-label">
                                                <strong>{{ $field['label'] }}</strong>
                                            </label>
                                            <select class="form-select settings-auto-save"
                                                id="field_{{ Str::slug($fieldKey) }}" data-key="{{ $fieldKey }}"
                                                {{ $cantEdit ? 'disabled' : '' }}>
                                                @foreach ($field['options'] as $optValue => $optLabel)
                                                    <option value="{{ $optValue }}"
                                                        {{ $settings[$fieldKey] == $optValue ? 'selected' : '' }}>
                                                        {{ $optLabel }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if (isset($field['description']))
                                                <div class="form-text">{{ $field['description'] }}</div>
                                            @endif

                                            {{-- Number --}}
                                        @elseif($field['type'] === 'number')
                                            <label for="field_{{ Str::slug($fieldKey) }}" class="form-label">
                                                <strong>{{ $field['label'] }}</strong>
                                            </label>
                                            <input type="number" class="form-control settings-auto-save"
                                                id="field_{{ Str::slug($fieldKey) }}" data-key="{{ $fieldKey }}"
                                                value="{{ $settings[$fieldKey] }}" min="{{ $field['min'] ?? '' }}"
                                                max="{{ $field['max'] ?? '' }}" {{ $cantEdit ? 'disabled' : '' }}>
                                            @if (isset($field['description']))
                                                <div class="form-text">{{ $field['description'] }}</div>
                                            @endif

                                            {{-- Text --}}
                                        @else
                                            <label for="field_{{ Str::slug($fieldKey) }}" class="form-label">
                                                <strong>{{ $field['label'] }}</strong>
                                            </label>
                                            <input type="text" class="form-control settings-auto-save"
                                                id="field_{{ Str::slug($fieldKey) }}" data-key="{{ $fieldKey }}"
                                                value="{{ $settings[$fieldKey] }}" {{ $cantEdit ? 'disabled' : '' }}>
                                            @if (isset($field['description']))
                                                <div class="form-text">{{ $field['description'] }}</div>
                                            @endif
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Toast succès --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="saveToast" class="toast align-items-center text-bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle-fill me-2"></i> Paramètre sauvegardé
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Fermer"></button>
            </div>
        </div>
    </div>

    {{-- Toast erreur --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> Erreur lors de la sauvegarde
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Fermer"></button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const saveToast = new bootstrap.Toast(document.getElementById('saveToast'), {
                delay: 2000
            });
            const errorToast = new bootstrap.Toast(document.getElementById('errorToast'), {
                delay: 3000
            });
            let saveTimeout;

            document.querySelectorAll('.settings-auto-save').forEach(element => {
                element.addEventListener('change', function() {
                    const key = this.dataset.key;
                    let value;

                    if (this.type === 'checkbox') {
                        value = this.checked ? '1' : '0';
                    } else {
                        value = this.value;
                    }

                    clearTimeout(saveTimeout);
                    this.classList.add('border', 'border-warning');

                    saveTimeout = setTimeout(() => {
                        saveSetting(key, value, this);
                    }, 500);
                });
            });

            function saveSetting(key, value, element) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                fetch('{{ route('settings.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            key: key,
                            value: value
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Erreur serveur');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        element.classList.remove('border-warning');
                        element.classList.add('border', 'border-success');
                        saveToast.show();
                        setTimeout(() => {
                            element.classList.remove('border', 'border-success');
                        }, 1500);
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        element.classList.remove('border-warning');
                        element.classList.add('border', 'border-danger');
                        errorToast.show();
                        setTimeout(() => {
                            element.classList.remove('border', 'border-danger');
                        }, 2000);
                    });
            }
        });
    </script>

</body>

</html>
