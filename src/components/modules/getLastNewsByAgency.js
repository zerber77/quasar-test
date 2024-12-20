import {useGet} from "./axiosGet";

export async function getLastNewsByAgency(param){
  const {response, request} = useGet('api/news/getLastNewsByAgency/?agency='+param)
  try {
    await request()
  }catch (e) {
    console.log('ERROR in personnelApi',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо
  return {response}
}
