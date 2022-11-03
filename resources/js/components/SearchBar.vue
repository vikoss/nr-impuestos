<template>
  <div class="bg-gray-100 h-16 py-2 px-9 flex flex-col justify-center"
    style="box-shadow: 0px 3px 6px #00000029;"
  >
    <div
      class="bg-white flex rounded-md justify-center items-center"
      :class="{ 'shadow': app.focused }"
    >
      <input
        :id="id"
        class="h-10 py-3 px-3.5 rounded-md outline-none text-black text-sm sm:text-base font-medium w-full"
        :class="{ 'uppercase': isUpperCase, [classInput]: true }"
        :type="type"
        :placeholder="placeholder"
        :value="value ? value : modelValue"
        :autocomplete="autocomplete"
        :maxlength="maxLength"
        :disabled="disabled"
        @input="({ target }) => $emit('update:modelValue', target.value)"
        @keyup.enter="app.onClick"
        @blur="() => (app.focused = false)"
        @focus="() => (app.focused = true)"
      >
      <search-svg class="mr-2 cursor-pointer" @click="app.onClick" />
    </div>
  </div>
</template>

<script>
import { reactive } from 'vue'
import SearchSvg from './svg/Search.vue'

export default {
  name: 'SearchBar',
  props: {
    value: {
      type: String,
      default: '',
    },
    id: {
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
  emits: ['update:modelValue', 'search'],
  components: { SearchSvg },
  setup (props, { emit }) {
    const app = reactive({
      focused: false,
      onClick: () => emit('search'),
    })
    return { app }
  },
}
</script>
