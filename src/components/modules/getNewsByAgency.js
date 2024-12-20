import {useGet} from "./axiosGet";

export async function getNewsByAgency(param){
  const {response, request} = useGet('api/news/getNewsByAgency/?agency='+param)
  try {
    await request()
  }catch (e) {
    console.log('ERROR in personnelApi',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо
  return {response}
}
