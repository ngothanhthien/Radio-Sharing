import router from "../router";
import { setUserInfoCookie, setUserTokenCookie } from "./cookie";

export const setLoginAction=()=>{
    localStorage.setItem("authAction", "login");
}
export const setRegisterAction=()=>{
    localStorage.setItem("authAction", "register");
}
export const isLoginAction=()=>localStorage.getItem("authAction")=="login";
export const isRegisterAction=()=>localStorage.getItem("authAction")=="register";
export const userAuthenticatedProcess =(info,token)=>{
    setUserInfoCookie(info);
    setUserTokenCookie(token);
    router.push({ name: 'UserMusicList', params: { uid: info.uid } });
}
export const userRegisterThirdPartyProcess =(email,token)=>{
    localStorage.setItem("thirdPartyEmail",email);
    router.push({ name: 'UserRegisterByThirdParty'});
}
export const redirectToBadRequest=()=>{
    router.push({name:"PageBadRequest"});
}
export const redirectToEmailExistPage=()=>{
    router.push({name:"PageEmailExist"});
}
export const redirectToEmailNotExistPage=()=>{
    router.push({name:"PageNotExistAccount"});
}
export const getThirdPartyEmail=()=>{
    return localStorage.getItem("thirdPartyEmail");
}