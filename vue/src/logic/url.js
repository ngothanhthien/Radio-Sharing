import router from '@/router/index'
export const getQueryFromUrl=(name)=>{
    return router.currentRoute._value.query[name];
}
export const getAllQueryFromUrl=()=>{
    return router.currentRoute._value.query;
}