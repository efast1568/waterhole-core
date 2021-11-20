@php
    $title = isset($group) ? 'Edit User Group' : 'Create a User Group';
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::dialog :title="$title" class="dialog--lg">
        <form
            method="POST"
            action="{{ isset($group) ? route('waterhole.admin.groups.update', compact('group')) : route('waterhole.admin.groups.store') }}"
        >
            @csrf
            @if (isset($group)) @method('PATCH') @endif

            <div class="stack-lg">
                <x-waterhole::validation-errors/>

                <div class="form-groups">
                    <x-waterhole::field name="name" label="Name">
                        <input
                            type="text"
                            name="name"
                            id="{{ $component->id }}"
                            class="input"
                            value="{{ old('name', $group->name ?? null) }}"
                            autofocus
                        >
                    </x-waterhole::field>

                    <div data-controller="reveal">
                        <div class="field__label">Appearance</div>

                        <div class="stack-lg">
                            <div>
                                <input type="hidden" name="is_public" value="0">
                                <label class="choice">
                                    <input type="checkbox" data-reveal-target="if" name="is_public" value="1" @if (old('is_public', $group->is_public ?? null)) checked @endif>
                                    Show this group as a badge
                                </label>
                            </div>

                            <x-waterhole::field name="icon" label="Icon" data-reveal-target="then">
                                <input
                                    type="text"
                                    name="icon"
                                    id="{{ $component->id }}"
                                    class="input"
                                    value="{{ old('icon', $group->icon ?? null) }}"
                                >
                            </x-waterhole::field>

                            <x-waterhole::field name="color" label="Color" data-reveal-target="then">
                                <x-waterhole::admin.color-picker
                                    name="color"
                                    id="{{ $component->id }}"
                                    value="{{ old('color', $group->color ?? null) }}"
                                />
                            </x-waterhole::field>
                        </div>
                    </div>

                    <div class="field">
                        <div class="field__label">Permissions</div>
                        <div>
                            <div class="table-container">
                                <table
                                    class="table permission-grid"
                                    data-controller="permission-grid"
                                    data-action="click->permission-grid#click mouseover->permission-grid#mouseover mouseout->permission-grid#reset"
                                >
                                    <colgroup>
                                        <col>
                                        @foreach ($abilities as $ability)
                                            <col>
                                        @endforeach
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <td></td>
                                            @foreach ($abilities as $ability)
                                                <th><span>{{ ucfirst($ability) }}</span>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($structure as $node)
                                            <tr>
                                                <th>
                                                    @if ($node->content instanceof Waterhole\Models\Channel)
                                                        <x-waterhole::channel-label
                                                            :channel="$node->content"
                                                        />
                                                    @else
                                                        {{ $node->content->name }}
                                                    @endif
                                                </th>
                                                @foreach ($abilities as $ability)
                                                    @if (method_exists($node->content, 'abilities') && in_array($ability, $node->content->abilities()))
                                                        <td class="choice-cell">
                                                            <label class="choice">
                                                                <input
                                                                    type="hidden"
                                                                    name="permissions[{{ $node->content->getMorphClass() }}:{{ $node->content->getKey() }}][{{ $ability }}]"
                                                                    value="{{ $node->content->permissions->member()->can($ability) ? 1 : 0 }}"
                                                                >
                                                                <input
                                                                    type="checkbox"
                                                                    name="permissions[{{ $node->content->getMorphClass() }}:{{ $node->content->getKey() }}][{{ $ability }}]"
                                                                    value="1"
                                                                    @if ($node->content->permissions->member()->can($ability)) disabled
                                                                    @endif
                                                                    @if (old("permissions.{$node->content->getMorphClass()}:{$node->content->getKey()}.$ability", $node->content->permissions->group($group ?? Waterhole\Models\Group::member())->can($ability))) checked
                                                                    @endif
                                                                    data-depends-on="
                                                                        @if ($ability !== 'view') permissions[{{ $node->content->getMorphClass() }}:{{ $node->content->getKey() }}][view] @endif
                                                                        "
                                                                >
                                                            </label>
                                                        </td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <div>
                        <div class="toolbar">
                            <button
                                type="submit"
                                class="btn btn--primary btn--wide"
                            >
                                {{ isset($group) ? 'Save Changes' : 'Create' }}
                            </button>
                            <a
                                href="{{ route('waterhole.admin.groups.index') }}"
                                class="btn"
                            >Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-waterhole::dialog>
</x-waterhole::admin>
