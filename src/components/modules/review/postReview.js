import {usePost} from "../axiosPostApi";

export async function postReview(personData){
  const {response:response, request} = usePost('api/review/', personData)
  try {
    await request()
  }catch (e) {
    console.log('ERROR in  postReview',e)
    throw e
  }
  //////здесь обрабатываем результат как вам надо
  console.log(response)
  return {response}
}
