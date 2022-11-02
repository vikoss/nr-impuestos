import { post, get } from 'axios'
import { JWT } from './../helpers/localstorage'
import { API } from './baseUrl'
import { getCurrentTimestamp } from './../helpers/dateTime'

const authenticate = (credentials) => new Promise((resolve, reject) => {
  post(`${API}/api/login`, credentials)
    .then(({ data }) => {
      localStorage.setItem('nr-impuestos-jwt', JSON.stringify({
        expiredAt: (data.authorisation.expires_in * 1000) + getCurrentTimestamp(),
        accessToken: data.authorisation.token,
        user: data.user,
      }))
      resolve(data)
    })
    .catch(({ response }) => reject(response))
})

const me = () => new Promise((resolve, reject) => {
  get(`${API}/api/me`, {
    headers: {
      Authorization: `Bearer ${JWT()}`,
    },
  })
    .then(({ data }) => resolve(data))
    .catch(({ response }) => reject(response))
})

const logout = () => new Promise((resolve, reject) => {
  post(`${API}/api/logout`, {}, {
    headers: {
      Authorization: `Bearer ${JWT()}`,
    },
  })
    .then(({ data }) => {
      localStorage.removeItem('nr-impuestos-jwt')
      resolve(data)
    })
    .catch(({ response }) => reject(response))
})

export { authenticate, me, logout }