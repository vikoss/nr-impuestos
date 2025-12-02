import { post, get, delete as destroy } from 'axios'
import { JWT } from './../helpers/localstorage'
import { API } from './baseUrl'

const getPdfSignatureByUuid = (uuid) => new Promise((resolve, reject) =>
  get(`${API}/api/qr/${uuid}`, {
    headers: {
      Authorization: `Bearer ${JWT()}`,
    },
  })
  .then(({ data }) => resolve(data))
  .catch(({ response }) => reject(response))
  )

const signPdf = (file, onUploadProgress) => new Promise((resolve, reject) => {
  const body = new FormData()
  body.append('file', file)

  post(`${API}/api/sign-pdf`, body, {
    headers: {
      Authorization: `Bearer ${JWT()}`,
    },
    onUploadProgress,
  })
  .then(({ data }) => resolve(data))
  .catch(({ response }) => reject(response))
})

export { getPdfSignatureByUuid, signPdf }
