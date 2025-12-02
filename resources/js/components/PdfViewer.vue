<template>
  <div class="block relative w-full">
    <label :for="id" class="text-wine text-base font-medium block relative mb-2">
      {{ label }}
    </label>

    <div class="w-full border border-wine rounded-md overflow-hidden" :style="{ height: containerHeight }">
      <iframe
        v-if="viewerSrc"
        :src="viewerSrc"
        class="w-full h-full"
        style="border: 0;"
        allowfullscreen
      ></iframe>

      <div v-else class="p-4 text-sm text-gray-600">
        No hay PDF para mostrar.
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PdfIframeViewer',
  props: {
    id: { type: String, default: 'pdf-iframe-viewer' },
    label: { type: String, default: 'PDF' },
    // Base64 (data URI o solo cadena)
    srcBase64: { type: String, required: true },
    containerHeight: { type: String, default: '70vh' },
    // Usa el viewer estático en el MISMO ORIGEN
    viewerPath: { type: String, default: '/pdfjs/web/viewer.html' },
  },
  data() {
    return { viewerSrc: '', blobUrl: '' };
  },
  computed: {
    viewerBaseUrl() {
      // Construye URL absoluta con el mismo origen que tu app (ej: http://127.0.0.1:8000)
      return `${window.location.origin}${this.viewerPath}`;
    }
  },
  watch: {
    srcBase64: { immediate: true, handler(val) { this.setupViewer(val); } },
  },
  beforeUnmount() {
    if (this.blobUrl) URL.revokeObjectURL(this.blobUrl);
  },
  methods: {
    setupViewer(input) {
      if (this.blobUrl) { URL.revokeObjectURL(this.blobUrl); this.blobUrl = ''; }
      if (!input || typeof input !== 'string') { this.viewerSrc = ''; return; }

      const base64 = input.startsWith('data:') ? (input.split(',')[1] || '') : input;

      try {
        const bin = atob(base64);
        const bytes = new Uint8Array(bin.length);
        for (let i = 0; i < bin.length; i++) bytes[i] = bin.charCodeAt(i);
        const blob = new Blob([bytes], { type: 'application/pdf' });

        this.blobUrl = URL.createObjectURL(blob);
        const fileParam = encodeURIComponent(this.blobUrl);

        // Usa URL ABSOLUTA al viewer para evitar que Vue Router intente navegar a esa ruta
        this.viewerSrc = `${this.viewerBaseUrl}?file=${fileParam}#zoom=page-width`;
      } catch (e) {
        console.error('Error preparando PDF base64:', e);
        this.viewerSrc = '';
      }
    },
  },
};
</script>

<style scoped>
:host, div, iframe { max-width: 100%; }
</style>