<template>
  <main class="flex flex-wrap min-h-screen">
    <div class="flex-grow py-7 px-6 sm:px-12 bg-wine h-full lg:h-auto text-center md:text-left" style="flex-basis: 540px;">
      <div class="bg-logo-nr h-24 w-auto bg-no-repeat bg-contain bg-center mb-5 sm:h-28 md:h-44 md:bg-left"></div>
      <h1 class="text-white font-light text-4xl sm:text-6xl lg:absolute lg:bottom-36">
        Bienvenido
        <br> a
        <strong class="font-bold">Impuestos</strong>
        <br> Nicolás Romero
      </h1>
      <h2 class="text-white font-light text-base mt-5 sm:text-xl lg:absolute sm:bottom-24">
        Nos renovamos para ofrecerte
        <strong class="font-bold">un mejor servicio.</strong>
      </h2>
    </div>
    <div class="flex-grow py-7 px-6 sm:px-16 sm:pt-36 bg-white" style="flex-basis: 484px;">
      <h1 class="text-3xl text-blue font-medium mb-2 text-wine">
        Inicia sesión
      </h1>
      <h2 class="text-base text-black font-normal mb-10 tracking-wide">
        Ingresa tus datos.
      </h2>
      <input-base
        id="email"
        v-model="app.credentials.email"
        label="Correo:"
      />
      <input-base
        id="password"
        v-model="app.credentials.password"
        type="password"
        label="Contraseña:"
        class="mt-2 mb-2"
      />
      <div class="mb-9">
        <p v-show="app.error" class="text-red-error text-xs">
          No pudimos encontrar una cuenta que coincida con lo que ingresó, revisa tus credenciales de acceso por favor.
        </p>
      </div>
      <button-base
        class="sm:max-w-sm ml-auto mr-0"
        label="Entrar"
        :loading="app.loading"
        @click="login"
        :disabled="app.disabled"
      />
    </div>
  </main>
</template>

<script>
import { computed, reactive } from 'vue'
import { authenticate } from './../api/authenticate'
import { useRouter } from 'vue-router'
import ButtonBase from './../components/ButtonBase.vue'
import InputBase from './../components/InputBase.vue'

export default {
  name: 'Login',
  components: { InputBase, ButtonBase, },
  setup() {
    const router = useRouter()
    const app = reactive({
      credentials: {
        email: '',
        password: '',
      },
      loading: false,
      disabled: computed(() => !app.credentials.email || !app.credentials.password),
      error: false,
    })
    const login = async () => {
      try {
        app.loading = true
        app.error = false
        await authenticate(app.credentials)
        router.push({ name: 'Home' })
      } catch (error) {
        app.error = true
      } finally {
        app.loading = false
      }
    }
    return { login, app }
  },
}
</script>