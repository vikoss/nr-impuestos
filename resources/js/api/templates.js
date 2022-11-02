import { post } from 'axios'
import { JWT } from './../helpers/localstorage'
import { API } from './baseUrl'

const generateQrCodePDF = (tax) => new Promise((resolve, reject) =>
  post(`${API}/api/templates/qr-code`, tax, {
    headers: {
      Authorization: `Bearer ${JWT()}`,
    },
    responseType: 'blob',
  })
  .then(({ data }) => resolve(data))
  .catch(({ response }) => reject(response))
  )

export { generateQrCodePDF }