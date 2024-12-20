
import {useGet} from "./axiosGet";

export async function getUpperNews(param,id){
  const {response, request} = useGet('api/news/getUpperNews/?agency='+param+'&l_id='+id)
  try {
    await request()
  }catch (e) {
    console.log('ERROR in getUpperNews',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо
  return {response}
}
