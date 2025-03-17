///getReviews

import {useGet} from "../axiosGet";

export async function getReviews(){
  const {response, request} = useGet('api/review/getReviews/')
  try {
    await request()
  }catch (e) {
    console.log('ERROR in getReviews',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо
  return {response}
}

