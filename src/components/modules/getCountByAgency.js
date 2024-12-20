import {useGet} from "./axiosGet";

export async function getCountByAgency(param){
  const {response, request} = useGet('api/news/getCountByAgency/?agency='+param)
  try {
    await request()
  }catch (e) {
    console.log('ERROR in getCountByAgency',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо
  return {response}
}
