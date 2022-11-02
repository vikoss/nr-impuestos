<template>
  <div class="mb-6 mt-5 md:mb-12">
    <h1 class="text-wine text-3xl font-bold">
      {{ title }}
    </h1>
    <span class="h-0.5 w-20 block bg-wine my-3 sm:mt-4 sm:mb-5" />
    <h2 class="text-base text-black">
      {{ subtitle }}
    </h2>
  </div>
</template>

<script>
export default {
  name: 'TitleBar',
  props: {
    title: {
      type: String,
      default: '',
    },
    subtitle: {
      type: String,
      default: '',
    },
  }
}
</script>