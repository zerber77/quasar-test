import {useGet} from "../axiosGet";

export async function getWordOfDay(param){
  const {response, request} = useGet('api/wordofday/getWordOfDay/?date='+param,{},true)
  try {
    await request()
  }catch (e) {
    console.log('ERROR in getWordOfDay',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо
  return {response}
}
