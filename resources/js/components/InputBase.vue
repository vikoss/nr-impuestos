<template>
  <label
    :for="id"
    class="text-wine text-base font-medium block relative"
  >
    {{ label }}
    <input
      :id="id"
      class="h-11 py-3 px-3.5 rounded-md border border-wine border-solid
        outline-none text-black text-base disabled:font-medium mt-1 w-full focus:border-2
        focus:shadow disabled:bg-gray-300 disabled:border-gray-100 disabled:text-gray
      "
      :class="{'uppercase': isUpperCase, [classInput]: true}"
      :type="type"
      :placeholder="placeholder"
      :value="value ? value : modelValue"
      :autocomplete="autocomplete"
      :maxlength="maxLength"
      :disabled="disabled"
      @input="(event) => $emit('update:modelValue', event.target.value)"
    >
  </label>
</template>

<script>
export default {
  name: 'InputBase',
  props: {
    value: {
      type: String,
      default: '',
    },
    id: {
      type: String,
      default: '',
    },
    label: {
      type: String,
      default: '',
    },
    placeholder: {
      type: String,
      default: '',
    },
    type: {
      type: String,
      default: 'text',
    },
    modelValue: {
      type: String,
      default: '',
    },
    autocomplete: {
      type: String,
      default: 'off',
    },
    maxLength: {
      type: String,
      default: '100',
    },
    isUpperCase: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false
    },
    classInput: {
      type: String,
      default: '',
    },
  },
  emits: ['update:modelValue'],
}
</script>