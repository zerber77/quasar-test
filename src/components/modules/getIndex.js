import {useGet} from "./axiosGet";

export async function getIndex(){
  const {response, request} = useGet('api/test/')
  try {
    await request()
  }catch (e) {
           console.log('ERROR in personnelApi',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо

  return {response}
}
