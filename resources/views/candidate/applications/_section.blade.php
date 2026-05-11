@php
    /** @var \Illuminate\Support\Collection<\App\Models\ApplicationField> $fields */
    /** @var \Illuminate\Support\Collection $responses */
    /** @var \App\Models\Application $application */
    $isHealth = $section === 'health';
    $isDeclaration = $section === 'declaration';
@endphp
<div class="card mb-6 @if($isHealth) border-amber-200 @endif">
    <div class="card-header @if($isHealth) bg-amber-50 @endif">
        <h2 class="font-semibold">{{ $title }}</h2>
        @if($isHealth)
            <span class="text-xs text-amber-700 inline-flex items-center gap-1">
                <x-icon name="warning" weight="fill" /> Confidentiel — utilisé uniquement en cas de besoin
            </span>
        @endif
    </div>
    <div class="card-body space-y-4">
        @foreach($fields as $field)
            @php $current = $responses[$field->id] ?? null; @endphp

            @if(in_array($field->type, ['text', 'email', 'tel', 'url', 'number', 'date']))
                <x-input :type="$field->type"
                         :name="'responses['.$field->id.']'"
                         :label="$field->label"
                         :value="$current?->value"
                         :required="$field->is_required"
                         :help="$field->help_text" />

            @elseif($field->type === 'textarea')
                <x-textarea :name="'responses['.$field->id.']'"
                            :label="$field->label"
                            :value="$current?->value"
                            :required="$field->is_required"
                            :help="$field->help_text"
                            rows="5" />

            @elseif($field->type === 'select')
                <x-select :name="'responses['.$field->id.']'"
                          :label="$field->label"
                          :options="collect($field->options ?? [])->mapWithKeys(fn($o) => [$o['value'] => $o['label']])->all()"
                          :selected="$current?->value"
                          :required="$field->is_required"
                          placeholder="—" />

            @elseif($field->type === 'radio')
                @php
                    $oldRadio = old('responses.'.$field->id, $current?->value);
                @endphp
                <div>
                    <label class="form-label">{{ $field->label }} @if($field->is_required)<span class="text-red-500">*</span>@endif</label>
                    <div class="flex flex-wrap gap-4 mt-2">
                        @foreach(($field->options ?? []) as $opt)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="radio"
                                       name="responses[{{ $field->id }}]"
                                       value="{{ $opt['value'] }}"
                                       @checked((string) ($oldRadio ?? '') === (string) $opt['value'])
                                       @required($field->is_required && $loop->first)
                                       class="text-brand-600 focus:ring-brand-500">
                                {{ $opt['label'] }}
                            </label>
                        @endforeach
                    </div>
                    @if($field->help_text)<p class="text-xs text-slate-500 mt-1">{{ $field->help_text }}</p>@endif
                </div>

            @elseif($field->type === 'checkbox' || $field->type === 'multiselect')
                @php
                    $checkedValues = (array) old('responses.'.$field->id, (array)($current?->value_json ?? []));
                @endphp
                <div>
                    <label class="form-label">
                        {{ $field->label }}
                        @if($field->is_required)<span class="text-red-500">*</span>@endif
                    </label>
                    <div class="space-y-1 mt-2">
                        @foreach(($field->options ?? []) as $opt)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox"
                                       name="responses[{{ $field->id }}][]"
                                       value="{{ $opt['value'] }}"
                                       @checked(in_array((string) $opt['value'], array_map('strval', $checkedValues), true))
                                       class="rounded text-brand-600 focus:ring-brand-500">
                                {{ $opt['label'] }}
                            </label>
                        @endforeach
                    </div>
                    @if($field->help_text)<p class="text-xs text-slate-500 mt-1">{{ $field->help_text }}</p>@endif
                </div>

            @elseif(in_array($field->type, ['file', 'video']))
                @php $doc = $application->documents->firstWhere('category', $field->key); @endphp
                <div>
                    <label class="form-label">
                        {{ $field->label }}
                        @if($field->is_required && ! $doc)<span class="text-red-500">*</span>@endif
                    </label>

                    {{-- APERÇU DU FICHIER ACTUEL --}}
                    @if($doc)
                        @php
                            $isImage = str_starts_with($doc->mime_type ?? '', 'image/');
                            $isVideo = str_starts_with($doc->mime_type ?? '', 'video/');
                        @endphp
                        <div class="border border-slate-200 rounded-lg p-3 bg-slate-50 mb-2 flex items-center gap-3">
                            @if($isImage)
                                <a href="{{ $doc->url() }}" target="_blank" class="block">
                                    <img src="{{ $doc->url() }}" alt="" class="w-16 h-16 object-cover rounded">
                                </a>
                            @elseif($isVideo)
                                <x-icon name="film-strip" class="text-3xl text-slate-400" />
                            @else
                                <x-icon name="file-text" class="text-3xl text-slate-400" />
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">{{ $doc->original_name }}</p>
                                <p class="text-xs text-slate-500">{{ $doc->humanSize() }} · {{ $doc->mime_type }}</p>
                                <a href="{{ $doc->url() }}" target="_blank"
                                   class="text-xs text-brand-600 hover:underline inline-flex items-center gap-1">
                                    <x-icon name="eye" /> Consulter / Télécharger
                                </a>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 mb-1">Vous pouvez choisir un nouveau fichier pour remplacer celui-ci :</p>
                    @endif

                    <input type="file" name="responses[{{ $field->id }}]" class="form-input">
                    @if($field->help_text)<p class="text-xs text-slate-500 mt-1">{{ $field->help_text }}</p>@endif
                </div>
            @endif

            @error('responses.'.$field->id)<p class="form-error">{{ $message }}</p>@enderror
        @endforeach
    </div>
</div>
