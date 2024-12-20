import {useGet} from "./axiosGet";

export async function getAgencies(){
  const {response, request} = useGet('api/news/agencies/')
  try {
    await request()
  }catch (e) {
    console.log('ERROR in personnelApi',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо
  return {response}
}
