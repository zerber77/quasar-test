import {useGet} from "./axiosGet";

export async function getFirstNewsByAgency(param){
  const {response, request} = useGet('api/news/getFirstNewsByAgency/?agency='+param)
  try {
    await request()
  }catch (e) {
    console.log('ERROR in getFirstNewsByAgency',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо
  return {response}
}
