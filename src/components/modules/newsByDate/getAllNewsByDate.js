
import {useGet} from "../axiosGet";

export async function getAllNewsByDate(date){
  const {response, request} = useGet('api/news/byDate/getAllNewsByDate/?date='+date,{},false)
  try {
    await request()
  }catch (e) {
    console.log('ERROR in getAllNewsByDate',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо
  return {response}
}
