import {useGet} from "../axiosGet";

export async function getNewsByCountry(date,wordRU,wordEN){
  const {response, request} = useGet('api/map/getNewsByCountry/?date='+date+'&wordRU='+wordRU+'&wordEN='+wordEN ,{},true)
  try {
    await request()
  }catch (e) {
    console.log('ERROR in getNewsByCountry',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо
  return {response}
}
