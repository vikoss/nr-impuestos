const singPdfRoutes = [
  {
    path: '/firmar-pdf',
    name: 'SignPdfIndex',
    component: () => import ('./../views/SignPdf/index.vue'),
  },
  {
    path: '/qr/:uuid',
    name: 'SignPdfValidity',
    component: () => import ('./../views/SignPdf/Public/Validity.vue'),
  },
]

export { singPdfRoutes }
