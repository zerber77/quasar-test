
import {useGet} from "../axiosGet";

export async function getNewsWordDyDate(date,word){
  const {response, request} = useGet('api/statistics/getNewsWordDyDate/?date='+date+'&word='+word,{},false)
  try {
    await request()
  }catch (e) {
    console.log('ERROR in getWordCountByDate',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо
  return {response}
}
