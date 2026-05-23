@foreach($fields as $field)
    <div class="mt-4">
        <x-input-label :for="'field_'.$field->id" :value="$field->name" />
        @if($field->type == 'textarea')
            <textarea name="fields[{{ $field->id }}]"
                      id="field_{{ $field->id }}"
                      class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                      rows="3">{{ old('fields.'.$field->id) }}</textarea>
        @else
            <x-text-input id="field_{{ $field->id }}" name="fields[{{ $field->id }}]"
                          class="block mt-1 w-full" value="{{ old('fields.'.$field->id) }}" />
        @endif
    </div>
@endforeach