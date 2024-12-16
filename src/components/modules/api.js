////настройки axios
////НЕ ТРОГАТЬ!!!!!!

import axios from "axios";

const api = axios.create({
 // baseURL: 'http://quasar-test/api/',
  // auth:{
  //     username: 'admin',
  //     password: 'admin'
  // },
  headers: {
      'Access-Control-Allow-Origin' : '*',
      'Access-Control-Allow-Credentials' : 'true',
      'Content-Type': 'application/json',
      'Access-Control-Allow-Methods': 'GET, POST, OPTIONS',
      'Access-Control-Allow-Headers': 'Content-Type',

  },
  withCredentials:true,
  crossDomain:true
})

api.interceptors.request.use(config => {
    // config.auth = {
    //     username: 'admin',
    //     password: 'admin'
    // }
    // config.headers['Accept-Language'] = 'ru-RU'
    // config.headers['Access-Control-Request-Method'] = 'GET'
    // config.headers['Authorization'] = 'Basic YWRtaW46YWRtaW4='
    // config.headers['Access-Control-Request-Headers'] = 'X-Custom-Header'
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
