//// Отправка данных о новой должности

import {usePost} from "../axiosPostApi";

export async function postLogInData(personData){
  const {response:data, request} = usePost('api/auth/login/', personData)
  try {
    await request()
  }catch (e) {
    console.log('ERROR in createRank usePost',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо
  console.log(data)
  return {data}
}
