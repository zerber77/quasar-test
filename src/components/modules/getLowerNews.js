import {useGet} from "./axiosGet";

export async function getLowerNews(param,id){
  const {response, request} = useGet('api/news/getLowerNews/?agency='+param+'&l_id='+id)
  try {
    await request()
  }catch (e) {
    console.log('ERROR in getLowerNews',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо
  return {response}
}
