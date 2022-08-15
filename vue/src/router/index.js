import { createRouter, createWebHistory } from 'vue-router'
import {getUserTokenCookie,getUserInfoCookie} from '../logic/cookie'
import {getThirdPartyEmail} from '../logic/auth'
import PageNotFound from '@/views/PageNotFound.vue'
import PageBadRequest from '../views/PageBadRequest.vue'
function authenticatedGuard(){
  if(!isLogged()){
    return {
      name:"UserLogin"
    }
  }
}
function loggedGuard(){
  if(isLogged()){
    const user=getUserInfoCookie();
    return {
      name:"UserMusicList",
      params: {
        uid:user['uid']
      }
    }
  }
}
function isLogged(){
  return getUserTokenCookie()?true:false;
}
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path:'/',
      redirect:{
        name:'UserLogin'
      }
    },
    {
      path: '/login',
      name: 'UserLogin',
      component: ()=>import('@/views/UserLoginView.vue'),
      beforeEnter: [loggedGuard]
    },
    {
      path:'/register',
      name:'UserRegister',
      component:()=>import('@/views/UserRegisterView.vue'),
      beforeEnter: [loggedGuard]
    },
    {
      path:'/register-by-third-party',
      name:'UserRegisterByThirdParty',
      component:()=>import('@/views/UserRegisterWithThirdPartyView.vue'),
      props:{
        token:'',
        email:'',
      },
      beforeEnter: [
        loggedGuard,
        ()=>{
          // if(getThirdPartyEmail){
          //   return {
          //     name:'UserRegister'
          //   }
          // }
        }
      ],
    },
    {
      path:'/view/:uid',
      name:'UserMusicList',
      component:()=>import('@/views/UserMusicListView.vue'),
      beforeEnter: [authenticatedGuard]
    },
    {
      path:'/callback/google/process',
      name:'GoogleCallback',
      component:()=>import('@/process/GoogleCallbackProcess.vue'),
    },
    {
      path: "/:catchAll(.*)",
      redirect: '/404',
    },
    {
      path:"/404",
      component: PageNotFound,
      name:"PageNotFound"
    },
    {
      path:"/503",
      component: PageBadRequest,
      name:"PageBadRequest"
    },
    {
      path:"/mail-exist",
      component: ()=>import('@/views/PageEmailExist.vue'),
      name:"PageEmailExist"
    },
    {
      path:"/mail-not-register",
      component: ()=>import('@/views/PageNotExistAccount.vue'),
      name:"PageNotExistAccount"
    },
  ]
})
export default router
