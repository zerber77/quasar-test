
import {useGet} from "../axiosGet";

export async function getWordCountByDate(date,word){
  const {response, request} = useGet('api/statistics/wordCountByDate/?date='+date+'&word='+word,{},true)
  try {
    await request()
  }catch (e) {
    console.log('ERROR in getWordCountByDate',e)
    throw e
  }

  //////здесь обрабатываем результат как вам надо
  return {response}
}
