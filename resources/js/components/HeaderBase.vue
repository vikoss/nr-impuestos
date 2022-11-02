<template>
  <header class="bg-white h-20 flex flex-col justify-center shadow-md">
    <ul class="flex justify-between">
      <li class="ml-11">
        <router-link :to="{ name: 'Home' }">
          <img src="./../assets/images/anr.png" alt="logo" class="h-8">
        </router-link>
      </li>
      <li class="mr-11">
        <div class="flex justify-center h-full relative">
          <div class="flex items-center">
            <p class="mr-3 text-black text-sm sm:text-base font-normal">
              {{ user.name }}
            </p>
            <arrow-bottom-svg
              hex-color="#333333"
              class="mt-1 cursor-pointer w-4 h-4"
              @click="app.showWindowLogOut = !app.showWindowLogOut"
            />
          </div>
          <div v-show="app.showWindowLogOut" class="text-right absolute top-12 right-0">
            <input
              class="bg-white px-4 py-1 text-base hover:bg-gray-300 hover:border-b cursor-pointer rounded-sm shadow-lg"
              type="button"
              value="Salir"
              @click="logOut"
            >
          </div>
        </div>
      </li>
    </ul>
  </header>
</template>

<script>
import ArrowBottomSvg from './svg/ArrowBottom.vue'
import { currentUser } from './../helpers/localstorage'
import { reactive } from 'vue'
import { logout } from './../api/authenticate'
import { useRouter } from 'vue-router'
export default {
  name: 'HeaderBase',
  components: { ArrowBottomSvg },
  setup() {
    const router = useRouter()
    const app = reactive({
      showWindowLogOut: false,
    })
    const user = reactive(currentUser())
    const logOut = async () => {
      await logout()
      router.push({ name: 'Login' })
    }
    return { user, logOut, app }
  },
}
</script>