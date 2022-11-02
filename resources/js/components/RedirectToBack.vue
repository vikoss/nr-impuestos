
<template>
  <div class="inline-block">
    <div @click="goBack" class="flex items-center cursor-pointer">
      <arrow-go-to-back />
      <p class="text-wine text-lg ml-2">Regresar</p>
    </div>
  </div>
</template>

<script>
import { useRouter } from 'vue-router'
import ArrowGoToBack from './svg/ArrowGoToBack.vue'
export default {
  name: 'RedirectToBack',
  props: {
    route: {
      type: Function,
      default: () => (false),
    },
  },
  components: { ArrowGoToBack },
  setup(props) {
    const router = useRouter()
    const goBack = () => {
      if (props.route()) {
        return
      }
      router.go(-1)
    }
    return { goBack }
  },
}
</script>