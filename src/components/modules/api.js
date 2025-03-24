////настройки axios
////НЕ ТРОГАТЬ!!!!!!

import axios from "axios";

const api = axios.create({
// baseURL: 'http://quasar-test/',
 baseURL: '/',
  // auth:{
  //     username: 'admin',
  //     password: 'admin'
  // },
  withCredentials:true,
  crossDomain:true
})

api.interceptors.request.use(config => {
    const token = localStorage.getItem('authToken')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`; // Добавляем токен в заголовок
    }
    // config.headers['Content-Type'] = 'application/x-www-form-urlencoded'
    return config
  },
  error=>{
    console.log('ERROR  in api. rejected'+error)
  })

api.interceptors.response.use(config=>{
  return config
}, error=>{
  //error.response.data.message === 'Token has expired'
  console.log('ERROR  in api. '+ error)

  return Promise.reject(error);
})

export default api
