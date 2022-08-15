import Cookies from 'js-cookie'
export const setUserInfoCookie = (info) => {
  if(info){
    Cookies.set("UserInfo", JSON.stringify(info), { expires: 60});
  }
};
export const setUserTokenCookie = (token) => {
  if(token){
    Cookies.set("userToken", JSON.stringify(token), { expires: 60});
  }
};
export const getUserTokenCookie = () => {
  return Cookies.get("UserToken");
};
export const getUserInfoCookie = () => {
  return Cookies.get("UserInfo")===undefined?JSON.parse(Cookies.get("UserInfo")):undefined;
};
export const deleteUserInfoCookie = () => {
  Cookies.remove("UserInfo");
};
export const deleteUserTokenCookie = () => {
  Cookies.remove("UserToken");
};
