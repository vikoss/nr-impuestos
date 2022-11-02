<template>
  <div>
    <input
      type="button"
      value="&lt;"
      :disabled="currentPage <= 1"
      :class="currentPage"
      class="py-1 px-2 text-wine mr-1.5 disabled:text-gray disabled:cursor-not-allowed cursor-pointer"
      @click="changePage(currentPage - 1)"
    >
    <input
      type="button"
      :value="page"
      v-for="(page, index) in pages"
      :key="index"
      :disabled="currentPage === page"
      :class="{ 'font-bold': currentPage === page }"
      class="py-1 px-2 text-wine mx-1.5 disabled:text-gray disabled:cursor-not-allowed cursor-pointer"
      @click="changePage(page)"
    >
    <input
      type="button"
      value="&gt;"
      :disabled="currentPage >= lastPage"
      :class="currentPage"
      class="py-1 px-2 text-wine ml-1.5 disabled:text-gray disabled:cursor-not-allowed cursor-pointer"
      @click="changePage(currentPage + 1)"
    >
  </div>
</template>

<script>
import { computed } from 'vue'
export default {
  name: 'PaginationBase',
  props: {
    currentPage: {
      type: Number,
      default: 1
    },
    lastPage: {
      type: Number,
      default: 1,
    },
    offset: {
      type: Number,
      default: 1
    },
  },
  setup(props, { emit }) {
    const changePage = (page) => emit('pageChanged', page)
    const pages = computed(() => {
      let from = (props.currentPage - Math.floor(props.offset / 2)) < 1 ? 1 : (props.currentPage - Math.floor(props.offset / 2))
      let to = (from + props.offset - 1) > props.lastPage ? props.lastPage : (from + props.offset - 1)
      return Array.from(Array((to - from) + 1), (x, index) => index + from)
    });
    return { changePage, pages }
  },
}
</script>