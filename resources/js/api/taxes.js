import { post, get, delete as destroy } from 'axios'
import { JWT } from './../helpers/localstorage'
import { API } from './baseUrl'

const getTaxes = ({ expediente, page }) => new Promise((resolve, reject) =>
  get(`${API}/api/taxes?page=${page}${expediente ? '&expediente='+expediente : ''}`, {
    headers: {
      Authorization: `Bearer ${JWT()}`,
    },
  })
  .then(({ data }) => resolve(data))
  .catch(({ response }) => reject(response))
  )

const getTax = (tax) => new Promise((resolve, reject) =>
  get(`${API}/api/taxes/${tax}`, {
    headers: {
      Authorization: `Bearer ${JWT()}`,
    },
  })
  .then(({ data }) => resolve(data))
  .catch(({ response }) => reject(response))
  )

const getTaxValidity = (tax) => new Promise((resolve, reject) =>
  get(`${API}/api/taxes/${tax.uuid}/type/${tax.type}`, {
    headers: {
      Authorization: `Bearer ${JWT()}`,
    },
  })
  .then(({ data }) => resolve(data))
  .catch(({ response }) => reject(response))
  )

const storeTaxes = ({ taxes, onUploadProgress }) => new Promise((resolve, reject) => {
  const body = new FormData()
  body.append('file', taxes)

  post(`${API}/api/taxes`, body, {
    headers: {
      Authorization: `Bearer ${JWT()}`,
    },
    onUploadProgress,
  })
  .then(({ data }) => resolve(data))
  .catch(({ response }) => reject(response))
})

const destroyTax = (tax) => new Promise((resolve, reject) =>
  destroy(`${API}/api/taxes/${tax}`, {
    headers: {
      Authorization: `Bearer ${JWT()}`,
    },
  })
  .then(({ data }) => resolve(data))
  .catch(({ response }) => reject(response))
  )

export { getTaxes, getTax, storeTaxes, destroyTax, getTaxValidity }